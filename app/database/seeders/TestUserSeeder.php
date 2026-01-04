<?php

namespace Database\Seeders;

use Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $userData = config('users.test_user');

        $adminPermissions = [
            "content"                       => true,
            "feedback"                      => true,
            "review"                        => true,
            "activate_store"                => true,
            "easypasscode"                  => true,
        ];

        User::firstOrCreate(
            ['email' => Arr::get($userData, 'email')],
            [
                'first_name'    => Arr::get($userData, 'first_name'),
                'middle_name'    => Arr::get($userData, 'middle_name'),
                'last_name'    => Arr::get($userData, 'last_name'),
                'phone'    => Arr::get($userData, 'phone'),
                'email'         => Arr::get($userData, 'email'),
                'password'      => Hash::make(Arr::get($userData, 'password')),
                'birthdate'    => Arr::get($userData, 'birthdate'),
                'bonuses'    => Arr::get($userData, 'bonuses'),
                'permissions'   => $adminPermissions,
            ],
        );
    }
}
