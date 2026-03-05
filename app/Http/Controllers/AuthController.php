<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $throttleKey = Str::lower((string) $request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Demasiados intentos. Intenta nuevamente en {$minutes} minuto(s).",
            ])->withInput();
        }

        $credentials = $request->validate(
            [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'min:6', 'max:255'],
            ],
            [
                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es valido',
                'password.required' => 'La contrasena es obligatoria',
                'password.min' => 'La contrasena debe tener al menos 6 caracteres',
            ]
        );

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'active' => 1,
        ])) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect('/redirect');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Correo o contrasena incorrectos',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'nombre' => ['required', 'string', 'min:3'],
                'apellido' => ['required', 'string', 'min:3'],
                'telefono' => ['required', 'digits:10'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:6'],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos 3 letras',
                'apellido.required' => 'Los apellidos son obligatorios',
                'apellido.min' => 'Los apellidos deben tener al menos 3 letras',
                'telefono.required' => 'El telefono es obligatorio',
                'telefono.digits' => 'El telefono debe tener exactamente 10 digitos',
                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es valido',
                'email.unique' => 'Este correo ya esta registrado',
                'password.required' => 'La contrasena es obligatoria',
                'password.confirmed' => 'Las contrasenas no coinciden',
                'password.min' => 'La contrasena debe tener minimo 6 caracteres',
            ]
        );

        $usuario = Usuario::create([
            'name' => $request->nombre,
            'last_name' => $request->apellido,
            'phone' => $request->telefono,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);

        if ($usuario->role_id === 2) {
            Cliente::create([
                'user_id' => $usuario->id,
            ]);
        }

        return redirect('/login')->with('success', 'Cuenta creada correctamente');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
