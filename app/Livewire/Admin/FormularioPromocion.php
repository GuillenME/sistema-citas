<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Promocion;
use Illuminate\Support\Facades\Storage;

class FormularioPromocion extends Component
{
    use WithFileUploads;

    public $promocion_id;
    public $title = '';
    public $description = '';
    public $discount = '';
    public $start_date = '';
    public $end_date = '';
    public $image;
    public $published = true;
    public $imagen_actual = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'discount' => 'required|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'published' => 'boolean',
    ];

    protected $messages = [
        'title.required' => 'El título es requerido',
        'description.required' => 'La descripción es requerida',
        'discount.required' => 'El descuento es requerido',
        'start_date.required' => 'La fecha de inicio es requerida',
        'end_date.required' => 'La fecha de fin es requerida',
        'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
        'image.image' => 'El archivo debe ser una imagen',
        'image.max' => 'La imagen no puede exceder 2MB',
    ];

    public function mount($promocion_id = null)
    {
        if ($promocion_id) {
            $promocion = Promocion::find($promocion_id);
            if ($promocion) {
                $this->promocion_id = $promocion->id;
                $this->title = $promocion->title;
                $this->description = $promocion->description;
                $this->discount = $promocion->discount;
                $this->start_date = $promocion->start_date->format('Y-m-d');
                $this->end_date = $promocion->end_date->format('Y-m-d');
                $this->published = $promocion->published;
                $this->imagen_actual = $promocion->image;
            }
        }
    }

    public function guardar()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'discount' => $this->discount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'published' => $this->published,
        ];

        if ($this->image) {
            // Eliminar imagen anterior si existe
            if ($this->imagen_actual && Storage::disk('public')->exists($this->imagen_actual)) {
                Storage::disk('public')->delete($this->imagen_actual);
            }
            $data['image'] = $this->image->store('promociones', 'public');
        }

        if ($this->promocion_id) {
            Promocion::find($this->promocion_id)->update($data);
            $mensaje = 'Promoción actualizada correctamente';
        } else {
            Promocion::create($data);
            $mensaje = 'Promoción creada correctamente';
        }

        session()->flash('success', $mensaje);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.formulario-promocion');
    }
}
