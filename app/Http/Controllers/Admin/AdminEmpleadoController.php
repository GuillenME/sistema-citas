<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;

class AdminEmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::orderBy('nombre')->get();
        return view('admin.empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('admin.empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'telefono'     => 'required|string|max:20',
            'especialidad' => 'required|string|max:255',
        ]);

        Empleado::create([
            'nombre'       => $request->nombre,
            'telefono'     => $request->telefono,
            'especialidad' => $request->especialidad,
            'activo'       => true,
        ]);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado creado correctamente');
    }

    public function edit(Empleado $empleado)
    {
        return view('admin.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'telefono'     => 'required|string|max:20',
            'especialidad' => 'required|string|max:255',
            'activo'       => 'required|boolean',
        ]);

        $empleado->update([
            'nombre'       => $request->nombre,
            'telefono'     => $request->telefono,
            'especialidad' => $request->especialidad,
            'activo'       => $request->activo,
        ]);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado actualizado');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->update(['activo' => false]);

        return back()->with('success', 'Empleado desactivado');
    }
}
