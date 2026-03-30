<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentDateTimeRequest;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Notifications\CitaClienteNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AnticipoCanceladoPorRechazosMail;
use App\Mail\PagoRechazadoMail;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminCitaController extends Controller
{
    public function __construct(private AppointmentAvailabilityService $availability) {}

    private function citaYaInicio(Cita $cita): bool
    {
        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $inicioCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('start_time'));

        return now()->greaterThanOrEqualTo($inicioCita);
    }

    public function index(Request $request)
    {
        $hoy = today();
        $focusCitaId = (int) $request->query('focus_cita', 0);

        // Cancelar citas vencidas automaticamente
        Cita::where('status', 'pendiente_anticipo')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->whereNull('receipt')
            ->update([
                'status' => 'cancelada',
                'notes' => 'Cita cancelada automaticamente por no reenviar anticipo a tiempo.',
                'receipt' => null,
                'payment_deadline' => null,
                'payment_attempts' => 0
            ]);
        $citas = Cita::with(['client.user', 'client.appointments.service', 'client.appointments.employee', 'service', 'employee', 'estados.user'])
            ->withCount([
                'estados as reagendas_count' => function ($query) {
                    $query->where('status', 'reagendada');
                },
            ]);

        if ($focusCitaId > 0) {
            $citas->orderByRaw('CASE WHEN id = ? THEN 0 ELSE 1 END', [$focusCitaId]);
        }

        $citas = $citas
            ->orderByDesc('created_at')
            ->paginate(5);

        $empleados = Empleado::where('active', 1)->get();

        $stats = [
            'hoy' => Cita::query()
                ->whereDate('date', $hoy)
                ->count(),
            'pendientes' => Cita::query()
                ->whereDate('date', $hoy)
                ->where('status', 'pendiente_anticipo')
                ->count(),
            'canceladas' => Cita::query()
                ->whereDate('date', $hoy)
                ->where('status', 'cancelada')
                ->count(),
        ];

        return view('admin.citas.index', compact('citas', 'empleados', 'stats'));
    }

    public function ticket(Cita $cita)
    {
        $cita->loadMissing(['client.user', 'service', 'employee']);

        $precio = $cita->precioRegistrado();
        $anticipo = $cita->anticipoRegistrado();
        $restante = max(0, $precio - $anticipo);

        return view('admin.citas.ticket', compact('cita', 'precio', 'anticipo', 'restante'));
    }

    public function agenda(Request $request)
    {
        $request->validate([
            'fecha' => 'nullable|date',
            'ocultar_descansos' => 'nullable|boolean',
            'ocultar_sin_citas' => 'nullable|boolean',
        ]);

        $fechaSeleccionada = Carbon::parse($request->input('fecha', today()->toDateString()));
        $ocultarDescansos = $request->boolean('ocultar_descansos');
        $ocultarSinCitas = $request->boolean('ocultar_sin_citas');
        $empleados = Empleado::query()
            ->where('active', 1)
            ->with([
                'schedules',
                'breaks' => fn($query) => $query->whereDate('date', $fechaSeleccionada->toDateString())->orderBy('start_time'),
            ])
            ->orderBy('name')
            ->get();

        $citasDia = Cita::with(['client.user', 'service', 'employee'])
            ->whereDate('date', $fechaSeleccionada->toDateString())
            ->orderBy('start_time')
            ->get();

        $horasBase = collect([8, 20]);
        $horasCitas = $citasDia->flatMap(function (Cita $cita) {
            $inicio = Carbon::parse($cita->getRawOriginal('start_time'));
            $fin = Carbon::parse($cita->getRawOriginal('end_time'));

            return [
                max(6, $inicio->copy()->hour - 1),
                min(22, (int) ceil(($fin->copy()->hour + ($fin->copy()->minute > 0 ? 1 : 0)) + 1)),
            ];
        });

        $horaInicio = $horasBase->merge($horasCitas)->min();
        $horaFin = $horasBase->merge($horasCitas)->max();

        $slots = collect();
        $cursor = $fechaSeleccionada->copy()->setTime($horaInicio, 0);
        $finAgenda = $fechaSeleccionada->copy()->setTime($horaFin, 0);

        while ($cursor->lt($finAgenda)) {
            $slots->push([
                'time' => $cursor->format('H:i'),
                'label' => $cursor->format('g:i A'),
                'is_hour' => $cursor->minute === 0,
            ]);
            $cursor->addMinutes(30);
        }

        $totalMinutos = max(30, $horaFin * 60 - $horaInicio * 60);

        $laneBuilder = function ($label, $appointments, ?Empleado $empleado = null) use ($fechaSeleccionada, $horaInicio, $totalMinutos) {
            $hasScheduleToday = $empleado
                ? $empleado->schedules->where('day_of_week', $fechaSeleccionada->dayOfWeek)->isNotEmpty()
                : false;
            $employeeBreaks = $empleado?->breaks ?? collect();
            $allDayBreak = $employeeBreaks->first(fn($break) => $break->is_all_day);

            return [
                'id' => $empleado?->id ? 'employee-' . $empleado->id : 'unassigned',
                'name' => $label,
                'subtitle' => $empleado
                    ? ($allDayBreak
                        ? ($allDayBreak->reason ?: 'Descansa hoy')
                        : ($hasScheduleToday ? ($empleado->specialty ?: 'Disponible') : 'Descansa hoy'))
                    : 'Requiere asignacion',
                'is_rest_day' => $empleado ? (!$hasScheduleToday || (bool) $allDayBreak) : false,
                'appointments' => $appointments
                    ->sortBy(fn(Cita $cita) => $cita->getRawOriginal('start_time'))
                    ->map(function (Cita $cita) use ($fechaSeleccionada, $horaInicio, $totalMinutos) {
                        $inicio = Carbon::parse($fechaSeleccionada->format('Y-m-d') . ' ' . $cita->getRawOriginal('start_time'));
                        $fin = Carbon::parse($fechaSeleccionada->format('Y-m-d') . ' ' . $cita->getRawOriginal('end_time'));
                        $duracionMinutos = max(30, $inicio->diffInMinutes($fin));
                        $offsetMinutos = max(0, ($inicio->hour * 60 + $inicio->minute) - ($horaInicio * 60));

                        return [
                            'id' => $cita->id,
                            'client' => trim((string) (($cita->client?->user?->name ?? '') . ' ' . ($cita->client?->user?->last_name ?? ''))) ?: 'Cliente',
                            'service' => $cita->service?->name ?? 'Servicio',
                            'time_range' => $inicio->format('g:i A') . ' - ' . $fin->format('g:i A'),
                            'status' => $cita->status,
                            'employee' => $cita->employee?->displayName(),
                            'top_percent' => round(($offsetMinutos / $totalMinutos) * 100, 4),
                            'height_percent' => round(($duracionMinutos / $totalMinutos) * 100, 4),
                            'payment_label' => '$' . number_format($cita->anticipoRegistrado(), 2) . ' anticipo',
                            'details' => [
                                'client' => trim((string) (($cita->client?->user?->name ?? '') . ' ' . ($cita->client?->user?->last_name ?? ''))) ?: 'Cliente',
                                'service' => $cita->service?->name ?? 'Servicio',
                                'time_range' => $inicio->format('g:i A') . ' - ' . $fin->format('g:i A'),
                                'employee' => $cita->employee?->displayName() ?? 'Sin asignar',
                                'status' => ucfirst(str_replace('_', ' ', $cita->status)),
                                'deposit' => '$' . number_format($cita->anticipoRegistrado(), 2),
                                'remaining' => '$' . number_format(max(0, $cita->precioRegistrado() - $cita->anticipoRegistrado()), 2),
                                'notes' => trim((string) ($cita->notes ?? '')) ?: 'Sin comentarios adicionales.',
                            ],
                        ];
                    })
                    ->concat(
                        $employeeBreaks
                            ->reject(fn($break) => $break->is_all_day)
                            ->map(function ($break) use ($fechaSeleccionada, $horaInicio, $totalMinutos) {
                                $inicio = Carbon::parse($fechaSeleccionada->format('Y-m-d') . ' ' . $break->start_time);
                                $fin = Carbon::parse($fechaSeleccionada->format('Y-m-d') . ' ' . $break->end_time);
                                $duracionMinutos = max(30, $inicio->diffInMinutes($fin));
                                $offsetMinutos = max(0, ($inicio->hour * 60 + $inicio->minute) - ($horaInicio * 60));

                                return [
                                    'id' => 'break-' . $break->id,
                                    'client' => '',
                                    'service' => $break->reason ?: 'Descanso bloqueado',
                                    'time_range' => $inicio->format('g:i A') . ' - ' . $fin->format('g:i A'),
                                    'status' => 'break',
                                    'employee' => $break->empleado?->name,
                                    'top_percent' => round(($offsetMinutos / $totalMinutos) * 100, 4),
                                    'height_percent' => round(($duracionMinutos / $totalMinutos) * 100, 4),
                                    'payment_label' => '',
                                    'details' => [
                                        'client' => 'Bloque interno',
                                        'service' => $break->reason ?: 'Descanso bloqueado',
                                        'time_range' => $inicio->format('g:i A') . ' - ' . $fin->format('g:i A'),
                                        'employee' => 'No disponible',
                                        'status' => 'Descanso',
                                        'deposit' => '-',
                                        'remaining' => '-',
                                        'notes' => trim((string) ($break->reason ?? '')) ?: 'Bloque de descanso manual del empleado.',
                                    ],
                                ];
                            })
                    )
                    ->sortBy('top_percent')
                    ->values(),
            ];
        };

        $lanes = $empleados->map(function (Empleado $empleado) use ($citasDia, $laneBuilder) {
            return $laneBuilder(
                $empleado->name,
                $citasDia->where('employee_id', $empleado->id)->values(),
                $empleado
            );
        })->values();

        $sinAsignar = $citasDia->whereNull('employee_id')->values();
        if ($sinAsignar->isNotEmpty()) {
            $lanes->push($laneBuilder('Sin asignar', $sinAsignar));
        }

        $lanes = $lanes->filter(function (array $lane) use ($ocultarDescansos, $ocultarSinCitas) {
            if ($ocultarDescansos && !empty($lane['is_rest_day'])) {
                return false;
            }

            if ($ocultarSinCitas && count($lane['appointments']) === 0) {
                return false;
            }

            return true;
        })->values();

        $stats = [
            'total' => $citasDia->count(),
            'confirmadas' => $citasDia->where('status', 'confirmada')->count(),
            'completadas' => $citasDia->where('status', 'completada')->count(),
            'ingresos_estimados' => round(
                $citasDia
                    ->whereIn('status', ['confirmada', 'completada'])
                    ->sum(fn(Cita $cita) => $cita->precioRegistrado()),
                2
            ),
        ];

        return view('admin.citas.agenda', [
            'fechaSeleccionada' => $fechaSeleccionada,
            'lanes' => $lanes,
            'slots' => $slots,
            'horaInicio' => $horaInicio,
            'horaFin' => $horaFin,
            'stats' => $stats,
            'ocultarDescansos' => $ocultarDescansos,
            'ocultarSinCitas' => $ocultarSinCitas,
        ]);
    }

    public function create()
    {
        $servicios = Servicio::where('active', 1)
            ->whereHas('empleados', function ($q) {
                $q->where('active', 1);
            })
            ->with(['promociones' => function ($query) {
                $query->where('published', true)
                    ->where('start_date', '<=', now()->toDateString())
                    ->where('end_date', '>=', now()->toDateString());
            }])
            ->get();

        $usuarios = Usuario::where('role_id', 2)
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.citas.create', compact('servicios', 'usuarios'));
    }

    public function store(AppointmentDateTimeRequest $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:services,id',
            'anticipo_recibido' => 'required|accepted',
            'anticipo_monto' => 'required|numeric|min:0.01',
        ]);

        $cliente = Cliente::where('user_id', $request->usuario_id)->first();

        if (!$cliente) {
            return back()->with('error', 'El usuario no es cliente')->withInput();
        }

        $servicio = Servicio::with(['empleados' => function ($q) {
            $q->where('active', 1)->with(['schedules', 'breaks']);
        }])->findOrFail($request->servicio_id);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duration_minutes);

        $precioServicio = $servicio->precioConDescuento();
        $anticipo = $request->anticipo_monto;

        $totalPagado = $anticipo;

        $status = $anticipo >= $precioServicio
            ? 'completada'
            : 'confirmada';

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
                return back()->with('error', 'No hay empleados disponibles')->withInput();
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

                'notes' => 'Anticipo recibido por administrador'

            ]);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Error al crear cita')->withInput();
        }

        return redirect()
            ->route('admin.citas.index')
            ->with('success', 'Cita creada correctamente');
    }

    public function reporteDiario(Request $request)
    {
        $request->validate([
            'fecha' => 'nullable|date',
        ]);

        $fecha = $request->input('fecha', today()->toDateString());

        $citas = Cita::with(['client.user', 'service', 'employee'])
            ->whereDate('date', $fecha)
            ->orderBy('start_time')
            ->get();

        $filename = 'reporte_citas_' . $fecha . '.csv';
        $handle = fopen('php://temp', 'r+');

        // BOM para que Excel abra UTF-8 correctamente
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Reporte diario de citas']);
        fputcsv($handle, ['Fecha', $fecha]);
        fputcsv($handle, ['Total de citas', $citas->count()]);
        fputcsv($handle, []);
        fputcsv($handle, [
            'Hora',
            'Cliente',
            'Email',
            'Servicio',
            'Estado',
            'Empleado',
            'Notas',
        ]);

        foreach ($citas as $cita) {
            fputcsv($handle, [
                optional($cita->start_time)->format('H:i'),
                trim((string) optional(optional($cita->client)->user)->name),
                trim((string) optional(optional($cita->client)->user)->email),
                trim((string) optional($cita->service)->name),
                (string) $cita->status,
                trim((string) optional($cita->employee)->name),
                trim((string) ($cita->notes ?? '')),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function reporteDiarioPdf(Request $request)
    {
        $request->validate([
            'fecha' => 'nullable|date',
        ]);

        $fecha = $request->input('fecha', today()->toDateString());

        $citas = Cita::with(['client.user', 'service', 'employee'])
            ->whereDate('date', $fecha)
            ->orderBy('start_time')
            ->get();

        $statusResumen = $citas
            ->groupBy('status')
            ->map(fn($grupo) => $grupo->count())
            ->sortKeys();

        $pdf = Pdf::loadView('admin.citas.reporte-diario-pdf', [
            'fecha' => Carbon::parse($fecha),
            'citas' => $citas,
            'totalCitas' => $citas->count(),
            'statusResumen' => $statusResumen,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('reporte_diario_citas_' . Carbon::parse($fecha)->format('Y-m-d') . '.pdf');
    }

    public function reporteMensual(Request $request)
    {
        $request->validate([
            'mes' => 'nullable|date_format:Y-m',
        ]);

        $data = $this->buildMonthlyReportData($request->input('mes', now()->format('Y-m')));

        return view('admin.citas.reporte-mensual', $data);
    }

    public function reporteMensualPdf(Request $request)
    {
        $request->validate([
            'mes' => 'nullable|date_format:Y-m',
        ]);

        $data = $this->buildMonthlyReportData($request->input('mes', now()->format('Y-m')));
        $nombreMes = $data['inicio']->format('Y-m');

        $pdf = Pdf::loadView('admin.citas.reporte-mensual-pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download("reporte_mensual_citas_{$nombreMes}.pdf");
    }

    private function buildMonthlyReportData(string $mesSeleccionado): array
    {
        [$year, $month] = array_map('intval', explode('-', $mesSeleccionado));

        $inicio = Carbon::create($year, $month, 1)->startOfMonth();
        $fin = $inicio->copy()->endOfMonth();

        $conteoPorDia = Cita::query()
            ->selectRaw('DATE(date) as fecha, COUNT(*) as total')
            ->whereBetween('date', [$inicio->toDateString(), $fin->toDateString()])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('fecha')
            ->pluck('total', 'fecha');

        $conteoPorDiaEstatus = Cita::query()
            ->selectRaw('DATE(date) as fecha, status, COUNT(*) as total')
            ->whereBetween('date', [$inicio->toDateString(), $fin->toDateString()])
            ->groupBy(DB::raw('DATE(date)'), 'status')
            ->get()
            ->groupBy('fecha');

        $ingresosPorDia = Cita::query()
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->selectRaw('DATE(appointments.date) as fecha, SUM(services.price) as total')
            ->whereBetween('appointments.date', [$inicio->toDateString(), $fin->toDateString()])
            ->whereIn('appointments.status', ['confirmada', 'completada'])
            ->groupBy(DB::raw('DATE(appointments.date)'))
            ->pluck('total', 'fecha');

        $labels = [];
        $valores = [];
        $valoresCanceladas = [];
        $valoresConfirmadas = [];
        $valoresCompletadas = [];
        $valoresNoAsistio = [];
        $valoresPendientes = [];
        $detalleDiario = [];
        $acumulado = 0;
        $ingresosMes = 0.0;

        for ($dia = 1; $dia <= $inicio->daysInMonth; $dia++) {
            $fecha = $inicio->copy()->day($dia)->toDateString();
            $totalDia = (int) ($conteoPorDia[$fecha] ?? 0);
            $registrosDia = $conteoPorDiaEstatus->get($fecha, collect());
            $canceladasDia = (int) ($registrosDia->firstWhere('status', 'cancelada')?->total ?? 0);
            $confirmadasDia = (int) ($registrosDia->firstWhere('status', 'confirmada')?->total ?? 0);
            $completadasDia = (int) ($registrosDia->firstWhere('status', 'completada')?->total ?? 0);
            $noAsistioDia = (int) ($registrosDia->firstWhere('status', 'no_asistio')?->total ?? 0);
            $pendientesDia = (int) ($registrosDia->firstWhere('status', 'pendiente_anticipo')?->total ?? 0);
            $ingresoDia = (float) ($ingresosPorDia[$fecha] ?? 0);
            $acumulado += $totalDia;
            $ingresosMes += $ingresoDia;

            $labels[] = str_pad((string) $dia, 2, '0', STR_PAD_LEFT);
            $valores[] = $totalDia;
            $valoresCanceladas[] = $canceladasDia;
            $valoresConfirmadas[] = $confirmadasDia;
            $valoresCompletadas[] = $completadasDia;
            $valoresNoAsistio[] = $noAsistioDia;
            $valoresPendientes[] = $pendientesDia;
            $detalleDiario[] = [
                'dia' => str_pad((string) $dia, 2, '0', STR_PAD_LEFT),
                'citas' => $totalDia,
                'canceladas' => $canceladasDia,
                'confirmadas' => $confirmadasDia,
                'completadas' => $completadasDia,
                'pendientes' => $pendientesDia,
                'no_asistio' => $noAsistioDia,
                'ingresos' => round($ingresoDia, 2),
                'totales' => $acumulado,
            ];
        }

        $statusResumen = Cita::query()
            ->selectRaw('status, COUNT(*) as total')
            ->whereBetween('date', [$inicio->toDateString(), $fin->toDateString()])
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalCitas = array_sum($valores);
        $promedioDiario = $inicio->daysInMonth > 0
            ? round($totalCitas / $inicio->daysInMonth, 2)
            : 0;
        $promedioIngresosDiario = $inicio->daysInMonth > 0
            ? round($ingresosMes / $inicio->daysInMonth, 2)
            : 0;

        $maxCitas = max($valores ?: [0]);
        $indicePico = array_search($maxCitas, $valores, true);
        $diaPico = $maxCitas > 0 && $indicePico !== false ? $labels[$indicePico] : null;

        return [
            'mesSeleccionado' => $mesSeleccionado,
            'inicio' => $inicio,
            'labels' => $labels,
            'valores' => $valores,
            'valoresCanceladas' => $valoresCanceladas,
            'valoresConfirmadas' => $valoresConfirmadas,
            'valoresCompletadas' => $valoresCompletadas,
            'valoresNoAsistio' => $valoresNoAsistio,
            'valoresPendientes' => $valoresPendientes,
            'maxCitas' => $maxCitas,
            'totalCitas' => $totalCitas,
            'totalCanceladas' => (int) ($statusResumen['cancelada'] ?? 0),
            'totalConfirmadas' => (int) ($statusResumen['confirmada'] ?? 0),
            'totalCompletadas' => (int) ($statusResumen['completada'] ?? 0),
            'totalPendientes' => (int) ($statusResumen['pendiente_anticipo'] ?? 0),
            'totalNoAsistio' => (int) ($statusResumen['no_asistio'] ?? 0),
            'promedioDiario' => $promedioDiario,
            'ingresosMes' => round($ingresosMes, 2),
            'promedioIngresosDiario' => $promedioIngresosDiario,
            'diaPico' => $diaPico,
            'statusResumen' => $statusResumen,
            'detalleDiario' => $detalleDiario,
        ];
    }


    public function confirmar(Request $request, Cita $cita)
    {
        if ($cita->status !== 'pendiente_anticipo') {
            return back()->with('error', 'Solo se pueden confirmar citas pendientes de anticipo.');
        }

        if (!$cita->receipt) {
            return back()->with('error', 'Se necesita un comprobante para confirmar el anticipo.');
        }

        $request->validate([
            'anticipo_monto' => 'required|numeric|min:0'
        ]);

        $anticipo = (float) $request->anticipo_monto;

        $cita->update([
            'deposit_amount' => $anticipo,
            'total_paid' => $anticipo,
            'status' => 'confirmada',
            'notes' => 'Anticipo validado por administrador'
        ]);
        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'confirmada',
            'user_id' => auth()->id(),
            'change_date' => now()
        ]);

        $cita->loadMissing(['client.user', 'service', 'employee']);

        $clienteUsuario = $cita->client?->user;

        if ($clienteUsuario) {
            $clienteUsuario->notify(
                new CitaClienteNotification(
                    $cita,
                    CitaClienteNotification::CONFIRMADA_CON_EMPLEADO
                )
            );
        }

        return back()->with('success', 'Cita confirmada correctamente');
    }

    public function actualizarAnticipo(Request $request, Cita $cita)
    {
        if (in_array($cita->status, ['cancelada'], true)) {
            return back()->with('error', 'No se puede editar el anticipo de una cita cancelada.');
        }

        // Solo editar una vez el anticipo para evitar confusiones en el historial de notas
        if (str_contains($cita->notes ?? '', 'Anticipo actualizado')) {
            return back()->with('error', 'El anticipo ya fue editado una vez.');
        }

        // No permitir editar el anticipo si la cita ya inició para evitar confusiones en el historial de notas y estado de la cita
        if ($this->citaYaInicio($cita)) {
            return back()->with('error', 'No se puede editar el anticipo porque la cita ya inició.');
        }

        $request->validate([
            'anticipo_monto' => 'required|numeric|min:0',
        ]);

        $anticipo = (float) $request->anticipo_monto;
        $pagoFinal = (float) ($cita->final_payment ?? 0);
        $totalPagado = $anticipo + $pagoFinal;

        $notaAnterior = trim((string) ($cita->notes ?? ''));
        $notaEdicion = 'Anticipo actualizado por administrador a $' . number_format($anticipo, 2) . '.';
        $notaFinal = $notaAnterior === '' ? $notaEdicion : $notaAnterior . ' | ' . $notaEdicion;

        $cita->update([
            'deposit_amount' => $anticipo,
            'total_paid' => $totalPagado,
            'notes' => $notaFinal,
        ]);

        return back()->with('success', 'Anticipo actualizado correctamente.');
    }


    public function cancelar(Request $request, Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se pueden cancelar citas confirmadas.');
        }

        $request->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        $motivoCancelacion = $request->observaciones
            ?? 'Cancelada por el administrador';

        $cita->update([
            'status' => 'cancelada',
            'notes' => $motivoCancelacion,
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'cancelada',
            'user_id' => auth()->id(),
            'change_date' => now()
        ]);

        return back()->with('success', 'Cita cancelada correctamente');
    }

    public function reagendar(AppointmentDateTimeRequest $request, Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se pueden reagendar citas confirmadas.');
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
        $notaFinal = 'Reagendada por administrador.';
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

    public function completar(Request $request, Cita $cita)
    {
        // Validar estado de la cita
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se pueden completar citas confirmadas.');
        }

        //  Validar que ya terminó la cita
        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $finCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('end_time'));

        if (now()->lt($finCita)) {
            return back()->with('error', 'La cita aún no ha finalizado.');
        }

        // CALCULAR AUTOMATICAMENTE EL PAGO FINAL
        $precio = $cita->precioRegistrado();
        $anticipo = $cita->anticipoRegistrado();
        $pagoFinal = max(0, $precio - $anticipo);

        $totalPagado = $anticipo + $pagoFinal;

        // Notas
        $notaBase = trim((string) ($cita->notes ?? ''));
        $notaPago = 'Cita completada con pago final de $' . number_format($pagoFinal, 2);
        $notaFinal = $notaBase === '' ? $notaPago : $notaBase . ' | ' . $notaPago;

        // Actualizar
        $cita->update([
            'final_payment' => $pagoFinal,
            'total_paid' => $totalPagado,
            'status' => 'completada',
            'notes' => $notaFinal,
        ]);

        // 📌 Historial
        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'completada',
            'user_id' => auth()->id(),
            'change_date' => now(),
        ]);

        return back()->with('success', 'Cita marcada como completada.');
    }


    public function marcarNoAsistio(Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se puede marcar no asistio en citas confirmadas.');
        }

        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $inicioCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('start_time'));
        if (Carbon::now()->lessThan($inicioCita)) {
            return back()->with('error', 'Solo se puede marcar no asistio a partir de la hora de inicio.');
        }

        $notaBase = trim((string) ($cita->notes ?? ''));
        $notaFinal = $notaBase === ''
            ? 'Marcada como no asistio por administrador.'
            : $notaBase . ' | Marcada como no asistio por administrador.';

        $cita->update([
            'status' => 'no_asistio',
            'notes' => $notaFinal,
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'no_asistio',
            'user_id' => auth()->id(),
            'change_date' => now(),
        ]);

        return back()->with('success', 'Cita marcada como no asistio.');
    }

    public function rechazarPago(Cita $cita)
    {
        if ($cita->status !== 'pendiente_anticipo') {
            return back()->with('error', 'Solo se puede rechazar anticipo en citas pendientes.');
        }

        if (!$cita->receipt) {
            return back()->with('error', 'No hay comprobante para rechazar.');
        }

        $intentos = ($cita->payment_attempts ?? 0) + 1;

        if ($cita->receipt && Storage::exists('public/' . $cita->receipt)) {
            Storage::delete('public/' . $cita->receipt);
        }

        if ($intentos >= 2) {
            $cita->update([
                'status' => 'cancelada',
                'notes' => 'Cita cancelada por 2 intentos fallidos de anticipo.',
                'receipt' => null,
                'payment_deadline' => null,
                'payment_attempts' => $intentos,
            ]);

            CitaEstado::create([
                'appointment_id' => $cita->id,
                'status' => 'cancelada',
                'user_id' => auth()->id(),
                'change_date' => now(),
            ]);

            $email = $cita->client?->user?->email;
            if ($email) {
                Mail::to($email)->send(new AnticipoCanceladoPorRechazosMail($cita));
            }

            return back()->with('error', 'La cita fue cancelada por 2 intentos fallidos.');
        }

        $cita->update([
            'status' => 'pendiente_anticipo',
            'notes' => 'Anticipo rechazado por administrador. Tiene 15 minutos para reenviar comprobante (ultimo intento).',
            'receipt' => null,
            'payment_deadline' => now()->addMinutes(15),
            'payment_attempts' => $intentos,
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'anticipo_rechazado',
            'user_id' => auth()->id(),
            'change_date' => now(),
        ]);

        $email = $cita->client?->user?->email;
        if ($email) {
            Mail::to($email)->send(new PagoRechazadoMail($cita));
        }

        return back()->with('success', 'Anticipo rechazado. Cliente notificado.');
    }

    public function asignarEmpleado(Request $request, Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se puede asignar empleado a citas confirmadas');
        }

        if ($cita->employee_id) {
            return back()->with('error', 'Esta cita ya tiene un empleado asignado');
        }

        $request->validate([
            'empleado_id' => 'required|exists:employees,id',
        ]);

        $cita->update([
            'employee_id' => $request->empleado_id,
        ]);

        $cita->loadMissing(['client.user', 'service', 'employee']);
        $clienteUsuario = $cita->client?->user;
        if ($clienteUsuario) {
            $clienteUsuario->notify(
                new CitaClienteNotification($cita, CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
            );
        }

        return back()->with('success', 'Empleado asignado correctamente');
    }
}
