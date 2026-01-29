<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Promocion;

class PromocionIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public function render()
    {
        return view('livewire.admin.promocion-index', [
            'promociones' => Promocion::with('servicios')
                ->orderByDesc('id')
                ->paginate(5)
        ]);
    }
}
