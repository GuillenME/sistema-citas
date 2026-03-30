<?php

namespace App\Http\Controllers;

use App\Constants\CitaStatus;
use App\Models\Cliente;
use App\Models\Cita;
use App\Models\CitaEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientProfileController extends Controller
{
    private function upcomingActiveAppointmentsQuery(?Cliente $cliente)
    {
        return Cita::query()
            ->where('client_id', $cliente?->id)
            ->whereIn('status', [CitaStatus::PENDIENTE_ANTICIPO, CitaStatus::CONFIRMADA])
            ->where(function ($query) {
                $query->whereDate('date', '>', today()->toDateString())
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->whereDate('date', today()->toDateString())
                            ->where('start_time', '>', now()->format('H:i:s'));
                    });
            });
    }

    public function show()
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = auth()->user()->load('client');
        $cliente = $usuario->client;

        $upcomingActiveAppointments = $this->upcomingActiveAppointmentsQuery($cliente)
            ->with('service')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

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
            'citas_activas_futuras' => $upcomingActiveAppointments->count(),
        ];

        return view('cliente.perfil', compact('usuario', 'cliente', 'stats', 'upcomingActiveAppointments'));
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
        $citasActivasFuturas = $this->upcomingActiveAppointmentsQuery($cliente)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        DB::transaction(function () use ($usuario, $cliente, $citasActivasFuturas) {
            foreach ($citasActivasFuturas as $cita) {
                $notaBase = trim((string) ($cita->notes ?? ''));
                $motivo = 'Cancelada por eliminacion de cuenta del cliente.';
                $notaFinal = $notaBase === '' ? $motivo : $notaBase . ' | ' . $motivo;

                $cita->update([
                    'status' => CitaStatus::CANCELADA,
                    'notes' => $notaFinal,
                ]);

                CitaEstado::create([
                    'appointment_id' => $cita->id,
                    'status' => CitaStatus::CANCELADA,
                    'user_id' => $usuario->id,
                    'change_date' => now(),
                ]);
            }

            $anonymousEmail = 'eliminado+' . $usuario->id . '+' . now()->format('YmdHis') . '@local.invalid';

            $usuario->forceFill([
                'name' => 'Cliente anonimizado',
                'last_name' => null,
                'email' => $anonymousEmail,
                'phone' => null,
                'password' => Hash::make(Str::random(40)),
                'active' => false,
            ])->save();

            if ($cliente) {
                $cliente->update([
                    'birth_date' => null,
                    'notes' => 'Cuenta anonimizada por solicitud del cliente el ' . now()->format('Y-m-d H:i:s')
                        . '. Citas futuras canceladas automaticamente: ' . $citasActivasFuturas->count() . '.',
                    'birth_date_change_count' => 0,
                ]);
            }
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Tu cuenta fue anonimizada correctamente. '
                    . ($citasActivasFuturas->isNotEmpty()
                        ? 'Tambien cancelamos tus citas futuras para evitar dejar reservas activas sin acceso a la cuenta.'
                        : 'Conservamos solo el historial necesario sin tus datos personales.')
            );
    }
}
