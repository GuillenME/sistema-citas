<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Servicio;
use Illuminate\Support\Facades\Storage;

class FormularioServicio extends Component
{
    use WithFileUploads;

    public $servicio_id;
    public $name = '';
    public $description = '';
    public $duration_minutes = '';
    public $price = '';
    public $image;
    public $active = true;
    public $imagen_actual = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'duration_minutes' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'El nombre es requerido',
        'description.required' => 'La descripción es requerida',
        'duration_minutes.required' => 'La duración es requerida',
        'price.required' => 'El precio es requerido',
        'image.image' => 'El archivo debe ser una imagen',
        'image.max' => 'La imagen no puede exceder 2MB',
    ];

    public function mount($servicio_id = null)
    {
        if ($servicio_id) {
            $servicio = Servicio::find($servicio_id);
            if ($servicio) {
                $this->servicio_id = $servicio->id;
                $this->name = $servicio->name;
                $this->description = $servicio->description;
                $this->duration_minutes = $servicio->duration_minutes;
                $this->price = $servicio->price;
                $this->active = $servicio->active;
                $this->imagen_actual = $servicio->image;
            }
        }
    }

    public function guardar()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'price' => $this->price,
            'active' => $this->active,
        ];

        if ($this->image) {
            // Eliminar imagen anterior si existe
            if ($this->imagen_actual && Storage::disk('public')->exists($this->imagen_actual)) {
                Storage::disk('public')->delete($this->imagen_actual);
            }
            $data['image'] = $this->image->store('servicios', 'public');
        }

        if ($this->servicio_id) {
            Servicio::find($this->servicio_id)->update($data);
            $mensaje = 'Servicio actualizado correctamente';
        } else {
            Servicio::create($data);
            $mensaje = 'Servicio creado correctamente';
        }

        session()->flash('success', $mensaje);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.formulario-servicio');
    }
}
