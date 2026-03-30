<?php

namespace App\Livewire\Admin;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\Empleado;
use App\Notifications\CitaClienteNotification;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class EmpleadoIndex extends Component
{
    use WithPagination;

    protected AppointmentAvailabilityService $availability;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;
    public $confirmToggleId = null;
    public $confirmReassignId = null;
    public $reassignMessage = null;
    public $reassignType = 'success';
    public $upcomingAppointmentsCount = 0;
    public $upcomingAppointmentsLabel = '';
    public $upcomingAppointments = [];
    public $showUpcomingAppointmentsModal = false;
    public $toggleUpcomingAppointmentsCount = 0;
    public $toggleUpcomingAppointmentsLabel = '';

    public function boot(AppointmentAvailabilityService $availability): void
    {
        $this->availability = $availability;
    }

    public function toggle($id)
    {
        $empleado = Empleado::findOrFail($id);

        if ($empleado->active) {
            $this->confirmToggle($id);
            return;
        }

        $empleado->update([
            'active' => true,
        ]);

        $this->setReassignResult('success', 'Empleado activado correctamente.');
    }

    public function confirmToggle($id): void
    {
        $empleado = Empleado::findOrFail($id);

        $this->reassignMessage = null;
        $this->reassignType = 'success';
        $this->confirmToggleId = $id;
        $this->toggleUpcomingAppointmentsLabel = $empleado->name;
        $this->toggleUpcomingAppointmentsCount = $this->upcomingAppointmentsQuery($empleado)->count();
    }

    public function cancelToggle(): void
    {
        $this->confirmToggleId = null;
        $this->toggleUpcomingAppointmentsCount = 0;
        $this->toggleUpcomingAppointmentsLabel = '';
    }

    public function toggleConfirmed(): void
    {
        if (!$this->confirmToggleId) {
            return;
        }

        $empleado = Empleado::findOrFail($this->confirmToggleId);

        if ($this->upcomingAppointmentsQuery($empleado)->exists()) {
            $this->setReassignResult(
                'error',
                'No se puede desactivar este empleado porque aun tiene citas proximas activas. Reasignalas o dalo de baja con el flujo correspondiente.'
            );
            return;
        }

        $empleado->update([
            'active' => false,
        ]);

        $this->setReassignResult('success', 'Empleado desactivado correctamente.');
        $this->cancelToggle();
    }

    public function confirmDelete($id)
    {
        $empleado = Empleado::findOrFail($id);

        if ($empleado->trashed()) {
            return;
        }

        $this->reassignMessage = null;
        $this->reassignType = 'success';

        $upcomingAppointments = $this->upcomingAppointmentsQuery($empleado)
            ->with(['service:id,name', 'client.user:id,name'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $this->confirmDeleteId = $id;
        $this->upcomingAppointmentsCount = $upcomingAppointments->count();
        $this->upcomingAppointmentsLabel = $empleado->name;
        $this->upcomingAppointments = $upcomingAppointments
            ->map(function (Cita $cita) {
                return [
                    'id' => $cita->id,
                    'date' => optional($cita->date)->format('d/m/Y'),
                    'time' => trim(
                        Carbon::parse($cita->getRawOriginal('start_time'))->format('H:i')
                            . ' - ' .
                            Carbon::parse($cita->getRawOriginal('end_time'))->format('H:i')
                    ),
                    'service' => $cita->service?->name ?? 'Servicio no disponible',
                    'client' => $cita->client?->user?->name ?? 'Cliente sin nombre',
                ];
            })
            ->all();
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
        $this->upcomingAppointmentsCount = 0;
        $this->upcomingAppointmentsLabel = '';
        $this->upcomingAppointments = [];
        $this->showUpcomingAppointmentsModal = false;
    }

    public function openUpcomingAppointmentsModal()
    {
        if (empty($this->upcomingAppointments)) {
            return;
        }

        $this->showUpcomingAppointmentsModal = true;
    }

    public function closeUpcomingAppointmentsModal()
    {
        $this->showUpcomingAppointmentsModal = false;
    }

    public function confirmReassign($id)
    {
        $this->reassignMessage = null;
        $this->reassignType = 'success';
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

        if ($this->upcomingAppointmentsQuery($empleado)->exists()) {
            $this->setReassignResult('error', 'Este empleado aún tiene citas próximas. Reasígnalas o déjalas sin asignar antes de darlo de baja.');
            return;
        }

        $empleado->delete();

        $this->setReassignResult('success', 'Empleado dado de baja correctamente. Su historial y citas se conservaron.');
        $this->cancelDelete();
    }

    public function deleteAndUnassignUpcomingAppointments()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        $empleado = Empleado::findOrFail($this->confirmDeleteId);
        $citas = $this->upcomingAppointmentsQuery($empleado)->get();

        DB::transaction(function () use ($empleado, $citas) {
            foreach ($citas as $cita) {
                $notaActual = trim((string) ($cita->notes ?? ''));
                $notaNueva = 'Empleado desasignado por baja administrativa.';

                $cita->update([
                    'employee_id' => null,
                    'notes' => $notaActual === '' ? $notaNueva : $notaActual . ' | ' . $notaNueva,
                ]);
            }

            $empleado->delete();
        });

        $this->setReassignResult(
            'success',
            $citas->count() . ' cita(s) próximas quedaron sin empleado asignado y el empleado fue dado de baja.'
        );

        $this->cancelDelete();
    }

    public function deleteAndReassignUpcomingAppointments()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        $empleado = Empleado::with(['servicios:id', 'schedules'])->findOrFail($this->confirmDeleteId);
        $citas = $this->upcomingAppointmentsQuery($empleado)
            ->with(['service.empleados.schedules', 'service.empleados.breaks', 'client.user', 'employee'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        if ($citas->isEmpty()) {
            $this->deleteConfirmed();
            return;
        }

        $candidateAppointments = Cita::query()
            ->whereDate('date', '>=', today()->toDateString())
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
            ->whereNotNull('employee_id')
            ->where('employee_id', '!=', $empleado->id)
            ->get()
            ->groupBy(fn(Cita $cita) => $cita->employee_id . '|' . optional($cita->date)->toDateString());

        $bloquesOcupados = [];
        foreach ($candidateAppointments as $key => $appointments) {
            $bloquesOcupados[$key] = $appointments
                ->map(fn(Cita $cita) => [
                    'inicio' => Carbon::parse($cita->getRawOriginal('start_time'))->format('H:i'),
                    'fin' => Carbon::parse($cita->getRawOriginal('end_time'))->format('H:i'),
                ])
                ->values()
                ->all();
        }

        $reasignaciones = [];
        $sinDestino = [];

        foreach ($citas as $cita) {
            if (!$cita->service) {
                $sinDestino[] = $cita->id;
                continue;
            }

            $fecha = Carbon::parse($cita->date);
            $horaInicio = Carbon::parse($cita->getRawOriginal('start_time'))->format('H:i');
            $horaFin = Carbon::parse($cita->getRawOriginal('end_time'))->format('H:i');

            $candidatos = $cita->service->empleados
                ->filter(fn(Empleado $candidato) => $candidato->active && $candidato->id !== $empleado->id)
                ->filter(function (Empleado $candidato) use ($fecha, $horaInicio, $horaFin, $bloquesOcupados) {
                    if (!$this->availability->employeeCoversRange($candidato, $fecha, $horaInicio, $horaFin)) {
                        return false;
                    }

                    $key = $candidato->id . '|' . $fecha->toDateString();

                    return !$this->availability->hasOverlap($bloquesOcupados[$key] ?? [], $horaInicio, $horaFin);
                })
                ->sortBy(function (Empleado $candidato) use ($fecha, $bloquesOcupados) {
                    $key = $candidato->id . '|' . $fecha->toDateString();
                    return count($bloquesOcupados[$key] ?? []);
                })
                ->values();

            if ($candidatos->isEmpty()) {
                $sinDestino[] = $cita->id;
                continue;
            }

            $destino = $candidatos->first();
            $key = $destino->id . '|' . $fecha->toDateString();
            $bloquesOcupados[$key] ??= [];
            $bloquesOcupados[$key][] = [
                'inicio' => $horaInicio,
                'fin' => $horaFin,
            ];

            $reasignaciones[] = [
                'cita_id' => $cita->id,
                'empleado_destino_id' => $destino->id,
            ];
        }

        if (!empty($sinDestino)) {
            $this->setReassignResult(
                'error',
                'No se pudo dar de baja. ' . count($sinDestino) . ' cita(s) próximas no tienen un empleado compatible para reasignación automática.'
            );
            return;
        }

        DB::transaction(function () use ($empleado, $reasignaciones) {
            foreach ($reasignaciones as $item) {
                Cita::where('id', $item['cita_id'])
                    ->update(['employee_id' => $item['empleado_destino_id']]);
            }

            $empleado->delete();
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
            count($reasignaciones) . ' cita(s) próximas fueron reasignadas y el empleado fue dado de baja.'
        );

        $this->cancelDelete();
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

                    if (!$this->availability->employeeCoversRange($empleado, Carbon::parse($hoy), $horaInicio, $horaFin)) {
                        return false;
                    }

                    return !$this->availability->hasOverlap($bloquesOcupados[$empleado->id] ?? [], $horaInicio, $horaFin);
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

    private function setReassignResult(string $type, string $message): void
    {
        $this->reassignType = $type;
        $this->reassignMessage = $message;
    }

    private function upcomingAppointmentsQuery(Empleado $empleado)
    {
        return Cita::query()
            ->where('employee_id', $empleado->id)
            ->whereDate('date', '>=', today()->toDateString())
            ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO]);
    }

    public function render()
    {
        return view('livewire.admin.empleado-index', [
            'empleados' => Empleado::with('servicios')->orderBy('id')->paginate(5),
        ]);
    }
}
