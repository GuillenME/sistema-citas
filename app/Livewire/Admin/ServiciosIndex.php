<?php

namespace App\Livewire\Admin;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\Servicio;
use Livewire\Component;
use Livewire\WithPagination;

class ServiciosIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmActionId = null;
    public $confirmActionType = null;

    public function toggleActivo(int $id)
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->active) {
            $hasPromotions = $servicio->promociones()->exists();

            if ($hasPromotions) {
                session()->flash('error', 'No se puede desactivar: el servicio está asociado a promociones. Primero quítalo de esas promociones.');
                return;
            }

            $hasPendingAppointments = Cita::where('service_id', $servicio->id)
                ->whereIn('status', [
                    'pendiente',
                    CitaStatus::PENDIENTE_ANTICIPO,
                    CitaStatus::CONFIRMADA,
                ])
                ->whereDate('date', '>=', now()->toDateString())
                ->exists();

            if ($hasPendingAppointments) {
                session()->flash('error', 'No se puede desactivar: el servicio tiene citas pendientes o confirmadas.');
                return;
            }

            $servicio->update(['active' => false]);
            session()->flash('success', 'Servicio desactivado correctamente.');
            return;
        }

        $servicio->update(['active' => true]);
        session()->flash('success', 'Servicio activado correctamente.');
    }

    public function confirmAction(int $id, string $action)
    {
        if ($action !== 'delete') {
            return;
        }

        $this->confirmActionId = $id;
        $this->confirmActionType = $action;
    }

    public function cancelAction()
    {
        $this->confirmActionId = null;
        $this->confirmActionType = null;
    }

    public function executeConfirmedAction()
    {
        if (!$this->confirmActionId || !$this->confirmActionType) {
            return;
        }

        $servicio = Servicio::findOrFail($this->confirmActionId);

        if ($this->confirmActionType === 'delete') {
            $hasPromotions = $servicio->promociones()->exists();

            if ($hasPromotions) {
                session()->flash('error', 'No se puede eliminar: el servicio está asociado a promociones. Primero quítalo de esas promociones o desactívalo.');
                $this->cancelAction();
                return;
            }

            $hasAppointments = Cita::where('service_id', $servicio->id)->exists();

            if ($hasAppointments) {
                session()->flash('error', 'No se puede eliminar: el servicio tiene citas asociadas. Usa desactivar.');
                $this->cancelAction();
                return;
            }

            $servicio->delete();
            session()->flash('success', 'Servicio eliminado correctamente.');
        }

        $this->cancelAction();
    }

    public function render()
    {
        return view('livewire.admin.servicios-index', [
            'servicios' => Servicio::orderBy('name')->paginate(5),
        ]);
    }
}
