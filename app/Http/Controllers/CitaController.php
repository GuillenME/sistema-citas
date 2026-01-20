<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Cliente;
use Carbon\Carbon;

class CitaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR CITAS DEL CLIENTE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cliente = Cliente::where('usuario_id', auth()->id())->first();

        if (!$cliente) {
            abort(403, 'Cliente no encontrado');
        }

        $citas = Cita::where('cliente_id', $cliente->id)
            ->with('servicio')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        return view('cliente.citas.index', compact('citas'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO CREAR CITA (CLIENTE)
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $servicios = Servicio::where('activo', 1)->get();
        return view('cliente.citas.create', compact('servicios'));
    }

    /*
    |--------------------------------------------------------------------------
    | 🔹 BLOQUES DISPONIBLES (CLIENTE + RECEPCIONISTA)
    |--------------------------------------------------------------------------
    */
    public function bloquesDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicio_id' => 'required|exists:servicios,id',
        ]);

        $fecha = Carbon::parse($request->fecha);

        // ❌ Domingo cerrado
        if ($fecha->isSunday()) {
            return response()->json([]);
        }

        $servicio  = Servicio::findOrFail($request->servicio_id);
        $duracion = $servicio->duracion_minutos;

        /*
        |--------------------------------------------------------------------------
        | HORARIOS DEL NEGOCIO
        | L–V: 08:00–15:00 y 16:00–20:00
        | S  : 08:00–15:00
        |--------------------------------------------------------------------------
        */
        $rangos = [];

        if ($fecha->isWeekday()) {
            $rangos = [
                [480, 900],   // 08:00 - 15:00
                [960, 1200],  // 16:00 - 20:00
            ];
        } else {
            $rangos = [
                [480, 900],   // 08:00 - 15:00
            ];
        }

        // ⛔ citas ya tomadas
        $citas = Cita::whereDate('fecha', $fecha)
            ->whereIn('estado', ['confirmada', 'pendiente_anticipo'])
            ->get(['hora_inicio', 'hora_fin']);

        $ocupados = [];

        foreach ($citas as $cita) {
            $ocupados[] = [
                Carbon::parse($cita->hora_inicio)->hour * 60 + Carbon::parse($cita->hora_inicio)->minute,
                Carbon::parse($cita->hora_fin)->hour * 60 + Carbon::parse($cita->hora_fin)->minute,
            ];
        }

        $bloques = [];

        foreach ($rangos as [$inicioR, $finR]) {
            for ($min = $inicioR; $min + $duracion <= $finR; $min += 15) {

                $finBloque = $min + $duracion;
                $libre = true;

                foreach ($ocupados as [$ini, $fin]) {
                    if ($min < $fin && $finBloque > $ini) {
                        $libre = false;
                        break;
                    }
                }

                if ($libre) {
                    $bloques[] = [
                        'inicio' => sprintf('%02d:%02d', intdiv($min, 60), $min % 60),
                        'fin'    => sprintf('%02d:%02d', intdiv($finBloque, 60), $finBloque % 60),
                    ];
                }
            }
        }

        return response()->json($bloques);
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR CITA (CLIENTE)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'servicio_id'  => 'required|exists:servicios,id',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora_inicio'  => 'required|date_format:H:i',
        ]);

        $cliente = Cliente::where('usuario_id', auth()->id())->first();

        if (!$cliente) {
            return back()->withErrors([
                'cliente' => 'No existe un perfil de cliente para este usuario'
            ]);
        }

        $fecha = Carbon::parse($request->fecha);

        if ($fecha->isSunday()) {
            return back()->withErrors([
                'fecha' => 'No se atienden citas los domingos'
            ]);
        }

        $servicio = Servicio::findOrFail($request->servicio_id);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        // ⛔ evitar choques
        $cruce = Cita::whereDate('fecha', $fecha)
            ->whereIn('estado', ['confirmada', 'pendiente_anticipo'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin->format('H:i'))
                  ->where('hora_fin', '>', $horaInicio->format('H:i'));
            })
            ->exists();

        if ($cruce) {
            return back()->withErrors([
                'hora_inicio' => 'Horario no disponible'
            ]);
        }

        Cita::create([
            'cliente_id'    => $cliente->id,
            'servicio_id'   => $servicio->id,
            'personal_id'   => null,
            'fecha'         => $fecha->toDateString(),
            'hora_inicio'   => $horaInicio->format('H:i'),
            'hora_fin'      => $horaFin->format('H:i'),
            'estado'        => 'pendiente_anticipo',
            'observaciones' => null,
        ]);

        return redirect()
            ->route('cliente.citas.index')
            ->with('success', 'Cita creada correctamente. Pendiente de anticipo.');
    }
}
