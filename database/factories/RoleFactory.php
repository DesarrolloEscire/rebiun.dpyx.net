<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function administrator()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => Role::ADMIN_ROLE,
            ];
        });
    }

    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => Role::USER_ROLE,
            ];
        });
    }

    public function evaluator()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => Role::EVALUATOR_ROLE,
            ];
        });
    }
}
