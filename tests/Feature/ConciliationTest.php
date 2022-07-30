<?php

namespace Tests\Feature;

use App\Models\Conciliation;
use App\Models\Repository;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ConciliationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $repository = Repository::factory()->create();
        $conciliation = Conciliation::factory()->create();

        $adminUser = User::factory()->create();
        $adminUser->assignRole(
            Role::factory()->administrator()->create()
        );

        $this->actingAs($adminUser);

        $response = $this->post(
            route('status.store', [$repository->id])
        );

        $response->assertStatus(200);
    }
}
