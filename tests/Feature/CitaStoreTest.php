<?php

namespace Tests\Feature;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CitaStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_rejects_overlapping_time_slot(): void
    {
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'cliente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = Usuario::create([
            'name' => 'Cliente',
            'last_name' => 'Test',
            'phone' => '555555555',
            'email' => 'cliente@test.local',
            'password' => bcrypt('password'),
            'role_id' => 2,
            'active' => 1,
        ]);

        $cliente = Cliente::create([
            'user_id' => $user->id,
            'birth_date' => '2000-01-01',
        ]);

        $servicio = Servicio::create([
            'name' => 'Corte',
            'description' => 'Servicio',
            'duration_minutes' => 60,
            'price' => 200,
            'active' => 1,
        ]);

        Cita::create([
            'client_id' => $cliente->id,
            'service_id' => $servicio->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => CitaStatus::CONFIRMADA,
        ]);

        $this->actingAs($user)
            ->post(route('cliente.citas.store'), [
                'servicio_id' => $servicio->id,
                'fecha' => now()->addDay()->toDateString(),
                'hora_inicio' => '10:30',
                'acepta_privacidad' => '1',
            ])
            ->assertSessionHasErrors('hora_inicio');

        $this->assertDatabaseCount('appointments', 1);
    }
}
