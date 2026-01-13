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
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion_minutos' => $request->duracion_minutos,
            'precio' => $request->precio,
            'activo' => 1
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
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion_minutos' => $request->duracion_minutos,
            'precio' => $request->precio,
            'activo' => $request->activo
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado');
    }

    // En lugar de borrar → desactivar
    public function destroy(Servicio $servicio)
    {
        $servicio->update(['activo' => 0]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio desactivado');
    }
}
