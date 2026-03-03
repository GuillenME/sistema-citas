<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Cliente;
use App\Models\RecepcionistaReminder;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
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
            ->whereDate('date', '>=', $today)
            ->where(function ($query) use ($today, $horaActual) {
                $query->whereDate('date', '>', $today)
                    ->orWhere(function ($q) use ($today, $horaActual) {
                        $q->whereDate('date', $today)
                            ->where('start_time', '>=', $horaActual);
                    });
            })
            ->orderBy('date')
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

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:services,id',
            'fecha' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $fecha = Carbon::parse($value);
                    if ($fecha->isSunday()) {
                        $fail('Los domingos no se atiende. Por favor selecciona otro dÃ­a.');
                    }
                },
            ],
            'hora_inicio' => 'required|date_format:H:i',
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

        $servicio = Servicio::findOrFail($request->servicio_id);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duration_minutes);

        if ($this->isInsideLunchBreak($horaInicio->format('H:i'), $horaFin->format('H:i'))) {
            return back()->with('error', 'Ese horario corresponde a la hora de comida. Elige otro bloque.');
        }

        Cita::create([
            'client_id' => $cliente->id,
            'service_id' => $servicio->id,
            'date' => $request->fecha,
            'start_time' => $horaInicio->format('H:i'),
            'end_time' => $horaFin->format('H:i'),
            'status' => 'confirmada',
            'notes' => 'Anticipo recibido en recepcion: $' . number_format($request->anticipo_monto, 2),
        ]);

        return redirect()
            ->route('recepcionista.dashboard')
            ->with('success', 'Cita agendada correctamente');
    }

    public function index()
    {
        $inicioSemana = now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $finSemana = now()->endOfWeek(Carbon::SUNDAY)->toDateString();

        $citas = Cita::with(['client.user', 'service'])
            ->withCount([
                'estados as reagendas_count' => function ($query) {
                    $query->where('status', 'reagendada');
                },
            ])
            ->whereBetween('date', [$inicioSemana, $finSemana])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();

        return view('recepcionista.citas.index', compact('citas'));
    }

    public function reagendar(Request $request, Cita $cita)
    {
        if (!in_array($cita->status, ['confirmada', 'pendiente_anticipo'], true)) {
            return back()->with('error', 'Solo se pueden reagendar citas confirmadas o pendientes de anticipo.');
        }

        $reagendas = $cita->estados()->where('status', 'reagendada')->count();
        if ($reagendas >= 2) {
            return back()->with('error', 'Esta cita ya alcanzÃ³ el mÃ¡ximo de 2 reagendas.');
        }

        $request->validate([
            'fecha' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $fecha = Carbon::parse($value);
                    if ($fecha->isSunday()) {
                        $fail('Los domingos no se atiende. Por favor selecciona otro dÃ­a.');
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
        $notaFinal = 'Reagendada por recepcionista.';
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

    private function isInsideLunchBreak(string $horaInicio, string $horaFin): bool
    {
        $comidaInicio = (string) config('citas.horarios.comida_inicio', '15:00');
        $comidaFin = (string) config('citas.horarios.comida_fin', '16:00');

        if (!preg_match('/^\d{2}:\d{2}$/', $comidaInicio) || !preg_match('/^\d{2}:\d{2}$/', $comidaFin)) {
            return false;
        }

        if ($comidaInicio >= $comidaFin) {
            return false;
        }

        return $horaInicio < $comidaFin && $horaFin > $comidaInicio;
    }
}
