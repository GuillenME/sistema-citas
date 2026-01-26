<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;

class AdminEmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::orderBy('name')->get();
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
            'name'       => $request->nombre,
            'phone'     => $request->telefono,
            'specialty' => $request->especialidad,
            'active'       => true,
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
            'name'       => $request->nombre,
            'phone'     => $request->telefono,
            'specialty' => $request->especialidad,
            'active'       => $request->activo,
        ]);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado actualizado');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->update(['active' => false]);

        return back()->with('success', 'Empleado desactivado');
    }
}
