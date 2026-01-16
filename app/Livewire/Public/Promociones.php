<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Promocion;

class Promociones extends Component
{
    public function render()
    {
        return view('components.public.promociones', [
            'promociones' => Promocion::where('publicada', 1)
                ->whereDate('fecha_inicio', '<=', now())
                ->whereDate('fecha_fin', '>=', now())
                ->orderBy('fecha_fin')
                ->take(3)
                ->get(), 
        ]);
    }
}
