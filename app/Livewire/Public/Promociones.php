<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Promocion;

class Promociones extends Component
{
    public function render()
    {
        $promocionesActivas = Promocion::where('published', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();

        $promocionesProximas = Promocion::where('published', 1)
            ->whereDate('start_date', '>', today())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('livewire.public.promociones', compact('promocionesActivas', 'promocionesProximas'));
    }
}

