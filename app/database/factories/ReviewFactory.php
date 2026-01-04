<?php

namespace Database\Factories;

use Domain\Product\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;


class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'product_id'    => $this->faker->uuid(),
            'user_id'       => $this->faker->numberBetween(5, 100),
            'rating'        => $this->faker->numberBetween(1, 5),
            'text'          => $this->faker->text(20),
            'active'        => $this->faker->boolean(),
            'user_name'     => $this->faker->firstName,
            'user_phone'    => $this->faker->numberBetween(1111111111, 9999999999),
        ];
    }
}
