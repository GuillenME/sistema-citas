<?php

namespace App\Http\Controllers;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Http\Requests\AppointmentDateTimeRequest;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    private const STATUS_LABELS = [
        CitaStatus::PENDIENTE_ANTICIPO => 'Pendientes de anticipo',
        CitaStatus::CONFIRMADA => 'Confirmadas',
        CitaStatus::COMPLETADA => 'Completadas',
        CitaStatus::CANCELADA => 'Canceladas',
        CitaStatus::NO_ASISTIO => 'No asistio',
    ];

    private const STATUS_PRIORITY = [
        CitaStatus::PENDIENTE_ANTICIPO,
        CitaStatus::CONFIRMADA,
        CitaStatus::COMPLETADA,
        CitaStatus::CANCELADA,
        CitaStatus::NO_ASISTIO,
    ];

    public function __construct(private AppointmentAvailabilityService $availability)
    {
    }

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

        $baseQuery = Cita::query()
            ->where('client_id', $cliente->id)
            ->with('estados.user')
            ->with(['service' => function ($query) {
                $query->with(['promociones' => function ($q) {
                    $q->where('published', true)
                        ->where('start_date', '<=', now()->toDateString())
                        ->where('end_date', '>=', now()->toDateString());
                }]);
            }]);

        $statusCounts = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusOptions = collect(self::STATUS_PRIORITY)
            ->filter(fn (string $status) => (int) ($statusCounts[$status] ?? 0) > 0)
            ->map(fn (string $status) => [
                'key' => $status,
                'label' => self::STATUS_LABELS[$status] ?? ucfirst($status),
                'count' => (int) ($statusCounts[$status] ?? 0),
            ])
            ->values();

        $defaultStatus = collect(self::STATUS_PRIORITY)
            ->first(fn (string $status) => (int) ($statusCounts[$status] ?? 0) > 0);

        $requestedStatus = (string) request('status');
        $selectedStatus = $requestedStatus !== '' && CitaStatus::isValid($requestedStatus) && (int) ($statusCounts[$requestedStatus] ?? 0) > 0
            ? $requestedStatus
            : $defaultStatus;

        $citas = (clone $baseQuery)
            ->when($selectedStatus, fn ($query) => $query->where('status', $selectedStatus))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        $porcentajeAnticipo = config('citas.porcentaje_anticipo', 50);
        $porcentajeRestante = 100 - $porcentajeAnticipo;

        return view('cliente.citas.index', compact(
            'citas',
            'porcentajeAnticipo',
            'porcentajeRestante',
            'statusOptions',
            'selectedStatus'
        ));
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
                return !empty($this->availability->buildAvailableBlocksForService($servicio, $fecha));
            })
            ->pluck('id')
            ->values();

        return response()->json($serviciosDisponibles);
    }

    public function fechasDisponibles(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'dias' => 'nullable|integer|min:7|max:120',
        ]);

        $horizonteDias = (int) $request->input('dias', 60);
        $inicio = today();
        $fin = today()->addDays($horizonteDias);

        $servicio = Servicio::query()
            ->where('active', 1)
            ->with(['empleados' => function ($q) {
                $q->where('active', 1)->with('schedules');
            }])
            ->findOrFail($request->servicio_id);

        if ($servicio->empleados->isEmpty()) {
            return response()->json([]);
        }

        $fechasDisponibles = [];
        $cursor = $inicio->copy();

        while ($cursor->lte($fin)) {
            if (!$cursor->isSunday()) {
                $bloques = $this->availability->buildAvailableBlocksForService($servicio, $cursor->copy());
                if (!empty($bloques)) {
                    $fechasDisponibles[] = $cursor->toDateString();
                }
            }

            $cursor->addDay();
        }

        return response()->json($fechasDisponibles);
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

        $bloquesDisponibles = $this->availability->buildAvailableBlocksForService($servicio, $fecha);
        return response()->json($bloquesDisponibles);
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR CITA
    |--------------------------------------------------------------------------
    */
    public function store(AppointmentDateTimeRequest $request)
    {
        $request->validate([
            'servicio_id'       => 'required|exists:services,id',
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

            $empleadoAsignado = $this->availability->findAssignableEmployee(
                $servicio,
                $fecha,
                $horaInicio->format('H:i'),
                $horaFin->format('H:i'),
                true
            );

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
                ->with('success', 'Cita agendada correctamente. Por favor, realiza el pago del anticipo para confirmar tu cita.');
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

    public function reagendar(AppointmentDateTimeRequest $request, Cita $cita)
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

        if (!$cita->service) {
            return back()->with('error', 'La cita no tiene servicio asociado.');
        }

        $cita->service->loadMissing(['empleados' => function ($q) {
            $q->where('active', 1)->with('schedules');
        }]);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($cita->service->duration_minutes);

        $nuevaFechaHora = Carbon::parse($request->fecha . ' ' . $horaInicio->format('H:i'));
        if (now()->diffInMinutes($nuevaFechaHora, false) < 24 * 60) {
            return back()->with('error', 'La nueva fecha y hora debe ser al menos 24 horas despues de este momento.');
        }

        $empleadoAsignado = $this->availability->findAssignableEmployee(
            $cita->service,
            Carbon::parse($request->fecha),
            $horaInicio->format('H:i'),
            $horaFin->format('H:i'),
            false,
            $cita->id
        );

        if (!$empleadoAsignado) {
            return back()->with('error', 'No hay empleados disponibles para ese nuevo horario.');
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
            'employee_id' => $empleadoAsignado->id,
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

}
