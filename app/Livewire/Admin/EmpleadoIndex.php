<?php

namespace App\Livewire\Admin;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\Empleado;
use App\Notifications\CitaClienteNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class EmpleadoIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;
    public $confirmReassignId = null;
    public $reassignMessage = null;
    public $reassignType = 'success';

    public function toggle($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'active' => !$empleado->active,
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function confirmReassign($id)
    {
        $this->confirmReassignId = $id;
    }

    public function cancelReassign()
    {
        $this->confirmReassignId = null;
    }

    public function deleteConfirmed()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        $empleado = Empleado::findOrFail($this->confirmDeleteId);
        $empleado->delete();
        $this->confirmDeleteId = null;
    }

    public function reassignTodayAppointments()
    {
        if (!$this->confirmReassignId) {
            return;
        }

        $hoy = today()->toDateString();

        $origen = Empleado::with(['servicios:id', 'schedules'])
            ->findOrFail($this->confirmReassignId);

        $citasOrigen = Cita::query()
            ->with('service')
            ->whereDate('date', $hoy)
            ->where('employee_id', $origen->id)
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
            ->orderBy('start_time')
            ->get();

        if ($citasOrigen->isEmpty()) {
            $this->setReassignResult('error', 'No hay citas de hoy para reasignar.');
            $this->confirmReassignId = null;
            return;
        }

        $candidatos = Empleado::query()
            ->where('active', 1)
            ->where('id', '!=', $origen->id)
            ->with(['servicios:id', 'schedules'])
            ->get();

        if ($candidatos->isEmpty()) {
            $this->setReassignResult('error', 'No hay empleados activos disponibles para reasignar.');
            $this->confirmReassignId = null;
            return;
        }

        $citasCandidatos = Cita::query()
            ->whereDate('date', $hoy)
            ->whereIn('employee_id', $candidatos->pluck('id'))
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
            ->get();

        $bloquesOcupados = [];
        foreach ($candidatos as $candidato) {
            $bloquesOcupados[$candidato->id] = [];
        }
        foreach ($citasCandidatos as $cita) {
            $bloquesOcupados[$cita->employee_id][] = [
                'inicio' => Carbon::parse($cita->getRawOriginal('start_time'))->format('H:i'),
                'fin' => Carbon::parse($cita->getRawOriginal('end_time'))->format('H:i'),
            ];
        }

        $reasignaciones = [];
        $sinDestino = 0;

        foreach ($citasOrigen as $cita) {
            if (!$cita->service) {
                $sinDestino++;
                continue;
            }

            $horaInicio = Carbon::parse($cita->getRawOriginal('start_time'))->format('H:i');
            $horaFin = Carbon::parse($cita->getRawOriginal('end_time'))->format('H:i');

            $elegibles = $candidatos
                ->filter(function ($empleado) use ($cita, $hoy, $horaInicio, $horaFin, $bloquesOcupados) {
                    if (!$empleado->servicios->contains('id', $cita->service_id)) {
                        return false;
                    }

                    if (!$this->employeeCoversRange($empleado, Carbon::parse($hoy), $horaInicio, $horaFin)) {
                        return false;
                    }

                    return !$this->hasOverlap($bloquesOcupados[$empleado->id] ?? [], $horaInicio, $horaFin);
                })
                ->sortBy(function ($empleado) use ($bloquesOcupados) {
                    return count($bloquesOcupados[$empleado->id] ?? []);
                })
                ->values();

            if ($elegibles->isEmpty()) {
                $sinDestino++;
                continue;
            }

            $destino = $elegibles->first();
            $reasignaciones[] = [
                'cita_id' => $cita->id,
                'empleado_destino_id' => $destino->id,
                'inicio' => $horaInicio,
                'fin' => $horaFin,
            ];

            $bloquesOcupados[$destino->id][] = ['inicio' => $horaInicio, 'fin' => $horaFin];
        }

        if (empty($reasignaciones)) {
            $this->setReassignResult('error', 'No se pudo reasignar ninguna cita automaticamente.');
            $this->confirmReassignId = null;
            return;
        }

        DB::transaction(function () use ($reasignaciones) {
            foreach ($reasignaciones as $item) {
                Cita::where('id', $item['cita_id'])
                    ->update(['employee_id' => $item['empleado_destino_id']]);
            }
        });

        $citasReasignadas = Cita::with(['client.user', 'service', 'employee'])
            ->whereIn('id', collect($reasignaciones)->pluck('cita_id'))
            ->get();

        foreach ($citasReasignadas as $citaReasignada) {
            $clienteUsuario = $citaReasignada->client?->user;
            if ($clienteUsuario) {
                $clienteUsuario->notify(
                    new CitaClienteNotification(
                        $citaReasignada,
                        CitaClienteNotification::REASIGNADA_DE_EMPLEADO
                    )
                );
            }
        }

        $this->setReassignResult(
            'success',
            count($reasignaciones) . ' cita(s) reasignadas automaticamente. '
                . ($sinDestino > 0 ? $sinDestino . ' sin destino compatible.' : '')
        );

        $this->confirmReassignId = null;
    }

    private function employeeCoversRange(Empleado $empleado, Carbon $fecha, string $horaInicio, string $horaFin): bool
    {
        $diaSemana = $fecha->dayOfWeek;

        return $empleado->schedules
            ->where('day_of_week', $diaSemana)
            ->contains(function ($horario) use ($horaInicio, $horaFin) {
                $inicio = Carbon::parse($horario->start_time)->format('H:i');
                $fin = Carbon::parse($horario->end_time)->format('H:i');
                return $horaInicio >= $inicio
                    && $horaFin <= $fin
                    && !$this->isInsideLunchBreak($horaInicio, $horaFin);
            });
    }

    private function hasOverlap(array $bloques, string $horaInicio, string $horaFin): bool
    {
        foreach ($bloques as $bloque) {
            if ($horaInicio < $bloque['fin'] && $horaFin > $bloque['inicio']) {
                return true;
            }
        }

        return false;
    }

    private function setReassignResult(string $type, string $message): void
    {
        $this->reassignType = $type;
        $this->reassignMessage = $message;
    }

    private function isInsideLunchBreak(string $horaInicio, string $horaFin): bool
    {
        $comidaInicio = (string) config('citas.horarios.comida_inicio', '15:00');
        $comidaFin = (string) config('citas.horarios.comida_fin', '16:00');

        if (!preg_match('/^\d{2}:\d{2}$/', $comidaInicio) || !preg_match('/^\d{2}:\d{2}$/', $comidaFin)) {
            return false;
        }

        if ($comidaInicio >= $comidaFin) {
            return false;
        }

        return $horaInicio < $comidaFin && $horaFin > $comidaInicio;
    }

    public function render()
    {
        return view('livewire.admin.empleado-index', [
            'empleados' => Empleado::with('servicios')->orderBy('id')->paginate(5),
        ]);
    }
}
