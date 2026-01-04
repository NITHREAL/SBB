<?php

namespace Database\Factories;

use Domain\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'set_id' => $this->faker->numberBetween(11111, 99999),
            'system_id' => $this->faker->uuid(),
            'legal_entity_system_id' => $this->faker->uuid(),
            'city_id' => $this->faker->numberBetween(1, 1000),
            'active' => $this->faker->boolean(),
            'title' => $this->faker->address(),
            'slug' => Str::random(20),
            'address' => $this->faker->address(),
            'work_time' => $this->faker->text(30),
            'latitude' => $this->faker->randomFloat(2, 0, 180),
            'longitude' => $this->faker->randomFloat(2, 0, 180),
            'sort' => $this->faker->numberBetween(100, 1000),
            'payments_from_city' => $this->faker->boolean(),
            'is_dark_store' => $this->faker->boolean(),
        ];
    }
}
