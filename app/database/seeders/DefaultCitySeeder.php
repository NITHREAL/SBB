<?php

namespace Database\Seeders;

use Domain\City\Models\City;
use Domain\City\Models\Region;
use Illuminate\Database\Seeder;

class DefaultCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region = Region::firstOrCreate(
            ['id' => 1],
            [
                'system_id' => '9fdbb25f-8925-4f28-8b61-40648d099241',
                'fias_id'   => '3c9d5e9e-5272-4e9e-9e49-dbe6faf01fb2',
                'title'     => 'Московская область',
            ],
        );

        City::firstOrCreate(
            ['id' => 2],
            [
                'system_id'         => '9fdbb25f-8925-4f28-8b61-40648d099241',
                'title'             => 'Москва',
                'fias_id'           => '0c5b2444-70a0-4932-980c-b4dc0d3f02b5',
                'timezone'          => 'Europe/Moscow',
                'region_id'         => $region->id,
                'region_system_id'  => '9fdbb25f-8925-4f28-8b61-40648d099241',
            ],
        );
    }
}
