<?php

namespace Tests\Unit;

use App\Models\Repository;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_repository_can_has_historicals()
    {
        $role = Role::create([
            'name' => Role::USER_ROLE
        ]);

        $repositoryUser = User::factory()->create()->assignRole($role);

        $repository = Repository::factory()->create();
        $repository->historicals()
            ->create([
                'user_id' => $repositoryUser->id,
                'action' => "El repositorio cambió de status a: $repository->status"
            ]);

        $this->assertTrue(true);
    }
}
