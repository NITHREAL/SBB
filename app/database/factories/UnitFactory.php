<?php

namespace Database\Factories;

use Domain\Unit\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $titles = ['упак', 'шт', 'кг', 'м3', 'м', 'л', 'упак', 'тн', 'рул', 'пач', 'пара', 'л.'];

        return [
            'system_id' => $this->faker->uuid(),
            'title'     => $this->faker->randomElement($titles),
        ];
    }
}
