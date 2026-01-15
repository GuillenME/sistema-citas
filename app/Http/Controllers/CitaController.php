<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Cliente;

class CitaController extends Controller
{
    public function index()
    {
        // obtener cliente real
        $cliente = Cliente::where('usuario_id', auth()->id())->first();

        if (!$cliente) {
            abort(403, 'Cliente no encontrado');
        }

        $citas = Cita::where('cliente_id', $cliente->id)
            ->with('servicio')
            ->get();

        return view('cliente.citas.index', compact('citas'));
    }

    public function create()
    {
        $servicios = Servicio::where('activo', 1)->get();
        return view('cliente.citas.create', compact('servicios'));
    }

    public function bloquesDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicio_id' => 'required|exists:servicios,id',
        ]);

        $fecha = $request->fecha;
        $dia = date('w', strtotime($fecha)); // 0=Domingo

        if ($dia == 0) {
            return response()->json([]);
        }

        $servicio = Servicio::findOrFail($request->servicio_id);
        $duracion = $servicio->duracion_minutos;

        $rangos = ($dia >= 1 && $dia <= 5)
            ? [[480, 900], [960, 1200]]
            : [[480, 900]];

        $citas = Cita::where('fecha', $fecha)
            ->whereIn('estado', ['confirmada', 'pendiente_anticipo'])
            ->get(['hora_inicio', 'hora_fin']);

        $ocupados = [];
        foreach ($citas as $cita) {
            $ocupados[] = [
                strtotime($cita->hora_inicio) / 60,
                strtotime($cita->hora_fin) / 60
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
                        'fin' => sprintf('%02d:%02d', intdiv($finBloque, 60), $finBloque % 60),
                    ];
                }
            }
        }

        return response()->json($bloques);
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
        ]);

        // obtener cliente real
        $cliente = Cliente::where('usuario_id', auth()->id())->first();

        if (!$cliente) {
            return back()->withErrors([
                'cliente' => 'No existe un perfil de cliente para este usuario'
            ]);
        }

        if (date('w', strtotime($request->fecha)) == 0) {
            return back()->withErrors([
                'fecha' => 'No se atienden citas los domingos'
            ]);
        }

        $servicio = Servicio::findOrFail($request->servicio_id);

        $horaFin = date(
            'H:i',
            strtotime($request->hora_inicio) + ($servicio->duracion_minutos * 60)
        );

        $cruce = Cita::where('fecha', $request->fecha)
            ->where(function ($q) use ($request, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $request->hora_inicio);
            })
            ->exists();

        if ($cruce) {
            return back()->withErrors([
                'hora_inicio' => 'Horario no disponible'
            ]);
        }

        Cita::create([
            'cliente_id' => $cliente->id, // ✅ CORRECTO
            'servicio_id' => $servicio->id,
            'personal_id' => null,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'estado' => 'pendiente_anticipo',
            'observaciones' => null,
        ]);

        return redirect()
            ->route('cliente.citas.index')
            ->with('success', 'Cita creada correctamente. Pendiente de anticipo.');
    }
}
