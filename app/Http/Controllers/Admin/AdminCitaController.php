<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Empleado;
use Illuminate\Http\Request;

class AdminCitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['client', 'service', 'employee'])->get();
        $empleados = Empleado::where('active', 1)->get();

        return view('admin.citas.index', compact('citas', 'empleados'));
    }

    public function confirmar(Cita $cita)
    {
        $cita->update([
            'status' => 'confirmada',
            'notes' => 'Cita confirmada por el administrador',
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'confirmada',
            'user_id' => auth()->id(),
            'change_date' => now()
        ]);

        return back()->with('success', 'Cita confirmada correctamente');
    }


    public function cancelar(Request $request, Cita $cita)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        $cita->update([
            'status' => 'cancelada',
            'notes' => $request->observaciones
                ?? 'Cancelada por el administrador',
        ]);

        CitaEstado::create([
            'appointment_id' => $cita->id,
            'status' => 'cancelada',
            'user_id' => auth()->id(),
            'change_date' => now()
        ]);

        return back()->with('success', 'Cita cancelada correctamente');
    }


    public function asignarEmpleado(Request $request, Cita $cita)
    {
        if ($cita->status !== 'confirmada') {
            return back()->with('error', 'Solo se puede asignar empleado a citas confirmadas');
        }

        if ($cita->employee_id) {
            return back()->with('error', 'Esta cita ya tiene un empleado asignado');
        }

        $request->validate([
            'empleado_id' => 'required|exists:employees,id',
        ]);

        $cita->update([
            'employee_id' => $request->empleado_id,
        ]);

        return back()->with('success', 'Empleado asignado correctamente');
    }
}
