<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Promocion;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Notifications\NuevaPromocionNotification;

class PromocionCreate extends Component
{
    use WithFileUploads;

    public $titulo;
    public $descripcion;
    public $descuento;
    public $fecha_inicio;
    public $fecha_fin;
    public $image;
    public $servicios = [];
    public $publicada = false;

    public $confirmar = false;

    protected $rules = [
        'titulo' => 'required|min:3',
        'descripcion' => 'required|min:10',
        'descuento' => 'required|integer|min:1|max:100',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'servicios' => 'required|array|min:1',
        'image' => 'nullable|image|max:2048',
    ];

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function guardar()
    {
        $path = $this->image
            ? $this->image->store('promociones', 'public')
            : null;

        $promocion = Promocion::create([
            'title' => $this->titulo,
            'description' => $this->descripcion,
            'discount' => $this->descuento,
            'start_date' => $this->fecha_inicio,
            'end_date' => $this->fecha_fin,
            'image' => $path,
            'published' => $this->publicada ? 1 : 0,
        ]);

        $promocion->servicios()->sync($this->servicios);

        if ($promocion->published) {
            $usuarios = Usuario::where('active', 1)->get();
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NuevaPromocionNotification($promocion));
            }
        }

        return redirect()->route('admin.promociones.index');
    }

    public function render()
    {
        return view('livewire.admin.promocion-create', [
            'listaServicios' => Servicio::where('active', true)->get(),
        ]);
    }
}
