<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticia;

class Noticias extends Component
{
    public function render()
    {
        return view('livewire.noticias', [
            'noticias' => Noticia::where('published', 1)
                ->orderBy('publication_date', 'desc')
                ->take(3)
                ->get(),
        ]);
    }
}
