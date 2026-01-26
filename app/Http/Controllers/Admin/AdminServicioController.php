<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class AdminServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'duracion_minutos' => 'required|integer|min:5',
            'precio' => 'required|numeric|min:0',
        ]);

        Servicio::create([
            'name' => $request->nombre,
            'description' => $request->descripcion,
            'duration_minutes' => $request->duracion_minutos,
            'price' => $request->precio,
            'active' => 1
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado correctamente');
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'duracion_minutos' => 'required|integer|min:5',
            'precio' => 'required|numeric|min:0',
            'activo' => 'required|boolean'
        ]);

        $servicio->update([
            'name' => $request->nombre,
            'description' => $request->descripcion,
            'duration_minutes' => $request->duracion_minutos,
            'price' => $request->precio,
            'active' => $request->activo
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado');
    }

    // En lugar de borrar → desactivar
    public function destroy(Servicio $servicio)
    {
        $servicio->update(['active' => 0]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio desactivado');
    }
}
