<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Header extends Component
{
    public string $title = '';

    protected $listeners = ['abrirLogout' => '$refresh'];

    public function abrirLogout()
    {
        $this->dispatch('abrirLogout');
    }

    public function render()
    {
        return view('livewire.admin.header');
    }
}
