<?php

namespace Database\Seeders;

use Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class ExchangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $exchangeData = config('auth.exchange_user');
        $adminPermissions = [
            "platform.systems.roles"        => true,
            "platform.systems.users"        => true,
            "platform.systems.attachment"   => true,
            "exchange"                      => true,
            "content"                       => true,
            "feedback"                      => true,
            "review"                        => true,
            "activate_store"                => true,
            "easypasscode"                  => true,
            "platform.index"                => true
        ];

        User::firstOrCreate(
            ['email' => Arr::get($exchangeData, 'email')],
            [
                'email'         => Arr::get($exchangeData, 'email'),
                'password'      => Hash::make(Arr::get($exchangeData, 'password')),
                'permissions'   => $adminPermissions,
            ],
        );
    }
}
