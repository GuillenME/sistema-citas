<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Promocion;
use App\Models\Servicio;

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
        'descuento' => 'required|integer|min:1|max:100',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'servicios' => 'required|array|min:1',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount(Promocion $promocion)
    {
        $this->promocion = $promocion;

        $this->titulo = $promocion->title;
        $this->descripcion = $promocion->description;
        $this->descuento = $promocion->discount;

        // ⚠️ FORMATO CORRECTO PARA INPUT DATE
        $this->fecha_inicio = $promocion->start_date?->format('Y-m-d');
        $this->fecha_fin    = $promocion->end_date?->format('Y-m-d');

        $this->publicada = (bool) $promocion->active;
        $this->servicios = $promocion->servicios->pluck('id')->toArray();
    }

    public function abrirConfirmacion()
    {
        //dd('SI ENTRA A ABRIR CONFIRMACION');
        $this->validate();
        $this->confirmar = true;
    }

    public function actualizar()
    {
        if ($this->image) {
            $this->promocion->image = $this->image->store('promociones', 'public');
        }

        $this->promocion->update([
            'title' => $this->titulo,
            'description' => $this->descripcion,
            'discount' => $this->descuento,
            'start_date' => $this->fecha_inicio,
            'end_date' => $this->fecha_fin,
            'active' => $this->publicada,
        ]);

        $this->promocion->servicios()->sync($this->servicios);

        return redirect()->route('admin.promociones.index');
    }

    

    public function render()
    {
        logger('RENDER PROMOCION EDIT');
        return view('livewire.admin.promocion-edit', [
            'listaServicios' => Servicio::where('active', true)->get(),
        ]);
    }
}
