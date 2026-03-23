<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Admin\AdminCitaController;
use App\Http\Controllers\Admin\AdminClientesController;
use App\Http\Controllers\Admin\AdminServicioController;
use App\Http\Controllers\Admin\HomeSettingController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Recepcionista\CitaController as RecepcionistaCitaController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServicioPublicController;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/* HOME PÚBLICO */

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/servicios', [ServicioPublicController::class, 'index'])
    ->name('servicios');
Route::get('/comentarios-publicos', [PublicController::class, 'comentarios'])
    ->name('comentarios.publicos');
Route::get('/noticias', [PublicController::class, 'noticias'])
    ->name('noticias.publicas');

/* POLÍTICA DE PRIVACIDAD */
Route::get('/politica-privacidad', function () {
    return view('legal.politica-privacidad');
})->name('politica.privacidad');

/* AUTH (INVITADOS) */
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'loginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

/* LOGOUT */
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/* RUTA GLOBAL PARA HORARIOS (CLIENTE + RECEPCIONISTA) */
Route::middleware('auth')->get(
    '/citas/bloques',
    [CitaController::class, 'bloquesDisponibles']
)->name('citas.bloques.global');

/* REDIRECCIÓN POR ROL */
Route::get('/redirect', function () {

    $rol = auth()->user()->role_id;

    if ($rol == 1) {
        return redirect()->route('admin.dashboard');
    }

    if ($rol == 3) {
        return redirect()->route('recepcionista.dashboard');
    }

    return redirect()->route('cliente.dashboard');
})->middleware('auth')->name('redirect');

/* ADMIN (rol_id = 1) */
Route::middleware(['auth', 'rol:1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $citasHoy = \App\Models\Cita::query()
                ->whereDate('date', today())
                ->count();

            return view('admin.dashboard', compact('citasHoy'));
        })->name('dashboard');

        Route::get('/citas', [AdminCitaController::class, 'index'])
            ->name('citas.index');
        Route::get('/citas/agenda', [AdminCitaController::class, 'agenda'])
            ->name('citas.agenda');
        Route::get('/citas/create', [AdminCitaController::class, 'create'])
            ->name('citas.create');
        Route::post('/citas', [AdminCitaController::class, 'store'])
            ->name('citas.store');

        Route::get('/citas/reporte-diario', [AdminCitaController::class, 'reporteDiario'])
            ->name('citas.reporte-diario');

        Route::get('/citas/reporte-diario/pdf', [AdminCitaController::class, 'reporteDiarioPdf'])
            ->name('citas.reporte-diario.pdf');

        Route::get('/citas/reporte-mensual', [AdminCitaController::class, 'reporteMensual'])
            ->name('citas.reporte-mensual');

        Route::get('/citas/reporte-mensual/pdf', [AdminCitaController::class, 'reporteMensualPdf'])
            ->name('citas.reporte-mensual.pdf');

        Route::post('/citas/{cita}/confirmar', [AdminCitaController::class, 'confirmar'])
            ->name('citas.confirmar');

        Route::post('/citas/{cita}/anticipo', [AdminCitaController::class, 'actualizarAnticipo'])
            ->name('citas.actualizarAnticipo');

        Route::post('/citas/{cita}/cancelar', [AdminCitaController::class, 'cancelar'])
            ->name('citas.cancelar');

        Route::post('/citas/{cita}/reagendar', [AdminCitaController::class, 'reagendar'])
            ->name('citas.reagendar');

        Route::post('/citas/{cita}/completar', [AdminCitaController::class, 'completar'])
            ->name('citas.completar');

        Route::post('/citas/{cita}/no-asistio', [AdminCitaController::class, 'marcarNoAsistio'])
            ->name('citas.noAsistio');
        Route::get('/citas/{cita}/ticket', [AdminCitaController::class, 'ticket'])
            ->name('citas.ticket');

        // PROMOCIONES (LIVEWIRE)
        Route::get('/promociones', function () {
            return view('admin.promociones.index');
        })->name('promociones.index');

        Route::get('/promociones/create', function () {
            return view('admin.promociones.create');
        })->name('promociones.create');

        Route::get('/promociones/{promocion}/edit', function (App\Models\Promocion $promocion) {
            return view('admin.promociones.edit', compact('promocion'));
        })->name('promociones.edit');

        Route::get('/servicios/plantilla', [AdminServicioController::class, 'downloadTemplate'])
            ->name('servicios.template');

        Route::get('/servicios/import', function () {
            return view('admin.servicios.import');
        })->name('servicios.import');

        Route::post('/servicios/import', [AdminServicioController::class, 'importCsv'])
            ->name('servicios.import.store');

        Route::resource('servicios', AdminServicioController::class);

        Route::get('/empleados', function () {
            return view('admin.empleados.index');
        })->name('empleados.index');

        Route::get('/empleados/create', function () {
            return view('admin.empleados.create');
        })->name('empleados.create');

        Route::get('/empleados/{empleado}/edit', function (App\Models\Empleado $empleado) {
            return view('admin.empleados.edit', compact('empleado'));
        })->name('empleados.edit');

        Route::get('/noticias', function () {
            return view('admin.noticias.index');
        })->name('noticias.index');

        Route::get('/noticias/create', function () {
            return view('admin.noticias.create');
        })->name('noticias.create');

        Route::get('/noticias/{noticia}/edit', function (App\Models\Noticia $noticia) {
            return view('admin.noticias.edit', compact('noticia'));
        })->name('noticias.edit');

        Route::post('citas/{cita}/asignar-empleado', [AdminCitaController::class, 'asignarEmpleado'])
            ->name('citas.asignarEmpleado');

        Route::get('/clientes', [AdminClientesController::class, 'index'])
            ->name('clientes.index');
        Route::get('/clientes/create', [AdminClientesController::class, 'create'])
            ->name('clientes.create');
        Route::post('/clientes', [AdminClientesController::class, 'store'])
            ->name('clientes.store');


        Route::get('/recepcionistas', function () {
            return view('admin.recepcionistas.index');
        })->name('recepcionistas.index');

        Route::get('/recepcionistas/create', function () {
            return view('admin.recepcionistas.create');
        })->name('recepcionistas.create');

        Route::get('/recepcionistas/{usuario}/edit', function (App\Models\Usuario $usuario) {
            return view('admin.recepcionistas.edit', compact('usuario'));
        })->name('recepcionistas.edit');

        Route::get('/home-settings', [HomeSettingController::class, 'edit'])
            ->name('home_settings.edit');

        Route::put('/home-settings', [HomeSettingController::class, 'update'])
            ->name('home_settings.update');
        Route::post('/citas/{cita}/rechazar', [AdminCitaController::class, 'rechazarPago'])
            ->name('citas.rechazar');

        Route::get('/notificacion/{id}', function ($id) {
            /** @var \App\Models\Usuario $user */
            $user = auth()->user();
            $noti = $user->notifications()->findOrFail($id);
            $noti->markAsRead();
            return redirect()->route('admin.citas.index');
        })->name('notificacion.leer');
        Route::get('/notificaciones', function () {

            $user = auth()->user();

            return response()->json([
                'count' => $user->unreadNotifications->count(),
                'notificaciones' => $user->unreadNotifications->take(5)->map(function ($n) {
                    return [
                        'id' => $n->id,
                        'mensaje' => $n->data['mensaje'],
                        'tiempo' => $n->created_at->diffForHumans()
                    ];
                })
            ]);
        })->name('notificaciones.json');

        Route::get('/notificaciones/todas', function () {
            /** @var \App\Models\Usuario $user */
            $user = auth()->user();
            $notificaciones = $user->notifications()->latest()->paginate(15);

            return view('admin.notificaciones.index', compact('notificaciones'));
        })->name('notificaciones.index');
    });

/* RECEPCIONISTA (rol_id = 3) */
Route::middleware(['auth', 'rol:3'])
    ->prefix('recepcionista')
    ->name('recepcionista.')
    ->group(function () {

        Route::get('/dashboard', [RecepcionistaCitaController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/citas/create', [RecepcionistaCitaController::class, 'create'])
            ->name('citas.create');

        Route::post('/citas', [RecepcionistaCitaController::class, 'store'])
            ->name('citas.store');

        Route::get('/citas/agenda', [RecepcionistaCitaController::class, 'agenda'])
            ->name('citas.agenda');

        Route::get('/citas/reporte-diario', [RecepcionistaCitaController::class, 'reporteDiario'])
            ->name('citas.reporte-diario');

        Route::get('/citas/reporte-diario/pdf', [RecepcionistaCitaController::class, 'reporteDiarioPdf'])
            ->name('citas.reporte-diario.pdf');

        Route::get('/citas/reporte-mensual', [RecepcionistaCitaController::class, 'reporteMensual'])
            ->name('citas.reporte-mensual');

        Route::get('/citas/reporte-mensual/pdf', [RecepcionistaCitaController::class, 'reporteMensualPdf'])
            ->name('citas.reporte-mensual.pdf');

        Route::post('/citas/{cita}/confirmar', [RecepcionistaCitaController::class, 'confirmar'])
            ->name('citas.confirmar');

        Route::post('/citas/{cita}/cancelar', [RecepcionistaCitaController::class, 'cancelar'])
            ->name('citas.cancelar');

        Route::post('/citas/{cita}/reagendar', [RecepcionistaCitaController::class, 'reagendar'])
            ->name('citas.reagendar');

        Route::post('/citas/{cita}/completar', [RecepcionistaCitaController::class, 'completar'])
            ->name('citas.completar');

        Route::post('/citas/{cita}/no-asistio', [RecepcionistaCitaController::class, 'marcarNoAsistio'])
            ->name('citas.noAsistio');

        Route::post('/citas/{cita}/asignar-empleado', [RecepcionistaCitaController::class, 'asignarEmpleado'])
            ->name('citas.asignarEmpleado');

        Route::post('/citas/{cita}/rechazar', [RecepcionistaCitaController::class, 'rechazarPago'])
            ->name('citas.rechazar');

        Route::get('/citas/{cita}/ticket', [RecepcionistaCitaController::class, 'ticket'])
            ->name('citas.ticket');

        Route::get('/citas', [RecepcionistaCitaController::class, 'index'])
            ->name('citas.index');
    });

/* CLIENTE (rol_id = 2) */
Route::middleware(['auth', 'rol:2'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('cliente.dashboard');
        })->name('dashboard');

        Route::get('/perfil', function () {
            $usuario = auth()->user()->load('client');
            $cliente = $usuario->client;

            $stats = [
                'citas_total' => \App\Models\Cita::query()
                    ->where('client_id', $cliente?->id)
                    ->count(),
                'proxima_cita' => \App\Models\Cita::query()
                    ->where('client_id', $cliente?->id)
                    ->whereDate('date', '>=', today())
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->first(),
            ];

            return view('cliente.perfil', compact('usuario', 'cliente', 'stats'));
        })->name('perfil');

        Route::post('/perfil', function (Request $request) {
            /** @var \App\Models\Usuario $usuario */
            $usuario = auth()->user();
            $cliente = $usuario->client ?? Cliente::create([
                'user_id' => $usuario->id,
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:30',
                'birth_date' => 'nullable|date|before:today',
            ]);

            $birthDateActual = $cliente->birth_date?->format('Y-m-d');
            $birthDateNueva = $validated['birth_date'] ?? null;

            if ($birthDateNueva !== $birthDateActual) {
                if ($birthDateActual !== null && (int) ($cliente->birth_date_change_count ?? 0) >= 1) {
                    return back()->withErrors([
                        'birth_date' => 'La fecha de nacimiento solo puede modificarse una vez despues de registrarla.',
                    ])->withInput();
                }

                if ($birthDateActual !== null) {
                    $cliente->birth_date_change_count = (int) ($cliente->birth_date_change_count ?? 0) + 1;
                }

                $cliente->birth_date = $birthDateNueva;
            }

            $usuario->update([
                'name' => $validated['name'],
                'last_name' => $validated['last_name'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);

            $cliente->save();

            return redirect()
                ->route('cliente.perfil')
                ->with('success', 'Perfil actualizado correctamente.');
        })->name('perfil.update');

        Route::post('/perfil/eliminar', function (Request $request) {
            $request->validate([
                'password' => 'required|current_password',
            ], [
                'password.required' => 'Debes confirmar tu contraseña para eliminar la cuenta.',
                'password.current_password' => 'La contraseña ingresada no es correcta.',
            ]);

            /** @var \App\Models\Usuario $usuario */
            $usuario = auth()->user()->load('client');
            $cliente = $usuario->client;

            DB::transaction(function () use ($usuario, $cliente) {
                $emailAnonimo = 'eliminado+' . $usuario->id . '+' . now()->format('YmdHis') . '@local.invalid';

                $usuario->forceFill([
                    'name' => 'Cliente eliminado',
                    'last_name' => null,
                    'email' => $emailAnonimo,
                    'phone' => null,
                    'password' => Hash::make(Str::random(40)),
                    'active' => false,
                    'remember_token' => null,
                ])->save();

                if ($cliente) {
                    $cliente->update([
                        'birth_date' => null,
                        'notes' => 'Cuenta anonimizada por solicitud del cliente el ' . now()->format('Y-m-d H:i:s'),
                        'birth_date_change_count' => 0,
                    ]);
                }
            });

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Tu cuenta fue eliminada correctamente. Conservamos solo el historial necesario sin tus datos personales.');
        })->name('perfil.delete');

        Route::get('/citas', [CitaController::class, 'index'])
            ->name('citas.index');

        Route::get('/citas/crear', [CitaController::class, 'create'])
            ->name('citas.create');

        // (opcional, puede quedarse)
        Route::get('/citas/bloques', [CitaController::class, 'bloquesDisponibles'])
            ->name('citas.bloques');
        Route::get('/citas/servicios-disponibles', [CitaController::class, 'serviciosDisponibles'])
            ->name('citas.serviciosDisponibles');
        Route::post('/citas/{cita}/comprobante', [CitaController::class, 'subirComprobante'])
            ->name('citas.comprobante');

        Route::post('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
            ->name('citas.cancelar');

        Route::post('/citas/{cita}/reagendar', [CitaController::class, 'reagendar'])
            ->name('citas.reagendar');

        Route::post('/citas', [CitaController::class, 'store'])
            ->name('citas.store');

        Route::get('/comentarios', [ReviewController::class, 'index'])
            ->name('comentarios');

        Route::post('/comentarios', [ReviewController::class, 'store'])
            ->name('comentarios.store');
    });

/* RECUPERAR CONTRASEÑA */
Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');


Route::view('/terminos', 'cliente.terminos')->name('terminos');
Route::view('/privacidad', 'cliente.privacidad')->name('privacidad');

Route::get('/promociones', function () {
    return view('promociones.index');
})->name('promociones');

/* API PARA PROMOCIONES */
Route::get('/api/promocion/{id}', function ($id) {
    $promocion = \App\Models\Promocion::findOrFail($id);
    return response()->json([
        'id' => $promocion->id,
        'title' => $promocion->title,
        'description' => $promocion->description,
        'discount' => $promocion->discount,
        'start_date' => $promocion->start_date,
        'end_date' => $promocion->end_date,
        'image' => $promocion->image ?? null
    ]);
});
