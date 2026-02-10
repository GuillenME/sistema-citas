<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Promocion;

class Promociones extends Component
{
    public function render()
    {
        $promociones = Promocion::where('published', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('livewire.public.promociones', compact('promociones'));
    }
}

