<?php

namespace Database\Factories;

use Domain\Store\Models\Store;
use Domain\User\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        $buildings = ['a', 'b', 'c', 'd'];

        return [
            'user_id' => $this->faker->numberBetween(1, 1000),
            'city_id' => $this->faker->numberBetween(1, 1000),
            'address' => $this->faker->address(),
            'city_name' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'house' => $this->faker->numberBetween(1, 100),
            'building' => $this->faker->randomElement($buildings),
            'entrance' => $this->faker->numberBetween(1, 10),
            'apartment' => $this->faker->numberBetween(1, 200),
            'floor' => $this->faker->numberBetween(1, 20),
            'comment' => $this->faker->text(10),
            'other_customer' => false,
            'other_customer_phone' => null,
            'other_customer_name' => null,
        ];
    }
}
