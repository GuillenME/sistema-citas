<?php

namespace App\Livewire\Admin;

use App\Models\Empleado;
use App\Models\EmployeeBreak;
use App\Models\Servicio;
use Carbon\Carbon;
use Livewire\Component;

class EmpleadoEdit extends Component
{
    public Empleado $empleado;
    public $nombre, $telefono;
    public $serviciosSeleccionados = [];
    public $horarios = [];
    public $descansos = [];

    protected $messages = [
        'telefono.required' => 'El telefono es obligatorio.',
        'telefono.digits' => 'El telefono debe tener exactamente 10 digitos numericos.',
    ];

    public function mount(Empleado $empleado)
    {
        $this->empleado = $empleado;
        $this->nombre = $empleado->name;
        $this->telefono = $empleado->phone;
        $this->serviciosSeleccionados = $empleado->servicios()->pluck('services.id')->toArray();
        $this->horarios = $this->buildDefaultSchedules();

        foreach ($empleado->schedules as $schedule) {
            $day = (int) $schedule->day_of_week;
            if ($day < 1 || $day > 6) {
                continue;
            }

            $index = $day - 1;
            $this->horarios[$index]['start_time'] = Carbon::parse($schedule->start_time)->format('H:i');
            $this->horarios[$index]['end_time'] = Carbon::parse($schedule->end_time)->format('H:i');
            $this->horarios[$index]['enabled'] = true;
        }

        $this->descansos = $empleado->breaks()
            ->whereDate('date', '>=', today()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function (EmployeeBreak $break) {
                return [
                    'date' => optional($break->date)->format('Y-m-d'),
                    'start_time' => $break->start_time ? Carbon::parse($break->start_time)->format('H:i') : '',
                    'end_time' => $break->end_time ? Carbon::parse($break->end_time)->format('H:i') : '',
                    'is_all_day' => (bool) $break->is_all_day,
                    'reason' => (string) ($break->reason ?? ''),
                ];
            })
            ->values()
            ->all();
    }

    public function toggleLabora($index): void
    {
        if (!isset($this->horarios[$index])) {
            return;
        }

        $enabled = (bool) ($this->horarios[$index]['enabled'] ?? false);
        $this->horarios[$index]['enabled'] = !$enabled;

        if ($this->horarios[$index]['enabled']) {
            $dayOfWeek = (int) ($this->horarios[$index]['day_of_week'] ?? 0);
            $this->horarios[$index]['start_time'] = $this->horarios[$index]['start_time'] ?: '09:00';
            $this->horarios[$index]['end_time'] = $this->horarios[$index]['end_time'] ?: ($dayOfWeek === 6 ? '16:00' : '18:00');
        }
    }

    public function updatedServiciosSeleccionados(): void
    {
        $selected = collect($this->serviciosSeleccionados)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($selected->count() > 5) {
            $selected = $selected->slice(0, 5)->values();
        }

        $this->serviciosSeleccionados = $selected->all();
    }

    public function toggleServicio(int $serviceId): void
    {
        $selected = collect($this->serviciosSeleccionados)
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($selected->contains($serviceId)) {
            $this->serviciosSeleccionados = $selected
                ->reject(fn ($id) => $id === $serviceId)
                ->values()
                ->all();
            return;
        }

        if ($selected->count() >= 5) {
            return;
        }

        $this->serviciosSeleccionados = $selected
            ->push($serviceId)
            ->unique()
            ->values()
            ->all();
    }

    public function addDescanso(): void
    {
        $this->descansos[] = [
            'date' => today()->toDateString(),
            'start_time' => '15:00',
            'end_time' => '16:00',
            'is_all_day' => false,
            'reason' => '',
        ];
    }

    public function removeDescanso(int $index): void
    {
        if (!isset($this->descansos[$index])) {
            return;
        }

        unset($this->descansos[$index]);
        $this->descansos = array_values($this->descansos);
    }

    private function validarHorarios(): bool
    {
        $activos = 0;

        foreach ($this->horarios as $i => $horario) {
            $enabled = (bool) ($horario['enabled'] ?? false);
            if (!$enabled) {
                continue;
            }

            $activos++;
            $inicio = (string) ($horario['start_time'] ?? '');
            $fin = (string) ($horario['end_time'] ?? '');

            if (!preg_match('/^\d{2}:\d{2}$/', $inicio)) {
                $this->addError("horarios.$i.start_time", 'La hora de entrada no tiene formato valido.');
                return false;
            }

            if (!preg_match('/^\d{2}:\d{2}$/', $fin)) {
                $this->addError("horarios.$i.end_time", 'La hora de salida no tiene formato valido.');
                return false;
            }

            if ($inicio >= $fin) {
                $this->addError("horarios.$i.start_time", 'La hora de entrada debe ser menor a la de salida.');
                return false;
            }
        }

        if ($activos === 0) {
            $this->addError('horarios', 'Debes activar al menos un dia laboral.');
            return false;
        }

        return true;
    }

    private function validarDescansos(): bool
    {
        foreach ($this->descansos as $i => $descanso) {
            $fecha = (string) ($descanso['date'] ?? '');
            $inicio = (string) ($descanso['start_time'] ?? '');
            $fin = (string) ($descanso['end_time'] ?? '');
            $todoDia = (bool) ($descanso['is_all_day'] ?? false);

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                $this->addError("descansos.$i.date", 'La fecha del descanso no es valida.');
                return false;
            }

            if ($todoDia) {
                continue;
            }

            if (!preg_match('/^\d{2}:\d{2}$/', $inicio)) {
                $this->addError("descansos.$i.start_time", 'La hora inicial del descanso no es valida.');
                return false;
            }

            if (!preg_match('/^\d{2}:\d{2}$/', $fin)) {
                $this->addError("descansos.$i.end_time", 'La hora final del descanso no es valida.');
                return false;
            }

            if ($inicio >= $fin) {
                $this->addError("descansos.$i.start_time", 'El descanso debe iniciar antes de terminar.');
                return false;
            }
        }

        return true;
    }

    public function actualizar()
    {
        $this->validate([
            'nombre' => 'required|min:3',
            'telefono' => 'required|digits:10',
            'serviciosSeleccionados' => 'required|array|min:1|max:5',
            'serviciosSeleccionados.*' => 'exists:services,id',
            'horarios' => 'required|array|size:6',
            'descansos' => 'nullable|array',
        ]);

        if (!$this->validarHorarios() || !$this->validarDescansos()) {
            return;
        }

        $this->empleado->update([
            'name' => $this->nombre,
            'phone' => $this->telefono,
        ]);

        $this->empleado->servicios()->sync($this->serviciosSeleccionados);
        $this->empleado->schedules()->delete();

        foreach ($this->horarios as $horario) {
            if (!(bool) ($horario['enabled'] ?? false)) {
                continue;
            }

            $this->empleado->schedules()->create([
                'day_of_week' => (int) $horario['day_of_week'],
                'start_time' => $horario['start_time'],
                'end_time' => $horario['end_time'],
            ]);
        }

        $this->empleado->breaks()
            ->whereDate('date', '>=', today()->toDateString())
            ->delete();

        foreach ($this->descansos as $descanso) {
            $this->empleado->breaks()->create([
                'date' => $descanso['date'],
                'start_time' => (bool) ($descanso['is_all_day'] ?? false) ? null : ($descanso['start_time'] ?: null),
                'end_time' => (bool) ($descanso['is_all_day'] ?? false) ? null : ($descanso['end_time'] ?: null),
                'is_all_day' => (bool) ($descanso['is_all_day'] ?? false),
                'reason' => trim((string) ($descanso['reason'] ?? '')) ?: null,
            ]);
        }

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado actualizado con horarios y descansos');
    }

    public function render()
    {
        return view('livewire.admin.empleado-edit', [
            'servicios' => Servicio::orderBy('name')->get()
        ]);
    }

    private function buildDefaultSchedules(): array
    {
        return collect(range(1, 6))->map(function ($day) {
            return [
                'day_of_week' => $day,
                'start_time' => '09:00',
                'end_time' => $day === 6 ? '16:00' : '18:00',
                'enabled' => false,
            ];
        })->all();
    }
}
