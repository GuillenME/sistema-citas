<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRecepcionistaController extends Controller
{
    public function index()
    {
        $recepcionistas = Usuario::where('role_id', 3)->get();
        return view('admin.recepcionistas.index', compact('recepcionistas'));
    }

    public function create()
    {
        return view('admin.recepcionistas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string',
            'password' => 'required|min:6',
        ]);

        Usuario::create([
            'name' => $request->nombre,
            'last_name' => $request->apellido,
            'email' => $request->email,
            'phone' => $request->telefono,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'active' => 1,
        ]);

        return redirect()->route('admin.recepcionistas.index')
            ->with('success', 'Recepcionista creado correctamente');
    }

    public function edit(Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 404);
        return view('admin.recepcionistas.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 404);

        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'nullable|string',
            'telefono' => 'nullable|string',
        ]);

        $usuario->update([
            'name' => $request->nombre,
            'last_name' => $request->apellido,
            'phone' => $request->telefono,
        ]);

        return back()->with('success', 'Datos actualizados');
    }

    public function toggleActivo(Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 404);

        $usuario->update([
            'active' => !$usuario->active
        ]);

        return back()->with('success', 'Estado actualizado');
    }
}
