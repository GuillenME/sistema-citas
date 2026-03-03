<?php

namespace App\Http\Controllers;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Cliente;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR CITAS DEL CLIENTE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente) {
            abort(403, 'Cliente no encontrado');
        }

        $citas = Cita::where('client_id', $cliente->id)
            ->with(['service' => function ($query) {
                $query->with(['promociones' => function ($q) {
                    $q->where('published', true)
                        ->where('start_date', '<=', now()->toDateString())
                        ->where('end_date', '>=', now()->toDateString());
                }]);
            }])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $porcentajeAnticipo = config('citas.porcentaje_anticipo', 50);
        $porcentajeRestante = 100 - $porcentajeAnticipo;

        return view('cliente.citas.index', compact('citas', 'porcentajeAnticipo', 'porcentajeRestante'));
    }

    public function create()
    {
        $servicios = Servicio::where('active', 1)
            ->whereHas('empleados', function ($q) {
                $q->where('active', 1);
            })
            ->get();

        $porcentajeAnticipo = config('citas.porcentaje_anticipo', 50);
        $porcentajeRestante = 100 - $porcentajeAnticipo;

        return view('cliente.citas.create', compact('servicios', 'porcentajeAnticipo', 'porcentajeRestante'));
    }

    public function serviciosDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);

        $fecha = Carbon::parse($request->fecha);
        if ($fecha->isSunday()) {
            return response()->json([]);
        }

        $serviciosDisponibles = Servicio::query()
            ->where('active', 1)
            ->whereHas('empleados', function ($q) {
                $q->where('active', 1);
            })
            ->with(['empleados' => function ($q) {
                $q->where('active', 1)->with('schedules');
            }])
            ->get()
            ->filter(function (Servicio $servicio) use ($fecha) {
                return !empty($this->buildAvailableBlocksForService($servicio, $fecha));
            })
            ->pluck('id')
            ->values();

        return response()->json($serviciosDisponibles);
    }

    /*
    |--------------------------------------------------------------------------
    | BLOQUES DISPONIBLES
    |--------------------------------------------------------------------------
    */
    public function bloquesDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicio_id' => 'required|exists:services,id',
        ]);

        $fecha = Carbon::parse($request->fecha);
        if ($fecha->isSunday()) {
            return response()->json([]);
        }

        $servicio = Servicio::with(['empleados' => function ($q) {
            $q->where('active', 1)->with('schedules');
        }])->findOrFail($request->servicio_id);

        $bloquesDisponibles = $this->buildAvailableBlocksForService($servicio, $fecha);
        return response()->json($bloquesDisponibles);
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR CITA
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'servicio_id'       => 'required|exists:services,id',
            'fecha'             => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->isSunday()) {
                        $fail('Los domingos no se atiende.');
                    }
                },
            ],
            'hora_inicio'       => 'required|date_format:H:i',
            'acepta_privacidad' => 'required|accepted',
        ]);

        $cliente = Cliente::where('user_id', auth()->id())->first();
        if (!$cliente) {
            abort(403);
        }

        $servicio = Servicio::with(['empleados' => function ($q) {
            $q->where('active', 1)->with('schedules');
        }])->findOrFail($request->servicio_id);

        if (!$servicio->active) {
            return back()->withErrors(['servicio_id' => 'Servicio no disponible'])->withInput();
        }

        if ($servicio->empleados->isEmpty()) {
            return back()->withErrors(['servicio_id' => 'No hay empleados disponibles para este servicio'])->withInput();
        }

        $fecha = Carbon::parse($request->fecha);
        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duration_minutes);

        if ($this->isInsideLunchBreak($horaInicio->format('H:i'), $horaFin->format('H:i'))) {
            return back()->withErrors([
                'hora_inicio' => 'Ese horario corresponde a la hora de comida. Elige otro bloque.'
            ])->withInput();
        }

        $driver = DB::getDriverName();
        $lockName = 'citas:' . $request->fecha;

        if ($driver === 'mysql') {
            $lock = DB::selectOne('SELECT GET_LOCK(?, 10) AS l', [$lockName]);
            if ((int) ($lock->l ?? 0) !== 1) {
                return back()->withErrors(['hora_inicio' => 'Intenta nuevamente'])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $empleadoAsignado = null;

            foreach ($servicio->empleados as $empleado) {
                if (!$this->isWithinEmployeeSchedule(
                    $empleado,
                    $fecha,
                    $horaInicio->format('H:i'),
                    $horaFin->format('H:i')
                )) {
                    continue;
                }

                $citaSolapada = Cita::where('employee_id', $empleado->id)
                    ->whereDate('date', $request->fecha)
                    ->whereIn('status', [
                        CitaStatus::CONFIRMADA,
                        CitaStatus::PENDIENTE_ANTICIPO
                    ])
                    ->where(function ($query) use ($horaInicio, $horaFin) {
                        $query->where('start_time', '<', $horaFin->format('H:i'))
                            ->where('end_time', '>', $horaInicio->format('H:i'));
                    })
                    ->lockForUpdate()
                    ->exists();

                if (!$citaSolapada) {
                    $empleadoAsignado = $empleado;
                    break;
                }
            }

            if (!$empleadoAsignado) {
                DB::rollBack();
                return back()->withErrors([
                    'hora_inicio' => 'Ya no hay empleados disponibles en ese horario.'
                ])->withInput();
            }

            Cita::create([
                'client_id'   => $cliente->id,
                'service_id'  => $servicio->id,
                'employee_id' => $empleadoAsignado->id,
                'date'        => $request->fecha,
                'start_time'  => $horaInicio->format('H:i'),
                'end_time'    => $horaFin->format('H:i'),
                'status'      => CitaStatus::PENDIENTE_ANTICIPO,
            ]);

            DB::commit();

            return redirect()
                ->route('cliente.citas.index')
                ->with('success', 'Cita agendada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Ocurrio un error al agendar.'
            ])->withInput();
        } finally {
            if ($driver === 'mysql') {
                DB::selectOne('SELECT RELEASE_LOCK(?)', [$lockName]);
            }
        }
    }

    public function cancelar(Cita $cita)
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente || $cita->client_id !== $cliente->id) {
            abort(403);
        }

        if (!in_array($cita->status, [CitaStatus::PENDIENTE_ANTICIPO, CitaStatus::CONFIRMADA], true)) {
            return back()->with('error', 'Solo puedes gestionar citas pendientes o confirmadas.');
        }

        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $inicioCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('start_time'));
        $ahora = now();
        $minutosRestantes = $ahora->diffInMinutes($inicioCita, false);

        if ($minutosRestantes <= 0) {
            return back()->with('error', 'No puedes cancelar una cita que ya inicio.');
        }

        $anticipacionRequerida = $minutosRestantes <= 60 ? 10 : 20;
        if ($minutosRestantes < $anticipacionRequerida) {
            return back()->with(
                'error',
                "Debes cancelar con al menos {$anticipacionRequerida} minutos de anticipacion."
            );
        }

        $notaBase = trim((string) ($cita->notes ?? ''));
        $tieneAnticipo = !empty($cita->receipt) || $cita->status === CitaStatus::CONFIRMADA;
        $motivo = $tieneAnticipo
            ? 'Cancelada por el cliente. Anticipo no reembolsable.'
            : 'Cancelada por el cliente.';
        $notaFinal = $notaBase === ''
            ? $motivo
            : $notaBase . ' | ' . $motivo;

        $cita->update([
            'status' => CitaStatus::CANCELADA,
            'notes' => $notaFinal,
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => CitaStatus::CANCELADA,
            'user_id' => auth()->id(),
            'change_date' => now(),
        ]);

        return back()->with('success', 'Cita cancelada correctamente.');
    }

    public function reagendar(Request $request, Cita $cita)
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente || $cita->client_id !== $cliente->id) {
            abort(403);
        }

        if (!in_array($cita->status, [CitaStatus::PENDIENTE_ANTICIPO, CitaStatus::CONFIRMADA], true)) {
            return back()->with('error', 'Solo puedes reagendar citas pendientes o confirmadas.');
        }

        $reagendas = $cita->estados()->where('status', 'reagendada')->count();
        if ($reagendas >= 1) {
            return back()->with('error', 'Solo puedes reagendar esta cita una vez.');
        }

        $fechaOriginal = Carbon::parse($cita->date)->format('Y-m-d');
        $inicioOriginal = Carbon::parse($fechaOriginal . ' ' . $cita->getRawOriginal('start_time'));
        if (now()->diffInMinutes($inicioOriginal, false) < 24 * 60) {
            return back()->with('error', 'Solo puedes reagendar con al menos 24 horas de anticipacion.');
        }

        $request->validate([
            'fecha' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->isSunday()) {
                        $fail('Los domingos no se atiende. Por favor selecciona otro dia.');
                    }
                },
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'observaciones' => 'nullable|string|max:500',
        ]);

        if (!$cita->service) {
            return back()->with('error', 'La cita no tiene servicio asociado.');
        }

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($cita->service->duration_minutes);

        if ($this->isInsideLunchBreak($horaInicio->format('H:i'), $horaFin->format('H:i'))) {
            return back()->with('error', 'Ese horario corresponde a la hora de comida. Elige otro bloque.');
        }

        $nuevaFechaHora = Carbon::parse($request->fecha . ' ' . $horaInicio->format('H:i'));
        if (now()->diffInMinutes($nuevaFechaHora, false) < 24 * 60) {
            return back()->with('error', 'La nueva fecha y hora debe ser al menos 24 horas despues de este momento.');
        }

        $citaSolapada = Cita::query()
            ->whereDate('date', $request->fecha)
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
            ->where('id', '!=', $cita->id)
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->where('start_time', '<', $horaFin->format('H:i'))
                    ->where('end_time', '>', $horaInicio->format('H:i'));
            })
            ->exists();

        if ($citaSolapada) {
            return back()->with('error', 'El horario nuevo se cruza con otra cita.');
        }

        $notaReagendada = trim((string) $request->observaciones);
        $notaAnterior = trim((string) ($cita->notes ?? ''));
        $notaFinal = 'Reagendada por cliente.';
        if ($notaReagendada !== '') {
            $notaFinal .= ' ' . $notaReagendada;
        }
        if ($notaAnterior !== '') {
            $notaFinal .= ' | Nota anterior: ' . $notaAnterior;
        }

        $cita->update([
            'date' => $request->fecha,
            'start_time' => $horaInicio->format('H:i'),
            'end_time' => $horaFin->format('H:i'),
            'notes' => $notaFinal,
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'reagendada',
            'user_id' => auth()->id(),
            'change_date' => now(),
        ]);

        return back()->with('success', 'Cita reagendada correctamente. Tu anticipo se mantiene para la nueva fecha.');
    }

    public function subirComprobante(Request $request, Cita $cita)
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente || $cita->client_id !== $cliente->id) {
            abort(403);
        }

        if ($cita->status !== CitaStatus::PENDIENTE_ANTICIPO) {
            return back()->withErrors('Esta cita no acepta comprobantes');
        }

        if ($cita->payment_deadline && now()->greaterThan($cita->payment_deadline) && empty($cita->receipt)) {
            $cita->update([
                'status' => CitaStatus::CANCELADA,
                'notes' => 'Cita cancelada por no reenviar el comprobante dentro de los 15 minutos.',
                'payment_deadline' => null,
            ]);

            CitaEstado::create([
                'appointment_id' => $cita->id,
                'status' => CitaStatus::CANCELADA,
                'user_id' => auth()->id(),
                'change_date' => now(),
            ]);

            return back()->withErrors('El tiempo para reenviar comprobante ya vencio. La cita fue cancelada.');
        }

        $request->validate([
            'comprobante' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $ruta = $request->file('comprobante')->store('comprobantes', 'public');

        $cita->update([
            'receipt' => $ruta,
            'payment_deadline' => null,
        ]);

        return back()->with(
            'info',
            'Estamos validando tu anticipo. Te notificaremos cuando sea confirmado.'
        );
    }

    private function buildAvailableBlocksForService(Servicio $servicio, Carbon $fecha): array
    {
        $empleados = $servicio->empleados;
        if ($empleados->isEmpty()) {
            return [];
        }

        $diaSemana = $fecha->dayOfWeek; // 0 domingo - 6 sabado
        $duracion = (int) $servicio->duration_minutes;

        $citasDelDia = Cita::whereDate('date', $fecha->toDateString())
            ->whereIn('employee_id', $empleados->pluck('id'))
            ->whereIn('status', [
                CitaStatus::CONFIRMADA,
                CitaStatus::PENDIENTE_ANTICIPO,
            ])
            ->get()
            ->groupBy('employee_id');

        $bloquesDisponibles = [];

        foreach ($empleados as $empleado) {
            $horarios = $empleado->schedules->where('day_of_week', $diaSemana);
            if ($horarios->isEmpty()) {
                continue;
            }

            foreach ($horarios as $horario) {
                $inicioR = Carbon::parse($horario->start_time)->hour * 60
                    + Carbon::parse($horario->start_time)->minute;
                $finR = Carbon::parse($horario->end_time)->hour * 60
                    + Carbon::parse($horario->end_time)->minute;

                if ($fecha->isToday()) {
                    $horaActual = Carbon::now()->addHour();
                    $horaMinima = $horaActual->hour * 60 + $horaActual->minute;
                    $inicioR = max($inicioR, $horaMinima);
                }

                for ($min = $inicioR; $min + $duracion <= $finR; $min += 15) {
                    $finBloque = $min + $duracion;
                    $citasEmpleado = $citasDelDia->get($empleado->id, collect());
                    $ocupado = false;
                    $inicioBloque = sprintf('%02d:%02d', intdiv($min, 60), $min % 60);
                    $finBloqueFmt = sprintf('%02d:%02d', intdiv($finBloque, 60), $finBloque % 60);

                    if ($this->isInsideLunchBreak($inicioBloque, $finBloqueFmt)) {
                        continue;
                    }

                    foreach ($citasEmpleado as $cita) {
                        $inicioCita = Carbon::parse($cita->start_time)->hour * 60
                            + Carbon::parse($cita->start_time)->minute;
                        $finCita = Carbon::parse($cita->end_time)->hour * 60
                            + Carbon::parse($cita->end_time)->minute;

                        if ($min < $finCita && $finBloque > $inicioCita) {
                            $ocupado = true;
                            break;
                        }
                    }

                    if (!$ocupado) {
                        $bloquesDisponibles[] = [
                            'inicio' => $inicioBloque,
                            'fin' => $finBloqueFmt,
                        ];
                    }
                }
            }
        }

        return collect($bloquesDisponibles)
            ->unique('inicio')
            ->sortBy('inicio')
            ->values()
            ->all();
    }

    private function isWithinEmployeeSchedule($empleado, Carbon $fecha, string $horaInicio, string $horaFin): bool
    {
        $diaSemana = $fecha->dayOfWeek;

        return $empleado->schedules
            ->where('day_of_week', $diaSemana)
            ->contains(function ($horario) use ($horaInicio, $horaFin) {
                $inicio = Carbon::parse($horario->start_time)->format('H:i');
                $fin = Carbon::parse($horario->end_time)->format('H:i');
                return $horaInicio >= $inicio
                    && $horaFin <= $fin
                    && !$this->isInsideLunchBreak($horaInicio, $horaFin);
            });
    }

    private function isInsideLunchBreak(string $horaInicio, string $horaFin): bool
    {
        $comidaInicio = (string) config('citas.horarios.comida_inicio', '15:00');
        $comidaFin = (string) config('citas.horarios.comida_fin', '16:00');

        // Si la configuracion viene invalida, no bloquear.
        if (!preg_match('/^\d{2}:\d{2}$/', $comidaInicio) || !preg_match('/^\d{2}:\d{2}$/', $comidaFin)) {
            return false;
        }

        if ($comidaInicio >= $comidaFin) {
            return false;
        }

        return $horaInicio < $comidaFin && $horaFin > $comidaInicio;
    }
}
