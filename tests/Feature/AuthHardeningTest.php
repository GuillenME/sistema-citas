<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $this->seedRole(2);

        Usuario::create([
            'name' => 'Cliente',
            'last_name' => 'Throttle',
            'phone' => '5555555555',
            'email' => 'throttle@test.local',
            'password' => bcrypt('correct-password'),
            'role_id' => 2,
            'active' => 1,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'throttle@test.local',
                'password' => 'bad-password',
            ])->assertSessionHasErrors('email');
        }

        $response = $this->post('/login', [
            'email' => 'throttle@test.local',
            'password' => 'bad-password',
        ])->assertSessionHasErrors('email');

        $error = session('errors')->first('email');
        $this->assertStringContainsString('Demasiados intentos', $error);
    }

    public function test_inactive_user_cannot_login_even_with_correct_password(): void
    {
        $this->seedRole(2);

        Usuario::create([
            'name' => 'Cliente',
            'last_name' => 'Inactivo',
            'phone' => '5555555554',
            'email' => 'inactive@test.local',
            'password' => bcrypt('correct-password'),
            'role_id' => 2,
            'active' => 0,
        ]);

        $this->post('/login', [
            'email' => 'inactive@test.local',
            'password' => 'correct-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_reset_request_returns_same_success_message_for_existing_or_missing_email(): void
    {
        $this->seedRole(2);

        Usuario::create([
            'name' => 'Cliente',
            'last_name' => 'Reset',
            'phone' => '5555555553',
            'email' => 'reset@test.local',
            'password' => bcrypt('password123'),
            'role_id' => 2,
            'active' => 1,
        ]);

        $expected =
            'Si el correo existe en el sistema, te enviaremos un enlace para restablecer tu contraseña.';

        $this->post(route('password.email'), [
            'email' => 'reset@test.local',
        ])->assertSessionHasNoErrors()
            ->assertSessionHas('success', $expected);

        $this->post(route('password.email'), [
            'email' => 'no-existe@test.local',
        ])->assertSessionHasNoErrors()
            ->assertSessionHas('success', $expected);
    }

    public function test_login_throttle_allows_new_attempt_after_decay_window(): void
    {
        $this->seedRole(2);

        $user = Usuario::create([
            'name' => 'Cliente',
            'last_name' => 'Decay',
            'phone' => '5555555552',
            'email' => 'decay@test.local',
            'password' => bcrypt('correct-password'),
            'role_id' => 2,
            'active' => 1,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'decay@test.local',
                'password' => 'bad-password',
            ])->assertSessionHasErrors('email');
        }

        $this->post('/login', [
            'email' => 'decay@test.local',
            'password' => 'bad-password',
        ])->assertSessionHasErrors('email');

        $this->travel(61)->seconds();

        $this->post('/login', [
            'email' => 'decay@test.local',
            'password' => 'correct-password',
        ])->assertRedirect('/redirect');

        $this->assertAuthenticatedAs($user);
    }

    private function seedRole(int $roleId): void
    {
        DB::table('roles')->insertOrIgnore([
            'id' => $roleId,
            'name' => 'rol_' . $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
