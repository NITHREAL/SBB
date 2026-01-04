<?php

namespace Database\Factories;

use Domain\Farmer\Models\Farmer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FarmerFactory extends Factory
{
    protected $model = Farmer::class;

    public function definition(): array
    {
        return [
            'system_id'             => $this->faker->uuid(),
            'active'                => $this->faker->boolean(),
            'name'                  => $this->faker->firstName,
            'supply_description'    => $this->faker->text(20),
            'description'           => $this->faker->text(200),
            'sort'                  => $this->faker->numberBetween(400, 600),
            'slug'                  => Str::random(10),
            'address'               => $this->faker->address(),
        ];
    }
}
