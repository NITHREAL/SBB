<?php

declare(strict_types=1);

namespace Database\Factories;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    public function definition(): array
    {
        return [
            'phone' => $this->faker->numberBetween(1111111111, 9999999999),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'birthdate' => $this->faker->date(),
            'bonuses' => null,
            'referral_code' => null,
            'email_verified_at' => now(),
            'password' => Hash::make(value: 'password'),
            'permissions' => null,
            'store_system_id' => null,
            'set_card_number' => null,
            'electronic_checks' => 0,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
