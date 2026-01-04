<?php

namespace Database\Factories;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'system_id'             => $this->faker->uuid(),
            'unit_system_id'        => $this->faker->uuid(),
            'active'                => true,
            'sku'                   => $this->faker->numberBetween(100, 999999),
            'title'                 => $this->faker->words(2, true),
            'slug'                  => Str::random(10),
            'description'           => $this->faker->text(200),
            'composition'           => $this->faker->text(150),
            'storage_conditions'    => $this->faker->text(150),
            'proteins'              => 1.1,
            'fats'                  => 1.1,
            'carbohydrates'         => 1.1,
            'nutrition_kcal'        => 1.1,
            'nutrition_kj'          => 1.1,
            'weight'                => false,
            'shelf_life'            => $this->faker->numberBetween(1, 100),
            'delivery_in_country'   => $this->faker->boolean(),
            'by_preorder'           => $this->faker->boolean(),
            'show_as_preorder'      => $this->faker->boolean(),
            'delivery_dates'        => null,
            'cooking'               => $this->faker->boolean(),
            'sort'                  => $this->faker->numberBetween(400, 600),
            'by_points'             => $this->faker->boolean(),
            'vegan'                 => $this->faker->boolean(),
            'sku_1c_ut'             => $this->faker->numberBetween(100, 999999),
            'rating'                => $this->faker->numberBetween(10, 50) / 10,
        ];
    }
}
