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
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Recepcionista\CitaController as RecepcionistaCitaController;
use App\Http\Controllers\ServicioPublicController;

/* HOME PÚBLICO */

Route::get('/', [PublicController::class, 'index'])->name('home');

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

        Route::resource('servicios', AdminServicioController::class);

        Route::get('/empleados', [AdminEmpleadoController::class, 'index'])
            ->name('empleados.index');

        Route::get('/empleados/create', [AdminEmpleadoController::class, 'create'])
            ->name('empleados.create');

        Route::post('/empleados', [AdminEmpleadoController::class, 'store'])
            ->name('empleados.store');

        Route::get('/empleados/{empleado}/edit', [AdminEmpleadoController::class, 'edit'])
            ->name('empleados.edit');

        Route::put('/empleados/{empleado}', [AdminEmpleadoController::class, 'update'])
            ->name('empleados.update');

        Route::delete('/empleados/{empleado}', [AdminEmpleadoController::class, 'destroy'])
            ->name('empleados.destroy');

        Route::post('citas/{cita}/asignar-empleado', [AdminCitaController::class, 'asignarEmpleado'])
            ->name('citas.asignarEmpleado');

        Route::get('/clientes', [AdminClientesController::class, 'index'])
            ->name('clientes.index');

        Route::post('/clientes/{cliente}/desactivar', [AdminClientesController::class, 'desactivar'])
            ->name('clientes.desactivar');

        Route::post('/clientes/{cliente}/activar', [AdminClientesController::class, 'activar'])
            ->name('clientes.activar');

        Route::get('/recepcionistas', [AdminRecepcionistaController::class, 'index'])
            ->name('recepcionistas.index');

        Route::get('/recepcionistas/create', [AdminRecepcionistaController::class, 'create'])
            ->name('recepcionistas.create');

        Route::post('/recepcionistas', [AdminRecepcionistaController::class, 'store'])
            ->name('recepcionistas.store');

        Route::get('/recepcionistas/{usuario}/edit', [AdminRecepcionistaController::class, 'edit'])
            ->name('recepcionistas.edit');

        Route::put('/recepcionistas/{usuario}', [AdminRecepcionistaController::class, 'update'])
            ->name('recepcionistas.update');

        Route::post('/recepcionistas/{usuario}/toggle', [AdminRecepcionistaController::class, 'toggleActivo'])
            ->name('recepcionistas.toggle');
    });

/* RECEPCIONISTA (rol_id = 3) */
Route::middleware(['auth', 'rol:3'])
    ->prefix('recepcionista')
    ->name('recepcionista.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('recepcionista.dashboard');
        })->name('dashboard');

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

Route::resource('promociones', AdminPromocionController::class);

Route::get('/servicios', [ServicioPublicController::class, 'index'])
    ->name('servicios');

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
