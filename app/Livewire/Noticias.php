<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticia;

class Noticias extends Component
{
    public function render()
    {
        $query = Noticia::where('published', 1)
            ->whereDate('publication_date', '<=', today())
            ->orderBy('publication_date', 'desc');

        $totalNoticias = (clone $query)->count();

        return view('livewire.noticias', [
            'noticias' => (clone $query)
                ->take(3)
                ->get(),
            'noticiasHasMore' => $totalNoticias > 3,
        ]);
    }
}
