<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function create()
    {
        $servicios = Servicio::all();

        $usuarios = Usuario::where('rol_id', 2)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('recepcionista.citas.create', compact('servicios', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'anticipo_monto' => 'nullable|numeric|min:0',
        ]);

        $cliente = Cliente::where('usuario_id', $request->usuario_id)->first();

        if (!$cliente) {
            return back()->with('error', 'El usuario no es cliente');
        }

        $servicio = Servicio::findOrFail($request->servicio_id);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        $anticipo = $request->filled('anticipo_monto');

        Cita::create([
            'cliente_id' => $cliente->id,
            'servicio_id' => $servicio->id,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
            'estado' => $anticipo ? 'confirmada' : 'pendiente_anticipo',
            'observaciones' => $anticipo
                ? 'Anticipo recibido en recepción: $' . number_format($request->anticipo_monto, 2)
                : 'Cita creada por recepción, pendiente de anticipo',
        ]);

        return redirect()
            ->route('recepcionista.dashboard')
            ->with('success', 'Cita agendada correctamente');
    }

    public function index()
    {
        $citas = Cita::with(['cliente.usuario', 'servicio'])
            ->whereDate('fecha', now())
            ->orderBy('hora_inicio')
            ->get();

        return view('recepcionista.citas.index', compact('citas'));
    }
}
