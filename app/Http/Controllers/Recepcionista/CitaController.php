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
                        $fail('Los domingos no se atiende. Por favor selecciona otro día.');
                    }
                },
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'anticipo_monto' => 'nullable|numeric|min:0',
        ]);

        $cliente = Cliente::where('user_id', $request->usuario_id)->first();

        if (!$cliente) {
            return back()->with('error', 'El usuario no es cliente');
        }

        $servicio = Servicio::findOrFail($request->servicio_id);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duration_minutes);

        $anticipo = $request->filled('anticipo_monto');

        Cita::create([
            'client_id' => $cliente->id,
            'service_id' => $servicio->id,
            'date' => $request->fecha,
            'start_time' => $horaInicio->format('H:i'),
            'end_time' => $horaFin->format('H:i'),
            'status' => $anticipo ? 'confirmada' : 'pendiente_anticipo',
            'notes' => $anticipo
                ? 'Anticipo recibido en recepción: $' . number_format($request->anticipo_monto, 2)
                : 'Cita creada por recepción, pendiente de anticipo',
        ]);

        return redirect()
            ->route('recepcionista.dashboard')
            ->with('success', 'Cita agendada correctamente');
    }

    public function index()
    {
        $citas = Cita::with(['client.user', 'service'])
            ->whereDate('date', now())
            ->orderBy('start_time')
            ->get();

        return view('recepcionista.citas.index', compact('citas'));
    }
}
