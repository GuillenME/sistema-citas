<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Cliente;

class ClientesIndex extends Component
{
    public $clientes = [];
    public $clienteSeleccionado;
    public $accion; // activar | desactivar
    public $confirmar = false;

    public function mount()
    {
        $this->cargarClientes();
    }

    public function cargarClientes()
    {
        $this->clientes = Cliente::with('user')->get();
    }

    public function confirmarAccion(Cliente $cliente, $accion)
    {
        $this->clienteSeleccionado = $cliente;
        $this->accion = $accion;
        $this->confirmar = true;
    }

    public function ejecutarAccion()
    {
        $this->clienteSeleccionado->user->update([
            'active' => $this->accion === 'activar' ? 1 : 0
        ]);

        $this->confirmar = false;
        $this->cargarClientes();
    }

    public function render()
    {
        return view('livewire.admin.clientes-index');
    }
}
