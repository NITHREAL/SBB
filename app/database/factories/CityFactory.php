<?php

namespace Database\Factories;

use Domain\City\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'for_city_id' => null,
            'title' => $this->faker->city(),
            'fias_id' => $this->faker->uuid(),
            'region_fias_id' => null,
            'is_settlement'  => $this->faker->boolean(),
            'timezone' => $this->faker->timezone(),
            'latitude' => $this->faker->randomFloat(2, 0, 180),
            'longitude' => $this->faker->randomFloat(2, 0, 180),
            'sort' => $this->faker->numberBetween(100, 1000),
            'region_id' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
