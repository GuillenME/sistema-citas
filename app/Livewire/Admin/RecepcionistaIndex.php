<?php

namespace App\Livewire\Admin;

use App\Models\Usuario;
use Livewire\Component;
use Livewire\WithPagination;

class RecepcionistaIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;

    public function toggleActivo(Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 403);

        $usuario->update([
            'active' => !$usuario->active,
        ]);
    }

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

        $usuario = Usuario::findOrFail($this->confirmDeleteId);
        abort_if($usuario->role_id !== 3, 403);

        $usuario->delete();
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.recepcionista-index', [
            'recepcionistas' => Usuario::where('role_id', 3)->orderBy('id')->paginate(5),
        ]);
    }
}
