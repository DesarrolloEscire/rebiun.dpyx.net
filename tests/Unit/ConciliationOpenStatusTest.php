<?php

namespace Tests\Unit;

use App\Models\StatusResolution;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConciliationOpenStatusTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp(): void{
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_conciliation_open_status()
    {
        // $statusResolution1 = StatusResolution::factory()->opened()->create();
        // dd($statusResolution1);

    }
}
