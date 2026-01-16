<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * Formulario para solicitar enlace de recuperación
     */
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar enlace de recuperación al correo
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email',
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Debes ingresar un correo válido',
            'email.exists' => 'Este correo no está registrado',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Te enviamos un enlace para restablecer tu contraseña')
            : back()->withErrors([
                'email' => 'No se pudo enviar el correo. Intenta nuevamente.'
            ]);
    }

    /**
     * Formulario para crear nueva contraseña
     */
    public function resetForm(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    /**
     * Actualizar contraseña y redirigir al login
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:usuarios,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Correo inválido',
            'email.exists' => 'Este correo no está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Tu contraseña fue actualizada correctamente. Inicia sesión.');
        }

        return back()->withErrors([
            'email' => 'El enlace de recuperación es inválido o ya expiró'
        ]);
    }
}
