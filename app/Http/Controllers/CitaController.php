<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::where('cliente_id', auth()->id())
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

        // ❌ Domingo no laboral
        if ($dia == 0) {
            return response()->json([]);
        }

        $servicio = Servicio::findOrFail($request->servicio_id);
        $duracion = $servicio->duracion_minutos;

        $rangos = [];

        if ($dia >= 1 && $dia <= 5) {
            // Lunes a Viernes
            $rangos = [
                [8 * 60, 15 * 60],
                [16 * 60, 20 * 60],
            ];
        }

        if ($dia == 6) {
            // Sábado
            $rangos = [
                [8 * 60, 15 * 60],
            ];
        }

        $citas = Cita::where('fecha', $fecha)
            ->get(['hora_inicio', 'hora_fin']);

        $ocupados = [];

        foreach ($citas as $cita) {
            $ini = strtotime($cita->hora_inicio) / 60;
            $fin = strtotime($cita->hora_fin) / 60;
            $ocupados[] = [$ini, $fin];
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

        // ❌ Bloqueo definitivo de domingos
        if (date('w', strtotime($request->fecha)) == 0) {
            return back()->withErrors([
                'fecha' => 'No se atienden citas los domingos'
            ])->withInput();
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
            ])->withInput();
        }

        Cita::create([
            'cliente_id' => auth()->id(),
            'servicio_id' => $servicio->id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'estado' => 'pendiente_anticipo',
        ]);

        return redirect()->route('cliente.citas.index')
            ->with('success', 'Cita creada, pendiente de anticipo');
    }
}
