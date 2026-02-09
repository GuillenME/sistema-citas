<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Admin\AdminCitaController;
use App\Http\Controllers\Admin\AdminClientesController;
use App\Http\Controllers\Admin\AdminEmpleadoController;
use App\Http\Controllers\Admin\AdminPromocionController;
use App\Http\Controllers\Admin\AdminRecepcionistaController;
use App\Http\Controllers\Admin\AdminServicioController;
use App\Http\Controllers\Admin\HomeSettingController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Recepcionista\CitaController as RecepcionistaCitaController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServicioPublicController;

/* HOME PÚBLICO */

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/servicios', [ServicioPublicController::class, 'index'])
    ->name('servicios');
Route::get('/comentarios-publicos', [PublicController::class, 'comentarios'])
    ->name('comentarios.publicos');

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
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/citas', [AdminCitaController::class, 'index'])
            ->name('citas.index');

        Route::post('/citas/{cita}/confirmar', [AdminCitaController::class, 'confirmar'])
            ->name('citas.confirmar');

        Route::post('/citas/{cita}/cancelar', [AdminCitaController::class, 'cancelar'])
            ->name('citas.cancelar');

        // LIVEWIRE
        Route::get('/promociones/create', function () {
            return view('admin.promociones.create');
        })->name('promociones.create');

        Route::get('/promociones/{promocion}/edit', function (App\Models\Promocion $promocion) {
            return view('admin.promociones.edit', compact('promocion'));
        })->name('promociones.edit');

        // CONTROLLER (RESTO)
        Route::resource('promociones', AdminPromocionController::class)
            ->except(['create', 'edit'])
            ->parameters(['promociones' => 'promocion']);

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

        Route::get('/clientes', function () {
            return view('admin.clientes.index');
        })->name('clientes.index');


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

        Route::get('/citas', [CitaController::class, 'index'])
            ->name('citas.index');

        Route::get('/citas/crear', [CitaController::class, 'create'])
            ->name('citas.create');

        // (opcional, puede quedarse)
        Route::get('/citas/bloques', [CitaController::class, 'bloquesDisponibles'])
            ->name('citas.bloques');
        Route::post('/citas/{cita}/comprobante', [CitaController::class, 'subirComprobante'])
            ->name('citas.comprobante');

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
