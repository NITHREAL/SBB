<?php

namespace Database\Seeders;

use Domain\User\Enums\RegistrationTypeEnum;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminData = config('users.admin');
        $adminPermissions = Dashboard::getAllowAllPermission();

        User::firstOrCreate(
            ['email' => Arr::get($adminData, 'email')],
            [
                'first_name'        => Arr::get($adminData, 'first_name'),
                'email'             => Arr::get($adminData, 'email'),
                'password'          => Hash::make(Arr::get($adminData, 'password')),
                'permissions'       => $adminPermissions,
                'registration_type' => RegistrationTypeEnum::admin()->value,
            ],
        );
    }
}
