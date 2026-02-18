<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Empleado;
use App\Notifications\CitaClienteNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCitaController extends Controller
{
    public function index()
    {
        $hoy = today();

        $citas = Cita::with(['client', 'service', 'employee'])
            ->withCount([
                'estados as reagendas_count' => function ($query) {
                    $query->where('status', 'reagendada');
                },
            ])
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

        $labels = [];
        $valores = [];
        $detalleDiario = [];
        $acumulado = 0;
        for ($dia = 1; $dia <= $inicio->daysInMonth; $dia++) {
            $fecha = $inicio->copy()->day($dia)->toDateString();
            $totalDia = (int) ($conteoPorDia[$fecha] ?? 0);
            $registrosDia = $conteoPorDiaEstatus->get($fecha, collect());
            $canceladasDia = (int) ($registrosDia->firstWhere('status', 'cancelada')?->total ?? 0);
            $confirmadasDia = (int) ($registrosDia->firstWhere('status', 'confirmada')?->total ?? 0);
            $completadasDia = (int) ($registrosDia->firstWhere('status', 'completada')?->total ?? 0);
            $noAsistioDia = (int) ($registrosDia->firstWhere('status', 'no_asistio')?->total ?? 0);
            $acumulado += $totalDia;

            $labels[] = str_pad((string) $dia, 2, '0', STR_PAD_LEFT);
            $valores[] = $totalDia;
            $detalleDiario[] = [
                'dia' => str_pad((string) $dia, 2, '0', STR_PAD_LEFT),
                'citas' => $totalDia,
                'canceladas' => $canceladasDia,
                'confirmadas' => $confirmadasDia,
                'completadas' => $completadasDia,
                'no_asistio' => $noAsistioDia,
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

        $maxCitas = max($valores ?: [0]);
        $indicePico = array_search($maxCitas, $valores, true);
        $diaPico = $maxCitas > 0 && $indicePico !== false ? $labels[$indicePico] : null;

        return [
            'mesSeleccionado' => $mesSeleccionado,
            'inicio' => $inicio,
            'labels' => $labels,
            'valores' => $valores,
            'maxCitas' => $maxCitas,
            'totalCitas' => $totalCitas,
            'totalCanceladas' => (int) ($statusResumen['cancelada'] ?? 0),
            'totalConfirmadas' => (int) ($statusResumen['confirmada'] ?? 0),
            'totalCompletadas' => (int) ($statusResumen['completada'] ?? 0),
            'totalNoAsistio' => (int) ($statusResumen['no_asistio'] ?? 0),
            'promedioDiario' => $promedioDiario,
            'diaPico' => $diaPico,
            'statusResumen' => $statusResumen,
            'detalleDiario' => $detalleDiario,
        ];
    }


    public function confirmar(Cita $cita)
    {
        $cita->update([
            'status' => 'confirmada',
            'notes' => 'Cita confirmada por el administrador',
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'confirmada',
            'user_id' => auth()->id(),
            'change_date' => now()
        ]);

        return back()->with('success', 'Cita confirmada correctamente');
    }


    public function cancelar(Request $request, Cita $cita)
    {
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

        $cita->loadMissing(['client.user', 'service', 'employee']);
        $clienteUsuario = $cita->client?->user;
        if ($clienteUsuario) {
            $clienteUsuario->notify(
                new CitaClienteNotification(
                    $cita,
                    CitaClienteNotification::CANCELADA_POR_ADMIN,
                    $motivoCancelacion
                )
            );
        }

        return back()->with('success', 'Cita cancelada correctamente');
    }

    public function reagendar(Request $request, Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se pueden reagendar citas confirmadas.');
        }

        $reagendas = $cita->estados()->where('status', 'reagendada')->count();
        if ($reagendas >= 2) {
            return back()->with('error', 'Esta cita ya alcanzó el máximo de 2 reagendas.');
        }

        $request->validate([
            'fecha' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->isSunday()) {
                        $fail('No se puede reagendar en domingo.');
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

        $citaSolapada = Cita::query()
            ->whereDate('date', $request->fecha)
            ->whereIn('status', ['confirmada', 'pendiente_anticipo'])
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
        $notaFinal = 'Reagendada por administrador.';
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

        return back()->with('success', 'Cita reagendada correctamente.');
    }

    public function completar(Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se pueden completar citas confirmadas.');
        }

        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $finCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('end_time'));
        if (Carbon::now()->lessThan($finCita)) {
            return back()->with('error', 'La cita solo puede marcarse como completada después de la hora de fin.');
        }

        $notaBase = trim((string) ($cita->notes ?? ''));
        $notaFinal = $notaBase === ''
            ? 'Marcada como completada por administrador.'
            : $notaBase . ' | Marcada como completada por administrador.';

        $cita->update([
            'status' => 'completada',
            'notes' => $notaFinal,
        ]);

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
            return back()->with('error', 'Solo se puede marcar no asistió en citas confirmadas.');
        }

        $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
        $inicioCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('start_time'));
        if (Carbon::now()->lessThan($inicioCita)) {
            return back()->with('error', 'Solo se puede marcar no asistió a partir de la hora de inicio.');
        }

        $notaBase = trim((string) ($cita->notes ?? ''));
        $notaFinal = $notaBase === ''
            ? 'Marcada como no asistió por administrador.'
            : $notaBase . ' | Marcada como no asistió por administrador.';

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

        return back()->with('success', 'Cita marcada como no asistió.');
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
