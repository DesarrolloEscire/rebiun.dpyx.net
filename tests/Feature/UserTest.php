<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("migrate:fresh");
        $this->seed();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_administrator_can_create_user()
    {

        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $response = $this->post('/users', [
            'name' => 'Raul Hernandez',
            'email' => 'raul@mail.com',
            'password' => "¡R4ul!",
            'repository_name' => 'repositorio de raul',
            'role' => 'usuario'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Raul Hernandez'
        ]);

        $response->assertStatus(302);
    }

    public function test_anonymous_user_can_request_registration()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('register', [
            'name' => 'Raul',
            'password' => 'password',
            'password_repeated' => 'password',
            'email' => 'user@mail.com',
            'repository_name' => 'repository name'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'Raul',
            'email' => 'user@mail.com',
        ]);
    }
}
