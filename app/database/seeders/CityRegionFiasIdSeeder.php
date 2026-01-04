<?php

namespace Database\Seeders;

use Domain\City\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Infrastructure\Services\DaData\City\DaDataCityService;

class CityRegionFiasIdSeeder extends Seeder
{
    public function __construct(
        private readonly DaDataCityService $daDataCityService,
    ) {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::all();

        foreach ($cities as $city) {
            $suggestion = $this->daDataCityService->getCitiesData($city->title);
            $data = Arr::get(Arr::first($suggestion), 'data', []);

            $city->update([
                'region_fias_id' => Arr::get($data, 'region_fias_id'),
            ]);
        }
    }
}
