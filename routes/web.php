<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;

/*
|--------------------------------------------------------------------------
| Autenticación (solo invitados)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'loginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Cerrar sesión
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Redirección por rol después del login
|--------------------------------------------------------------------------
*/

Route::get('/redirect', function () {

    $rol = auth()->user()->rol_id;

    if ($rol == 1) {
        return redirect('/admin');
    }

    if ($rol == 3) {
        return redirect('/recepcionista');
    }

    return redirect('/cliente');

})->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN (rol_id = 1)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:1'])->group(function () {

    Route::get('/admin', function () {
        return view('admin.dashboard');
    });

});

/*
|--------------------------------------------------------------------------
| RECEPCIONISTA (rol_id = 3)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:3'])->group(function () {

    Route::get('/recepcionista', function () {
        return view('recepcionista.dashboard');
    });

});

/*
|--------------------------------------------------------------------------
| CLIENTE (rol_id = 2)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:2'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {

        // Dashboard cliente
        Route::get('/', function () {
            return view('cliente.dashboard');
        })->name('dashboard');

        // Ver citas
        Route::get('/citas', [CitaController::class, 'index'])
            ->name('citas.index');

        // Formulario crear cita
        Route::get('/citas/crear', [CitaController::class, 'create'])
            ->name('citas.create');

        // Guardar cita (cuando lo implementes)
        Route::post('/citas', [CitaController::class, 'store'])
            ->name('citas.store');
});
