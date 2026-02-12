<?php

namespace Tests\Feature;

use App\Livewire\Admin\EmpleadoCreate;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmpleadoCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_services_are_limited_to_five(): void
    {
        $serviceIds = collect(range(1, 6))->map(function ($i) {
            return Servicio::create([
                'name' => 'Servicio ' . $i,
                'description' => 'Desc',
                'duration_minutes' => 30,
                'price' => 100,
                'active' => 1,
            ])->id;
        })->all();

        Livewire::test(EmpleadoCreate::class)
            ->set('serviciosSeleccionados', $serviceIds)
            ->assertSet('serviciosSeleccionados', array_slice($serviceIds, 0, 5));
    }

    public function test_guardar_persists_only_five_selected_services(): void
    {
        $serviceIds = collect(range(1, 6))->map(function ($i) {
            return Servicio::create([
                'name' => 'Servicio ' . $i,
                'description' => 'Desc',
                'duration_minutes' => 30,
                'price' => 100,
                'active' => 1,
            ])->id;
        })->all();

        Livewire::test(EmpleadoCreate::class)
            ->set('nombre', 'Empleado Test')
            ->set('telefono', '777777777')
            ->set('serviciosSeleccionados', $serviceIds)
            ->call('guardar')
            ->assertRedirect(route('admin.empleados.index'));

        $empleado = Empleado::query()->firstOrFail();

        $this->assertCount(5, $empleado->servicios()->pluck('services.id')->all());
    }
}
