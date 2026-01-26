<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6'],
            ],
            [
                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es válido',
                'password.required' => 'La contraseña es obligatoria',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            ]
        );

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/redirect');
        }

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos'
        ])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'nombre'    => ['required', 'string', 'min:3'],
                'apellido'  => ['required', 'string', 'min:3'],
                'telefono'  => ['required', 'digits:10'],
                'email'     => ['required', 'email', 'unique:users,email'],
                'password'  => ['required', 'confirmed', 'min:6'],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos 3 letras',

                'apellido.required' => 'Los apellidos son obligatorios',
                'apellido.min' => 'Los apellidos deben tener al menos 3 letras',


                'telefono.required' => 'El teléfono es obligatorio',
                'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos',

                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es válido',
                'email.unique' => 'Este correo ya está registrado',

                'password.required' => 'La contraseña es obligatoria',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'password.min' => 'La contraseña debe tener mínimo 6 caracteres',
            ]
        );

        $usuario = Usuario::create([
            'name'      => $request->nombre,
            'last_name' => $request->apellido,
            'phone'     => $request->telefono,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => 2,
        ]);

        // 👇 Si el usuario es cliente, crear registro en clientes
        if ($usuario->role_id == 2) {
            Cliente::create([
                'user_id' => $usuario->id,
               // 'phone'   => $usuario->phone,
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
