<?php

namespace App\Http\Controllers\Recepcionista;

use App\Constants\CitaStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentDateTimeRequest;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Cliente;
use App\Models\RecepcionistaReminder;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    private function citaYaInicio(Cita $cita): bool
    {
        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $inicioCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('start_time'));

        return now()->greaterThanOrEqualTo($inicioCita);
    }

    public function __construct(private AppointmentAvailabilityService $availability)
    {
    }

    public function dashboard()
    {
        $today = now()->toDateString();
        $horaActual = now()->format('H:i');

        $citasHoy = Cita::whereDate('date', $today)->count();
        $pendientes = Cita::whereDate('date', $today)
            ->where('status', 'pendiente_anticipo')
            ->count();
        $confirmadas = Cita::whereDate('date', $today)
            ->where('status', 'confirmada')
            ->count();
        $canceladas = Cita::whereDate('date', $today)
            ->where('status', 'cancelada')
            ->count();

      $citasRecientes = Cita::with(['client.user', 'service'])
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')        // más próxima primero
            ->orderBy('start_time')
            ->limit(3)
            ->get();

        $recordatorios = RecepcionistaReminder::query()
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('recepcionista.dashboard', compact(
            'citasHoy',
            'pendientes',
            'confirmadas',
            'canceladas',
            'citasRecientes',
            'recordatorios'
        ));
    }

    public function create()
    {
        $servicios = Servicio::where('active', 1)
            ->whereHas('empleados', function ($q) {
                $q->where('active', 1);
            })
            ->with(['promociones' => function($query) {
                $query->where('published', true)
                    ->where('start_date', '<=', now()->toDateString())
                    ->where('end_date', '>=', now()->toDateString());
            }])
            ->get();

        $usuarios = Usuario::where('role_id', 2)
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        return view('recepcionista.citas.create', compact('servicios', 'usuarios'));
    }

    public function store(AppointmentDateTimeRequest $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:services,id',
            'anticipo_recibido' => 'required|accepted',
            'anticipo_monto' => 'required|numeric|min:0.01',
        ], [
            'anticipo_recibido.required' => 'Debes confirmar que se recibio anticipo en recepcion.',
            'anticipo_recibido.accepted' => 'Debes confirmar que se recibio anticipo en recepcion.',
            'anticipo_monto.required' => 'Debes capturar el monto del anticipo.',
            'anticipo_monto.min' => 'El monto del anticipo debe ser mayor a 0.',
        ]);

        $cliente = Cliente::where('user_id', $request->usuario_id)->first();

        if (!$cliente) {
            return back()->with('error', 'El usuario no es cliente');
        }

        $servicio = Servicio::with(['empleados' => function ($q) {
            $q->where('active', 1)->with(['schedules', 'breaks']);
        }])->findOrFail($request->servicio_id);

        if (!$servicio->active) {
            return back()->with('error', 'Servicio no disponible')->withInput();
        }

        if ($servicio->empleados->isEmpty()) {
            return back()->with('error', 'No hay empleados disponibles para este servicio')->withInput();
        }

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duration_minutes);
        $precioServicio = $servicio->precioConDescuento();
        $anticipo = (float) $request->anticipo_monto;
        $totalPagado = $anticipo;
        $status = $anticipo >= $precioServicio ? 'completada' : 'confirmada';

        $driver = DB::getDriverName();
        $lockName = 'citas:' . $request->fecha;

        if ($driver === 'mysql') {
            $lock = DB::selectOne('SELECT GET_LOCK(?, 10) AS l', [$lockName]);
            if ((int) ($lock->l ?? 0) !== 1) {
                return back()->with('error', 'Intenta nuevamente')->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $empleadoAsignado = $this->availability->findAssignableEmployee(
                $servicio,
                Carbon::parse($request->fecha),
                $horaInicio->format('H:i'),
                $horaFin->format('H:i'),
                true
            );

            if (!$empleadoAsignado) {
                DB::rollBack();
                return back()->with('error', 'Ya no hay empleados disponibles en ese horario.')->withInput();
            }

            Cita::create([
                'client_id' => $cliente->id,
                'service_id' => $servicio->id,
                'employee_id' => $empleadoAsignado->id,
                'date' => $request->fecha,
                'start_time' => $horaInicio->format('H:i'),
                'end_time' => $horaFin->format('H:i'),
                'service_price' => $servicio->price,
                'deposit_amount' => $anticipo,
                'total_paid' => $totalPagado,
                'status' => $status,
                'notes' => 'Anticipo recibido en recepcion: $' . number_format($anticipo, 2),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrio un error al agendar la cita.')->withInput();
        } finally {
            if ($driver === 'mysql') {
                DB::selectOne('SELECT RELEASE_LOCK(?)', [$lockName]);
            }
        }

        return redirect()
            ->route('recepcionista.dashboard')
            ->with('success', 'Cita agendada correctamente');
    }

    public function index()
    {
        $inicioSemana = now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $finSemana = now()->endOfWeek(Carbon::SUNDAY)->toDateString();

        $baseQuery = Cita::query()
            ->with(['client.user', 'service'])
            ->withCount([
                'estados as reagendas_count' => function ($query) {
                    $query->where('status', 'reagendada');
                },
            ])
            ->whereBetween('date', [$inicioSemana, $finSemana]);

        $statusCounts = Cita::query()
            ->whereBetween('date', [$inicioSemana, $finSemana])
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
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(4)
            ->withQueryString();

        return view('recepcionista.citas.index', compact('citas', 'statusOptions', 'selectedStatus'));
    }

    public function reagendar(AppointmentDateTimeRequest $request, Cita $cita)
    {
        if (!in_array($cita->status, ['confirmada', 'pendiente_anticipo'], true)) {
            return back()->with('error', 'Solo se pueden reagendar citas confirmadas o pendientes de anticipo.');
        }

        if ($this->citaYaInicio($cita)) {
            return back()->with('error', 'La cita ya inicio o ya paso y no puede reagendarse.');
        }

        $reagendas = $cita->estados()->where('status', 'reagendada')->count();
        if ($reagendas >= 2) {
            return back()->with('error', 'Esta cita ya alcanzo el maximo de 2 reagendas.');
        }

        if (!$cita->service) {
            return back()->with('error', 'La cita no tiene servicio asociado.');
        }

        $cita->service->load([
            'empleados' => function ($q) {
                $q->where('active', 1)->with(['schedules', 'breaks']);
            },
        ]);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($cita->service->duration_minutes);

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
        $notaFinal = 'Reagendada por recepcionista.';
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

        return back()->with('success', 'Cita reagendada correctamente.');
    }

}
