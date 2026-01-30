<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class LogoutModal extends Component
{
    public bool $mostrar = false;

    #[On('abrirLogout')]
    public function abrir()
    {
        logger('MODAL RECIBIÓ EVENTO');
        $this->mostrar = true;
    }

    public function cerrar()
    {
        $this->mostrar = false;
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.admin.logout-modal');
    }
}
