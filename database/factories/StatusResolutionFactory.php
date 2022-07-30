<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Evaluator;
use App\Models\StatusResolution;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusResolutionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StatusResolution::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'evaluator_id' => Evaluator::get()->random()->id,
            'evaluation_id' => Evaluation::get()->random()->id,
            'status_conciliation' => collect([
                StatusResolution::UNCONCILIATED,
                StatusResolution::CLOSED,
                StatusResolution::OPENED,
            ])->random(),
            'status' => collect([
                StatusResolution::APPROVED,
                StatusResolution::REJECTED,
            ])
        ];
    }

    public function opened()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_conciliation' => StatusResolution::OPENED,
            ];
        });
    }
}
