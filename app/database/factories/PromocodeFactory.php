<?php

namespace Database\Factories;

use Domain\Promocode\Models\Promocode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PromocodeFactory extends Factory
{

    protected $model = Promocode::class;

    public function definition(): array
    {
        $discount = rand(10, 100);

        return [
            'free_delivery'     => $this->faker->boolean(),
            'order_type'        => 'any',
            'delivery_type'     => 'any',
            'any_user'          => $this->faker->boolean(),
            'one_use_per_phone' => $this->faker->boolean(),
            'expires_in'        => null,
            'discount'          => $discount,
            'min_amount'        => $discount * rand(0, 2),
            'limit'             => null,
            'percentage'        => $this->faker->boolean(),
            'any_product'       => $this->faker->boolean(),
            'mobile'            => $this->faker->boolean(),
            'active'            => true,
            'only_one_use'      => $this->faker->boolean(),
            'use_excluded'      => $this->faker->boolean(),
            'code'              => Str::random(10),
        ];
    }
}
