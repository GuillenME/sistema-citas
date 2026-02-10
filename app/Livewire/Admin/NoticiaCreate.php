<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Noticia;
use Illuminate\Support\Str;

class NoticiaCreate extends Component
{
    use WithFileUploads;

    public $titulo;
    public $contenido;
    public $fecha_publicacion;
    public $publicada = false;
    public $image;

    public $confirmar = false;

    protected $rules = [
        'titulo' => 'required|min:3',
        'contenido' => 'required|min:10',
        'fecha_publicacion' => 'required|date',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->fecha_publicacion = now()->toDateString();
    }

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function guardar()
    {
        $path = $this->image
            ? $this->image->store('noticias', 'public')
            : null;

        Noticia::create([
            'title' => $this->titulo,
            'slug' => $this->uniqueSlug($this->titulo),
            'content' => $this->contenido,
            'image' => $path,
            'publication_date' => $this->fecha_publicacion,
            'published' => $this->publicada ? 1 : 0,
            'user_id' => auth()->id(),
        ]);

        session()->flash('success', 'Noticia creada correctamente');

        return redirect()->route('admin.noticias.index');
    }

    private function uniqueSlug($title)
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = Str::random(8);
        }
        $slug = $base;
        $i = 1;

        while (Noticia::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.admin.noticia-create');
    }
}
