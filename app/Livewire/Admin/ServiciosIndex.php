<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Servicio;

class ServiciosIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap'; // 🔥 clave

    public function render()
    {
        return view('livewire.admin.servicios-index', [
            'servicios' => Servicio::orderBy('name')->paginate(5)
        ]);
    }
}
