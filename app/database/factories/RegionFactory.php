<?php

namespace Database\Factories;

use Domain\City\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition(): array
    {
        $regions = [
            'Красноярский край',
            'Кемеровская область',
            'Новосибирская область',
            'Алтайский край',
            'Томская область'
        ];

        return [
            'title' => $this->faker->randomElement($regions),
            'sort' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
