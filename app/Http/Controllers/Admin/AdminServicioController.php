<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('servicios', 'public');
        }

        Servicio::create([
            'name' => $request->nombre,
            'description' => $request->descripcion,
            'duration_minutes' => $request->duracion_minutos,
            'price' => $request->precio,
            'active' => 1,
            'image' => $imagePath,
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
        $data = $request->validate([
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'duracion_minutos' => 'required|integer|min:5',
            'precio' => 'required|numeric|min:0',
            'activo' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($servicio->image) {
                Storage::disk('public')->delete($servicio->image);
            }

            $data['image'] = $request->file('image')
                ->store('servicios', 'public');
        }

        $servicio->update([
            'name' => $request->nombre,
            'description' => $request->descripcion,
            'duration_minutes' => $request->duracion_minutos,
            'price' => $request->precio,
            'active' => $request->activo,
            'image' => $data['image'] ?? $servicio->image,
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado');
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->image) {
            Storage::disk('public')->delete($servicio->image);
        }

        $servicio->update(['active' => 0]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio desactivado');
    }

}
