<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithPagination;

class ClientesIndex extends Component
{
    use WithPagination;

    public $clientes = [];
    public $clienteSeleccionado;
    public $accion; // activar | desactivar
    public $confirmar = false;
    protected $paginationTheme = 'simple-bootstrap';
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
        return view('livewire.admin.clientes-index', ['items' => Cliente::orderBy('id')->paginate(5)]);
    }
}
