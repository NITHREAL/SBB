<?php

namespace Database\Factories;

use Domain\Order\Models\Delivery\Polygon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PolygonFactory extends Factory
{
    protected $model = Polygon::class;

    public function definition(): array
    {
        return [
            'store_system_id'   => $this->faker->uuid(),
            'coordinates'       => [],
            'stroke_color'      => $this->faker->hexColor(),
            'fill_color'        => $this->faker->hexColor(),
            'type'              => null,
        ];
    }
}
