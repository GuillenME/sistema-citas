<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientProfileController extends Controller
{
    public function show()
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = auth()->user()->load('client');
        $cliente = $usuario->client;

        $stats = [
            'citas_total' => Cita::query()
                ->where('client_id', $cliente?->id)
                ->count(),
            'proxima_cita' => Cita::query()
                ->where('client_id', $cliente?->id)
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('start_time')
                ->first(),
        ];

        return view('cliente.perfil', compact('usuario', 'cliente', 'stats'));
    }

    public function update(Request $request)
    {
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

        $currentBirthDate = $cliente->birth_date?->format('Y-m-d');
        $newBirthDate = $validated['birth_date'] ?? null;

        if ($newBirthDate !== $currentBirthDate) {
            if ($currentBirthDate !== null && (int) ($cliente->birth_date_change_count ?? 0) >= 1) {
                return back()->withErrors([
                    'birth_date' => 'La fecha de nacimiento solo puede modificarse una vez despues de registrarla.',
                ])->withInput();
            }

            if ($currentBirthDate !== null) {
                $cliente->birth_date_change_count = (int) ($cliente->birth_date_change_count ?? 0) + 1;
            }

            $cliente->birth_date = $newBirthDate;
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
    }

    public function destroy(Request $request)
    {
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
            $anonymousEmail = 'eliminado+' . $usuario->id . '+' . now()->format('YmdHis') . '@local.invalid';

            $usuario->forceFill([
                'name' => 'Cliente eliminado',
                'last_name' => null,
                'email' => $anonymousEmail,
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
    }
}
