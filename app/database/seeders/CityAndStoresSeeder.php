<?php

namespace Database\Seeders;

use Domain\City\Models\City;
use Domain\City\Models\Region;
use Domain\Store\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Str;

class CityAndStoresSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'system_id'     => '9fdaa25f-a3d0-4f28-8b61-40648d099421',
                'fias_id'       => '3c9d5e9e-3735-4e9e-9e49-dbe6faf01fb2',
                'title'         => 'Республика Бурятия',
                'sort'          => 100,
                'created_at'    => now(),
                'updated_at'    => now(),
                'cities'        => [
                    [
                        'system_id'         => '9fdbb25f-8932-4f28-8b61-40648d099241',
                        'region_system_id'  => '9fdaa25f-a3d0-4f28-8b61-40648d099421',
                        'title'             => 'Улан-Удэ',
                        'fias_id'           => '9fdcc25f-a3d0-4f28-8b61-40648d099065',
                        'region_fias_id'    => 'a84ebed3-153d-4ba9-8532-8bdf879e1f5a',
                        'latitude'          => '51.833507',
                        'longitude'         => '107.584125',
                        'stores'    => [
                            [
                                'city_system_id'    => '9fdbb25f-8932-4f28-8b61-40648d099241',
                                'title'             => 'ул. Пестеля, 8',
                                'address'           => '670034, Респ Бурятия, г Улан-Удэ, Железнодорожный р-н, ул Пестеля, д 8',
                                'latitude'          => '51.840319',
                                'longitude'         => '107.59398',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                            [
                                'city_system_id'    => '9fdbb25f-8932-4f28-8b61-40648d099241',
                                'title'             => 'ул. Геологическая, 13',
                                'address'           => '670031, Респ Бурятия, г Улан-Удэ, Октябрьский р-н, ул Геологическая, д 13',
                                'latitude'          => '51.8112',
                                'longitude'         => '107.604939',
                                'sort'              => 200,
                                'active'            => 1,
                            ],
                        ],
                    ],
                    [
                        'system_id'         => '9fdbb25f-y723-4f28-8b61-40648d099241',
                        'region_system_id'  => '9fdaa25f-a3d0-4f28-8b61-40648d099421',
                        'title'             => 'Гусиноозёрск',
                        'fias_id'           => '30e1ab6c-4ee2-45ce-934a-c3435a015c29',
                        'region_fias_id'    => 'a84ebed3-153d-4ba9-8532-8bdf879e1f5a',
                        'latitude'          => '51.286545',
                        'longitude'         => '106.523062',
                        'stores'    => [
                            [
                                'city_system_id'    => '9fdbb25f-y723-4f28-8b61-40648d099241',
                                'title'             => 'ул. Ленина, 28Б',
                                'address'           => '671160, Респ Бурятия, Селенгинский р-н, г Гусиноозерск, ул Ленина, д 28б',
                                'latitude'          => '51.281413',
                                'longitude'         => '106.525963',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                        ],
                    ]
                ],
            ],
            [
                'system_id'     => '9fdbb25f-a3d0-4f28-8b61-40648d099241',
                'fias_id'       => '3c9d5e9e-9532-4e9e-9e49-dbe6faf01fb2',
                'title'         => 'Иркутская область',
                'sort'          => 200,
                'created_at'    => now(),
                'updated_at'    => now(),
                'cities'        => [
                    [
                        'system_id'         => '9fdbb25f-3627-4f28-8b61-40648d099241',
                        'region_system_id'  => '9fdbb25f-a3d0-4f28-8b61-40648d099241',
                        'title'             => 'Ангарск',
                        'fias_id'           => '82b6b7c8-82a4-44b2-8bc7-691373706b89',
                        'region_fias_id'    => '6466c988-7ce3-45e5-8b97-90ae16cb1249',
                        'latitude'          => '52.544889',
                        'longitude'         => '103.888456',
                        'stores'    => [
                            [
                                'city_system_id'    => '9fdbb25f-3627-4f28-8b61-40648d099241',
                                'title'             => 'ул. Желябова, 4',
                                'address'           => '665826, Иркутская обл, г Ангарск, ул Желябова, д 4',
                                'latitude'          => '52.51478',
                                'longitude'         => '103.85553',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                        ],
                    ],
                    [
                        'system_id'         => '9fdbb25f-5212-4f28-8b61-40648d099241',
                        'region_system_id'  => '9fdbb25f-a3d0-4f28-8b61-40648d099241',
                        'title'             => 'Тулун',
                        'fias_id'           => '869c2c69-e8cd-43ae-9596-e0be50c0fcfe',
                        'region_fias_id'    => '6466c988-7ce3-45e5-8b97-90ae16cb1249',
                        'latitude'          => '54.55712',
                        'longitude'         => '100.578038',
                        'stores'    => [
                            [
                                'city_system_id'    => '9fdbb25f-5212-4f28-8b61-40648d099241',
                                'title'             => 'ул Юбилейная, 14',
                                'address'           => '665268, Иркутская обл, г Тулун, ул Юбилейная, зд 14',
                                'latitude'          => '54.55594',
                                'longitude'         => '100.582997',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'system_id'     => '9fsrq25f-a3d0-4f28-8b61-40648d099423',
                'fias_id'       => '3c9d5e9e-6315-4e9e-9e49-dbe6faf01fb2',
                'title'         => 'Забайкальский край',
                'sort'          => 300,
                'created_at'    => now(),
                'updated_at'    => now(),
                'cities'        => [
                    [
                        'system_id'         => '9fdbb25f-7625-4f28-8b61-40648d099241',
                        'region_system_id'  => '9fsrq25f-a3d0-4f28-8b61-40648d099423',
                        'title'             => 'Чита',
                        'fias_id'           => '2d9abaa6-85a6-4f1f-a1bd-14b76ec17d9c',
                        'region_fias_id'    => 'b6ba5716-eb48-401b-8443-b197c9578734',
                        'latitude'          => '52.033973',
                        'longitude'         => '113.499432',
                        'stores'    => [
                            [
                                'city_system_id'    => '9fdbb25f-7625-4f28-8b61-40648d099241',
                                'title'             => 'ул. Новобульварная, 55',
                                'address'           => '672012, Забайкальский край, г Чита, ул Новобульварная, д 55',
                                'latitude'          => '52.047222',
                                'longitude'         => '113.504',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                            [
                                'city_system_id'    => '9fdbb25f-7625-4f28-8b61-40648d099241',
                                'title'             => 'просп. Маршала Жукова, 10',
                                'address'           => '672042, Забайкальский край, г Чита, пр-кт Маршала Жукова, д 10',
                                'latitude'          => '52.068854',
                                'longitude'         => '113.385023',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                        ],
                    ],
                    [
                        'system_id'         => '9fdbb25f-9221-4f28-8b61-40648d099241',
                        'region_system_id'  => '9fsrq25f-a3d0-4f28-8b61-40648d099423',
                        'title'             => 'Краснокаменск',
                        'fias_id'           => '3c9d5e9e-2922-4e9e-9e49-dbe6faf01fb2',
                        'region_fias_id'    => 'b6ba5716-eb48-401b-8443-b197c9578734',
                        'latitude'          => '50.09868',
                        'longitude'         => '118.034118',
                        'stores'    => [
                            [
                                'city_system_id'    => '9fdbb25f-9221-4f28-8b61-40648d099241',
                                'title'             => 'просп. Строителей, 13',
                                'address'           => '674673, Забайкальский край, Краснокаменский р-н, г Краснокаменск, пр-кт Строителей, д 13',
                                'latitude'          => '50.10181',
                                'longitude'         => '118.04261',
                                'sort'              => 100,
                                'active'            => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];


        DB::transaction(function () use ($regions) {
            foreach ($regions as $regionData) {
                $cities = Arr::pull($regionData, 'cities');

                $region = Region::firstOrCreate(
                    ['title' => Arr::get($regionData, 'title')],
                    $regionData,
                );

                foreach ($cities as $cityData) {
                    $stores = Arr::pull($cityData, 'stores');

                    $cityData['region_id'] = $region->id;
                    $cityData['created_at'] = now();
                    $cityData['updated_at'] = now();

                    $city = City::firstOrCreate(
                        ['title' => Arr::get($cityData, 'title')],
                        $cityData
                    );

                    foreach ($stores as $storeData) {
                        $storeData['city_id'] = $city->id;
                        $storeData['system_id'] = Str::uuid();
                        $storeData['created_at'] = now();
                        $storeData['updated_at'] = now();

                        Store::firstOrCreate(
                            ['title' => Arr::get($storeData, 'title')],
                            $storeData,
                        );
                    }
                }
            }
        });
    }
}
