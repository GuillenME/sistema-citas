<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usuario;
use App\Notifications\NuevaPromocionNotification;
use App\Http\Controllers\Controller;
use App\Models\Promocion;
use App\Models\Servicio;
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
        $servicios = Servicio::where('active', true)->get();
        return view('admin.promociones.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|min:5',
            'descripcion' => 'required',
            'descuento' => 'required|numeric|min:1|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
        ]);

        $promocion = Promocion::create([
            'title' => $request->titulo,
            'description' => $request->descripcion,
            'discount' => $request->descuento,
            'start_date' => $request->fecha_inicio,
            'end_date' => $request->fecha_fin,
            'published' => $request->has('publicada'),
        ]);

        // Sincronizar servicios
        $promocion->servicios()->sync($request->servicios);

        // ✅ SOLO SI SE PUBLICA
        if ($promocion->published) {
            $usuarios = Usuario::where('active', 1)->get();

            foreach ($usuarios as $usuario) {
                $usuario->notify(new NuevaPromocionNotification($promocion));
            }
        }

        return redirect()->route('admin.promociones.index')
            ->with('success', 'Promoción creada y notificada por correo');
    }


    public function edit(Promocion $promocion)
    {
        $servicios = Servicio::where('active', true)->get();
        $serviciosSeleccionados = $promocion->servicios->pluck('id')->toArray();
        return view('admin.promociones.edit', compact('promocion', 'servicios', 'serviciosSeleccionados'));
    }

    public function update(Request $request, Promocion $promocion)
    {
        $data = $request->validate([
            'titulo' => 'required|string|min:5|max:255',
            'descripcion' => 'required|string',
            'descuento' => 'required|numeric|min:1|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
        ]);

        $data['published'] = $request->has('publicada');
        $data['title'] = $request->titulo;
        $data['description'] = $request->descripcion;
        $data['discount'] = $request->descuento;
        $data['start_date'] = $request->fecha_inicio;
        $data['end_date'] = $request->fecha_fin;

        $promocion->update($data);

        // Sincronizar servicios
        $promocion->servicios()->sync($request->servicios);

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
