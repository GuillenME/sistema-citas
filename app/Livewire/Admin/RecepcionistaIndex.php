<?php

namespace App\Livewire\Admin;

use App\Models\RecepcionistaReminder;
use App\Models\Usuario;
use Livewire\Component;
use Livewire\WithPagination;

class RecepcionistaIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;
    public $showReminderCreateModal = false;
    public $showReminderListModal = false;
    public $newReminder = '';

    public function toggleActivo(Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 403);

        $usuario->update([
            'active' => !$usuario->active,
        ]);
    }

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

        $usuario = Usuario::findOrFail($this->confirmDeleteId);
        abort_if($usuario->role_id !== 3, 403);

        $usuario->delete();
        $this->confirmDeleteId = null;
    }

    public function openReminderCreateModal()
    {
        $this->newReminder = '';
        $this->showReminderCreateModal = true;
    }

    public function closeReminderCreateModal()
    {
        $this->showReminderCreateModal = false;
    }

    public function openReminderListModal()
    {
        $this->showReminderListModal = true;
    }

    public function closeReminderListModal()
    {
        $this->showReminderListModal = false;
    }

    public function saveReminder()
    {
        $this->validate([
            'newReminder' => 'required|string|max:500',
        ]);

        RecepcionistaReminder::create([
            'message' => trim((string) $this->newReminder),
            'is_active' => true,
        ]);

        $this->newReminder = '';
        $this->showReminderCreateModal = false;
        session()->flash('success', 'Recordatorio creado correctamente.');
    }

    public function toggleReminderStatus($id)
    {
        $reminder = RecepcionistaReminder::findOrFail($id);

        $reminder->update([
            'is_active' => !$reminder->is_active,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.recepcionista-index', [
            'recepcionistas' => Usuario::where('role_id', 3)->orderBy('id')->paginate(5),
            'recordatorios' => RecepcionistaReminder::orderByDesc('created_at')->limit(20)->get(),
        ]);
    }
}
