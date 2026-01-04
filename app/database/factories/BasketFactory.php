<?php

namespace Database\Factories;

use Domain\Basket\Models\Basket;
use Illuminate\Database\Eloquent\Factories\Factory;

class BasketFactory extends Factory
{
    protected $model = Basket::class;

    public function definition(): array
    {
        return [
            'promo_id' => null,
            'user_id' => null,
            'token' => $this->faker->uuid(),
            'delivery_params' => null,
        ];
    }
}
