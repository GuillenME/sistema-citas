<?php

namespace App\Livewire\Admin;

use App\Models\Servicio;
use Livewire\Component;
use Livewire\WithPagination;

class ServiciosIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function deleteConfirmed()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        $servicio = Servicio::findOrFail($this->confirmDeleteId);
        $servicio->update(['active' => 0]);
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.servicios-index', [
            'servicios' => Servicio::orderBy('name')->paginate(5),
        ]);
    }
}
