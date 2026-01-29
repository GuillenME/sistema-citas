<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LogoutModal extends Component
{
    public $mostrar = false;

    protected $listeners = ['abrirLogout' => 'abrir'];

    public function abrir()
    {
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
