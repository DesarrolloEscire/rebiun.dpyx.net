<?php

namespace Database\Factories;

use App\Models\Conciliation;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConciliationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conciliation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'check_list' => [],
            'evaluators_ids' => "1,2",
            "repository_id" => Repository::get()->random()->id,
        ];
    }
}
