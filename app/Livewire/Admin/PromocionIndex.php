<?php

namespace App\Livewire\Admin;

use App\Models\Promocion;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class PromocionIndex extends Component
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

        $promocion = Promocion::findOrFail($this->confirmDeleteId);

        if ($promocion->image) {
            Storage::disk('public')->delete($promocion->image);
        }

        $promocion->delete();
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.promocion-index', [
            'promociones' => Promocion::with('servicios')
                ->orderByDesc('id')
                ->paginate(5),
        ]);
    }
}
