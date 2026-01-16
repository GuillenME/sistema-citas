<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Admin\AdminCitaController;
use App\Http\Controllers\Admin\AdminPromocionController;
use App\Http\Controllers\Admin\AdminServicioController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Recepcionista\CitaController as RecepcionistaCitaController;


Route::get('/', [PublicController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| AUTH (Invitados)
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


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/citas', [AdminCitaController::class, 'index'])
        ->name('citas.index');

    Route::post('/citas/{cita}/confirmar', [AdminCitaController::class, 'confirmar'])
        ->name('citas.confirmar');

    Route::post('/citas/{cita}/cancelar', [AdminCitaController::class, 'cancelar'])
        ->name('citas.cancelar');
});


Route::middleware('auth')->prefix('cliente')->name('cliente.')->group(function () {

    Route::get('/dashboard', function () {
        return view('cliente.dashboard');
    })->name('dashboard');

    Route::get('/citas', [CitaController::class, 'index'])
        ->name('citas.index');

    Route::get('/citas/crear', [CitaController::class, 'create'])
        ->name('citas.create');

    Route::post('/citas', [CitaController::class, 'store'])
        ->name('citas.store');
});


Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| REDIRECCIÓN POR ROL
|--------------------------------------------------------------------------
*/
Route::get('/redirect', function () {

    $rol = auth()->user()->rol_id;

    if ($rol == 1) {
        return redirect()->route('admin.dashboard');
    }

    if ($rol == 3) {
        return redirect()->route('recepcionista.dashboard');
    }

    return redirect()->route('cliente.dashboard');
})->middleware('auth')->name('redirect');


/*
|--------------------------------------------------------------------------
| ADMIN (rol_id = 1)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/citas', [AdminCitaController::class, 'index'])
            ->name('citas.index');

        Route::post('/citas/{cita}/confirmar', [AdminCitaController::class, 'confirmar'])
            ->name('citas.confirmar');

        Route::post('/citas/{cita}/cancelar', [AdminCitaController::class, 'cancelar'])
            ->name('citas.cancelar');
        Route::resource('promociones', AdminPromocionController::class);
        Route::resource('servicios', AdminServicioController::class);
    });

/*
|--------------------------------------------------------------------------
| RECEPCIONISTA (rol_id = 3)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:3'])
    ->prefix('recepcionista')
    ->name('recepcionista.')
    ->group(function () {

        // Dashboard
        Route::get('/', function () {
            return view('recepcionista.dashboard');
        })->name('dashboard');

        // Agendar cita (vista)
        Route::get('/citas/create', [RecepcionistaCitaController::class, 'create'])
            ->name('citas.create');

        // Guardar cita
        Route::post('/citas', [RecepcionistaCitaController::class, 'store'])
            ->name('citas.store');
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

        Route::get('/', function () {
            return view('cliente.dashboard');
        })->name('dashboard');

        Route::get('/citas', [CitaController::class, 'index'])
            ->name('citas.index');

        Route::get('/citas/crear', [CitaController::class, 'create'])
            ->name('citas.create');

        Route::get('/citas/bloques', [CitaController::class, 'bloquesDisponibles'])
            ->name('citas.bloques');

        Route::post('/citas', [CitaController::class, 'store'])
            ->name('citas.store');
    });

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
