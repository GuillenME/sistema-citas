<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use Illuminate\Http\Request;

class AdminPromocionController extends Controller
{
    public function index()
    {
        $promociones = Promocion::latest()->get();
        return view('admin.promociones.index', compact('promociones'));
    }

    public function create()
    {
        return view('admin.promociones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|min:5|max:255',
            'descripcion' => 'required|string',
            'descuento' => 'required|integer|min:1|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $data['publicada'] = $request->has('publicada');

        Promocion::create($data);

        return redirect()
            ->route('admin.promociones.index')
            ->with('success', 'Promoción creada correctamente');
    }

    public function edit(Promocion $promocion)
    {
        return view('admin.promociones.edit', compact('promocion'));
    }

    public function update(Request $request, Promocion $promocion)
    {
        $data = $request->validate([
            'titulo' => 'required|string|min:5|max:255',
            'descripcion' => 'required|string',
            'descuento' => 'required|integer|min:1|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $data['publicada'] = $request->has('publicada');

        $promocion->update($data);

        return redirect()
            ->route('admin.promociones.index')
            ->with('success', 'Promoción actualizada correctamente');
    }

    public function destroy(Promocion $promocion)
    {
        $promocion->delete();

        return back()->with('success', 'Promoción eliminada');
    }
}
