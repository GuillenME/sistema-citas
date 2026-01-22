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
        $recepcionistas = Usuario::where('rol_id', 3)->get();
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
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string',
            'password' => 'required|min:6',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol_id' => 3,
            'activo' => 1,
        ]);

        return redirect()->route('admin.recepcionistas.index')
            ->with('success', 'Recepcionista creado correctamente');
    }

    public function edit(Usuario $usuario)
    {
        abort_if($usuario->rol_id !== 3, 404);
        return view('admin.recepcionistas.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        abort_if($usuario->rol_id !== 3, 404);

        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'nullable|string',
            'telefono' => 'nullable|string',
        ]);

        $usuario->update($request->only('nombre', 'apellido', 'telefono'));

        return back()->with('success', 'Datos actualizados');
    }

    public function toggleActivo(Usuario $usuario)
    {
        abort_if($usuario->rol_id !== 3, 404);

        $usuario->update([
            'activo' => !$usuario->activo
        ]);

        return back()->with('success', 'Estado actualizado');
    }
}
