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
    | FORMULARIO CREAR CITA
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $servicios = Servicio::where('activo', 1)->get();
        return view('cliente.citas.create', compact('servicios'));
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
            'servicio_id' => 'required|exists:servicios,id',
        ]);

        $fecha = Carbon::parse($request->fecha);

        if ($fecha->isSunday()) {
            return response()->json([]);
        }

        $servicio = Servicio::findOrFail($request->servicio_id);
        $duracion = $servicio->duracion_minutos;

        $rangos = $fecha->isWeekday()
            ? [[480, 900], [960, 1200]]
            : [[480, 900]];

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
    | GUARDAR CITA
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
        ]);

        $cliente = Cliente::where('usuario_id', auth()->id())->first();

        $servicio = Servicio::findOrFail($request->servicio_id);
        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        Cita::create([
            'cliente_id' => $cliente->id,
            'servicio_id' => $servicio->id,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
            'estado' => 'pendiente_anticipo',
        ]);

        return redirect()->route('cliente.citas.index');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBIR COMPROBANTE (🔥 CORREGIDO)
    |--------------------------------------------------------------------------
    */
    public function subirComprobante(Request $request, Cita $cita)
    {
        $cliente = Cliente::where('usuario_id', auth()->id())->first();

        if (!$cliente || $cita->cliente_id !== $cliente->id) {
            abort(403);
        }

        if ($cita->estado !== 'pendiente_anticipo') {
            return back()->withErrors('Esta cita no acepta comprobantes');
        }

        $request->validate([
            'comprobante' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ ESTA LÍNEA ES LA CLAVE
        $ruta = $request->file('comprobante')->store('comprobantes', 'public');

        $cita->update([
            'comprobante' => $ruta,
        ]);

        return back()->with('success', 'Comprobante enviado correctamente');
    }
}
