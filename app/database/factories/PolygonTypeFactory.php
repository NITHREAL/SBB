<?php

namespace Database\Factories;

use Domain\Order\Models\Delivery\PolygonType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PolygonTypeFactory extends Factory
{
    protected $model = PolygonType::class;

    public function definition(): array
    {
        $deliveryTypes = ['delivery', 'pickup'];

        return [
            'type'          => 'other',
            'delivery_type' => $this->faker->randomElement($deliveryTypes),
            'title'         => 'Забрать в другой день',
            'description'   => $this->faker->text(100),
            'tooltip'       => $this->faker->text(100),
        ];
    }
}
