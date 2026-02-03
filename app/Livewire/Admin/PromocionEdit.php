<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Promocion;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Notifications\NuevaPromocionNotification;

class PromocionEdit extends Component
{
    use WithFileUploads;

    public Promocion $promocion;

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
        'descuento' => 'required|numeric|min:1|max:100',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'servicios' => 'required|array|min:1',
        'image' => 'nullable|image|max:2048',
    ];


    public function mount(Promocion $promocion)
    {
        $this->promocion = $promocion;

        $this->titulo       = $promocion->title;
        $this->descripcion  = $promocion->description;
        $this->descuento    = $promocion->discount;
        $this->fecha_inicio = optional($promocion->start_date)->format('Y-m-d');
        $this->fecha_fin    = optional($promocion->end_date)->format('Y-m-d');
        $this->publicada    = (bool) $promocion->published;

        $this->servicios = $promocion
            ->servicios()
            ->pluck('services.id')
            ->toArray();
    }

    public function abrirConfirmacion()
    {
        // 🚨 SIN VALIDAR AQUÍ
        $this->confirmar = true;
    }

    public function actualizar()
    {
        $this->validate();

        $wasPublished = (bool) $this->promocion->published;

        if ($this->image) {
            $this->promocion->image =
                $this->image->store('promociones', 'public');
        }

        $this->promocion->update([
            'title'       => $this->titulo,
            'description' => $this->descripcion,
            'discount'    => (float) $this->descuento,
            'start_date'  => $this->fecha_inicio,
            'end_date'    => $this->fecha_fin,
            'published'   => $this->publicada,
        ]);

        $this->promocion->servicios()->sync($this->servicios);

        if (!$wasPublished && $this->publicada) {
            $usuarios = Usuario::where('active', 1)->get();
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NuevaPromocionNotification($this->promocion));
            }
        }

        $this->confirmar = false;

        return redirect()->route('admin.promociones.index');
    }



    public function render()
    {
        return view('livewire.admin.promocion-edit', [
            'listaServicios' => Servicio::where('active', true)->get(),
        ]);
    }
}
