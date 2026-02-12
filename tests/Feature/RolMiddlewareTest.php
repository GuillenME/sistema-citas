<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RolMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->middleware('rol:1')->get('/_test/rol-only', function () {
            return response('ok', 200);
        });
    }

    public function test_guest_is_forbidden_on_role_route(): void
    {
        $this->get('/_test/rol-only')->assertForbidden();
    }

    public function test_user_with_non_matching_role_is_forbidden(): void
    {
        $user = $this->createUserWithRole(2);

        $this->actingAs($user)
            ->get('/_test/rol-only')
            ->assertForbidden();
    }

    public function test_user_with_matching_role_can_access(): void
    {
        $user = $this->createUserWithRole(1);

        $this->actingAs($user)
            ->get('/_test/rol-only')
            ->assertOk()
            ->assertSee('ok');
    }

    private function createUserWithRole(int $roleId): Usuario
    {
        DB::table('roles')->insertOrIgnore([
            'id' => $roleId,
            'name' => 'rol_' . $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Usuario::create([
            'name' => 'Usuario ' . $roleId,
            'last_name' => 'Test',
            'phone' => '999999999',
            'email' => 'user' . $roleId . '@test.local',
            'password' => bcrypt('password'),
            'role_id' => $roleId,
            'active' => 1,
        ]);
    }
}
