<?php

namespace App\Livewire\Admin;

use App\Models\Empleado;
use App\Models\Servicio;
use Livewire\Component;

class EmpleadoCreate extends Component
{
    public $nombre, $telefono;
    public $serviciosSeleccionados = [];
    public $horarios = [];
    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'telefono' => 'required|digits:10',
        'serviciosSeleccionados' => 'required|array|min:1|max:5',
        'serviciosSeleccionados.*' => 'exists:services,id',
        'horarios' => 'required|array|size:6',
    ];

    protected $messages = [
        'telefono.required' => 'El telefono es obligatorio.',
        'telefono.digits' => 'El telefono debe tener exactamente 10 digitos numericos.',
    ];

    public function mount()
    {
        $this->horarios = $this->buildDefaultSchedules();
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

    public function guardar()
    {
        $this->validate();

        if (!$this->validarHorarios()) {
            return;
        }

        $empleado = Empleado::create([
            'name' => $this->nombre,
            'phone' => $this->telefono,
            'active' => true,
        ]);

        $empleado->servicios()->sync($this->serviciosSeleccionados);

        foreach ($this->horarios as $horario) {
            if (!(bool) ($horario['enabled'] ?? false)) {
                continue;
            }

            $empleado->schedules()->create([
                'day_of_week' => (int) $horario['day_of_week'],
                'start_time' => $horario['start_time'],
                'end_time' => $horario['end_time'],
            ]);
        }

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado creado con horarios correctamente');
    }

    public function abrirConfirmacion()
    {
        $this->validate();

        if (!$this->validarHorarios()) {
            return;
        }

        $this->confirmar = true;
    }

    public function render()
    {
        return view('livewire.admin.empleado-create', [
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
                'enabled' => true,
            ];
        })->all();
    }
}
