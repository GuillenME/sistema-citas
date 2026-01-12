<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
    
Route::get('/redirect', function () {
    $rol = auth()->user()->rol_id;

    if ($rol == 1) return redirect('/admin');
    if ($rol == 3) return redirect('/recepcionista');
    return redirect('/cliente');
})->middleware('auth');

Route::middleware(['auth', 'rol:1'])->get('/admin', function () {
    return view('admin.dashboard');
});

Route::middleware(['auth', 'rol:3'])->get('/recepcionista', function () {
    return view('recepcionista.dashboard');
});

Route::middleware(['auth', 'rol:2'])->get('/cliente', function () {
    return view('cliente.dashboard');
});
