<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Constants\CitaStatus;
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
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente) {
            abort(403, 'Cliente no encontrado');
        }

        $citas = Cita::where('client_id', $cliente->id)
            ->with(['service' => function($query) {
                $query->with(['promociones' => function($q) {
                    $q->where('published', true)
                        ->where('start_date', '<=', now()->toDateString())
                        ->where('end_date', '>=', now()->toDateString());
                }]);
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $porcentajeAnticipo = config('citas.porcentaje_anticipo', 50);
        $porcentajeRestante = 100 - $porcentajeAnticipo;

        return view('cliente.citas.index', compact('citas', 'porcentajeAnticipo', 'porcentajeRestante'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO CREAR CITA
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $servicios = Servicio::where('active', 1)
            ->with(['promociones' => function($query) {
                $query->where('published', true)
                    ->where('start_date', '<=', now()->toDateString())
                    ->where('end_date', '>=', now()->toDateString());
            }])
            ->get();
        
        $porcentajeAnticipo = config('citas.porcentaje_anticipo', 50);
        $porcentajeRestante = 100 - $porcentajeAnticipo;
        
        return view('cliente.citas.create', compact('servicios', 'porcentajeAnticipo', 'porcentajeRestante'));
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
            'servicio_id' => 'required|exists:services,id',
        ]);

        $fecha = Carbon::parse($request->fecha);

        if ($fecha->isSunday()) {
            return response()->json([]);
        }

        $servicio = Servicio::findOrFail($request->servicio_id);
        $duracion = $servicio->duration_minutes;

        $rangos = $fecha->isWeekday()
            ? [[480, 900], [960, 1200]]
            : [[480, 900]];

        // Si la fecha es hoy, calcular la hora mínima (1 hora después de ahora)
        $horaMinima = null;
        if ($fecha->isToday()) {
            $horaActual = Carbon::now();
            $horaMinima = $horaActual->copy()->addHour(); // 1 hora después
            $horaMinimaMinutos = $horaMinima->hour * 60 + $horaMinima->minute;
        }

        $citas = Cita::whereDate('date', $fecha)
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
            ->get(['start_time', 'end_time']);

        $ocupados = [];

        foreach ($citas as $cita) {
            $ocupados[] = [
                Carbon::parse($cita->start_time)->hour * 60 + Carbon::parse($cita->start_time)->minute,
                Carbon::parse($cita->end_time)->hour * 60 + Carbon::parse($cita->end_time)->minute,
            ];
        }

        $bloques = [];

        foreach ($rangos as [$inicioR, $finR]) {
            // Si es hoy, usar el máximo entre el inicio del rango y la hora mínima
            $inicioReal = $horaMinima !== null
                ? max($inicioR, $horaMinimaMinutos)
                : $inicioR;

            for ($min = $inicioReal; $min + $duracion <= $finR; $min += 15) {

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
        'servicio_id'       => 'required|exists:services,id',
        'fecha'             => [
            'required',
            'date',
            'after_or_equal:today',
            function ($attribute, $value, $fail) {
                $fecha = Carbon::parse($value);
                if ($fecha->isSunday()) {
                    $fail('Los domingos no se atiende. Por favor selecciona otro dia.');
                }
            },
        ],
        'hora_inicio'       => 'required|date_format:H:i',
        'acepta_privacidad' => 'required|accepted',
    ], [
        'acepta_privacidad.required' => 'Debes aceptar la politica de privacidad.',
        'acepta_privacidad.accepted' => 'Debes aceptar la politica de privacidad.',
        'fecha.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
    ]);

    $cliente = Cliente::where('user_id', auth()->id())->first();

    if (!$cliente) {
        abort(403, 'Cliente no encontrado');
    }

    $servicio = Servicio::findOrFail($request->servicio_id);

    if (!$servicio->active) {
        return back()->withErrors(['servicio_id' => 'El servicio seleccionado no esta disponible.'])->withInput();
    }

    $horaInicio = Carbon::parse($request->hora_inicio);
    $horaFin = $horaInicio->copy()->addMinutes($servicio->duration_minutes);

    $driver = DB::getDriverName();
    $lockName = 'citas:' . $request->fecha;
    $lockAcquired = true;

    if ($driver === 'mysql') {
        $lockResult = DB::selectOne('SELECT GET_LOCK(?, 10) AS l', [$lockName]);
        $lockAcquired = ((int) ($lockResult->l ?? 0)) === 1;
    }

    if (!$lockAcquired) {
        return back()->withErrors([
            'hora_inicio' => 'No fue posible validar disponibilidad en este momento. Intenta de nuevo.',
        ])->withInput();
    }

    try {
        DB::beginTransaction();

        $citaSolapada = Cita::whereDate('date', $request->fecha)
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->where('start_time', '<', $horaFin->format('H:i'))
                    ->where('end_time', '>', $horaInicio->format('H:i'));
            })
            ->lockForUpdate()
            ->exists();

        if ($citaSolapada) {
            DB::rollBack();
            return back()->withErrors([
                'hora_inicio' => 'El horario seleccionado no esta disponible. Por favor elige otro horario.',
            ])->withInput();
        }

        $cita = Cita::create([
            'client_id' => $cliente->id,
            'service_id' => $servicio->id,
            'date'       => $request->fecha,
            'start_time' => $horaInicio->format('H:i'),
            'end_time'   => $horaFin->format('H:i'),
            'status'     => CitaStatus::PENDIENTE_ANTICIPO,
        ]);

        DB::commit();

        Log::info('Cita creada', [
            'cita_id' => $cita->id,
            'cliente_id' => $cliente->id,
            'servicio_id' => $servicio->id,
            'fecha' => $request->fecha,
        ]);

        return redirect()
            ->route('cliente.citas.index')
            ->with('success', 'Cita agendada correctamente. Pendiente de anticipo.');
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al crear cita', [
            'error' => $e->getMessage(),
            'cliente_id' => $cliente->id,
            'servicio_id' => $servicio->id,
        ]);

        return back()->withErrors([
            'error' => 'Ocurrio un error al agendar la cita. Por favor intenta nuevamente.',
        ])->withInput();
    } finally {
        if ($driver === 'mysql' && $lockAcquired) {
            DB::selectOne('SELECT RELEASE_LOCK(?) AS l', [$lockName]);
        }
    }
}

    public function subirComprobante(Request $request, Cita $cita)
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente || $cita->client_id !== $cliente->id) {
            abort(403);
        }

        if ($cita->status !== CitaStatus::PENDIENTE_ANTICIPO) {
            return back()->withErrors('Esta cita no acepta comprobantes');
        }

        $request->validate([
            'comprobante' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ESTA LÍNEA ES LA CLAVE
        $ruta = $request->file('comprobante')->store('comprobantes', 'public');

        $cita->update([
            'receipt' => $ruta,
        ]);

        return back()->with(
            'info',
            '⏳ Estamos validando tu anticipo. Te notificaremos cuando sea confirmado.'
        );
    }
}
