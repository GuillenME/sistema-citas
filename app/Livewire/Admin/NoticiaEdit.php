<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Noticia;
use Illuminate\Support\Str;

class NoticiaEdit extends Component
{
    use WithFileUploads;

    public Noticia $noticia;

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

    public function mount(Noticia $noticia)
    {
        $this->noticia = $noticia;
        $this->titulo = $noticia->title;
        $this->contenido = $noticia->content;
        $this->fecha_publicacion = $noticia->publication_date;
        $this->publicada = (bool) $noticia->published;
    }

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function actualizar()
    {
        if ($this->image) {
            $this->noticia->image = $this->image->store('noticias', 'public');
        }

        $this->noticia->update([
            'title' => $this->titulo,
            'slug' => $this->uniqueSlug($this->titulo, $this->noticia->id),
            'content' => $this->contenido,
            'publication_date' => $this->fecha_publicacion,
            'published' => $this->publicada ? 1 : 0,
            'image' => $this->noticia->image,
        ]);

        session()->flash('success', 'Noticia actualizada correctamente');

        return redirect()->route('admin.noticias.index');
    }

    private function uniqueSlug($title, $ignoreId)
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = Str::random(8);
        }
        $slug = $base;
        $i = 1;

        while (Noticia::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.admin.noticia-edit');
    }
}
