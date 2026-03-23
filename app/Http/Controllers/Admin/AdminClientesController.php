<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Notifications\WelcomeClientNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('user')->get();

        return view('admin.clientes.index', compact('clientes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nombre' => ['required', 'string', 'min:3', 'max:255'],
                'apellido' => ['required', 'string', 'min:2', 'max:255'],
                'telefono' => ['required', 'digits:10'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 3 letras.',
                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.min' => 'El apellido debe tener al menos 2 letras.',
                'telefono.required' => 'El telefono es obligatorio.',
                'telefono.digits' => 'El telefono debe tener exactamente 10 digitos.',
                'email.required' => 'El correo es obligatorio.',
                'email.email' => 'El correo no es valido.',
                'email.unique' => 'Este correo ya esta registrado.',
            ]
        );

        $usuario = Usuario::create([
            'name' => $validated['nombre'],
            'last_name' => $validated['apellido'],
            'phone' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'role_id' => 2,
            'active' => 1,
        ]);

        Cliente::create([
            'user_id' => $usuario->id,
        ]);

        try {
            $token = Password::createToken($usuario);
            $usuario->notify(new WelcomeClientNotification($token));

            return redirect()->route('admin.clientes.index')
                ->with('success', 'Cliente creado correctamente. Se envio un correo de bienvenida para definir su contraseña.');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('admin.clientes.index')
                ->with('success', 'Cliente creado correctamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function desactivar(Cliente $cliente)
    {
        $cliente->user->update([
            'active' => 0
        ]);

        return back()->with('success', 'Cuenta desactivada correctamente');
    }

    public function activar(Cliente $cliente)
    {
        $cliente->user->update([
            'active' => 1
        ]);

        return back()->with('success', 'Cuenta activada correctamente');
    }
}
