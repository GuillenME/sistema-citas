<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class AdminClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('user')->get();

        return view('admin.clientes.index', compact('clientes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function desactivar(Cliente $cliente)
    {
        $cliente->user->update([
            'active' => 0
        ]);

        return back()->with('success', 'Cuenta desactivada correctamente');
    }

    public function activar(Cliente $cliente)
    {
        $cliente->user->update([
            'active' => 1
        ]);

        return back()->with('success', 'Cuenta activada correctamente');
    }
}
