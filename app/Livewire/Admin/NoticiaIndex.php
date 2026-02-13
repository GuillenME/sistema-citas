<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Noticia;
use Illuminate\Support\Facades\Storage;

class NoticiaIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;
    public $previewNoticia = null;
    public $showPreviewModal = false;


    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }
    public function preview($id)
{
    $this->previewNoticia = Noticia::findOrFail($id);
    $this->showPreviewModal = true;
}

public function closePreview()
{
    $this->previewNoticia = null;
    $this->showPreviewModal = false;
}

    public function deleteConfirmed()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        $noticia = Noticia::findOrFail($this->confirmDeleteId);

        if ($noticia->image) {
            Storage::disk('public')->delete($noticia->image);
        }

        $noticia->delete();
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.noticia-index', [
            'noticias' => Noticia::orderByDesc('publication_date')
                ->orderByDesc('id')
                ->paginate(5),
        ]);
    }
}
