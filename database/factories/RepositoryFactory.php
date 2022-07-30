<?php

namespace Database\Factories;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'responsible_id' => User::users()->get()->random(),
            'name' => $this->faker->word(),
            'status' => collect(['en progreso','en revisión','observaciones','aprobado','rechazado'])->random(),
            'repository_name' => $this->faker->word(),
        ];
    }
}
