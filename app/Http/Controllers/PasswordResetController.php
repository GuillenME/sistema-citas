<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Debes ingresar un correo valido',
        ]);

        Password::sendResetLink($request->only('email'));

        // Respuesta generica para evitar enumeracion de cuentas.
        return back()->with(
            'success',
            'Si el correo existe en el sistema, te enviaremos un enlace para restablecer tu contraseña.'
        );
    }

    public function resetForm(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6|max:255',
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Correo invalido',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Tu contraseña fue actualizada correctamente. Inicia sesion.');
        }

        return back()->withErrors([
            'email' => 'El enlace de recuperacion es invalido o ya expiro.',
        ]);
    }
}
