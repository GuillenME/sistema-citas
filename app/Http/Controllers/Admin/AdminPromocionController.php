<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usuario;
use App\Notifications\NuevaPromocionNotification;
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
    $request->validate([
        'titulo' => 'required|min:5',
        'descripcion' => 'required',
        'descuento' => 'required|integer|min:1|max:100',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    $promocion = Promocion::create([
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'descuento' => $request->descuento,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
        'publicada' => $request->has('publicada'),
    ]);

    // ✅ SOLO SI SE PUBLICA
    if ($promocion->publicada) {
        $usuarios = Usuario::where('activo', 1)->get();

        foreach ($usuarios as $usuario) {
            $usuario->notify(new NuevaPromocionNotification($promocion));
        }
    }

    return redirect()->route('admin.promociones.index')
        ->with('success', 'Promoción creada y notificada por correo');
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
