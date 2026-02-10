<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Livewire\Admin\LogoutModal;

class Header extends Component
{
    public string $title = '';

    public function abrirLogout()
    {
        $this->dispatch('abrirLogout')->to(LogoutModal::class);
    }

    public function render()
    {
        return view('livewire.admin.header');
    }
}

