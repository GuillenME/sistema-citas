<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\LogoutModal;
use App\Models\HomeSetting;
use Livewire\Component;

class Navbar extends Component
{
    public function abrirLogout()
    {
        $this->dispatch('abrirLogout')->to(LogoutModal::class);
    }

    public function render()
    {
        return view('livewire.admin.navbar', [
            'homeSetting' => HomeSetting::first(),
        ]);
    }
}
