<?php

namespace Tests\Unit\User\Stores;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Pest\Expectation;
use Pest\Expectations\HigherOrderExpectation;

class UserStoresHelper
{
    public static array $userStoresStructure = [
        'userId',
        'stores' => [
            [
                'id',
                'title',
                'slug',
                'cityId',
                'address',
                'workTime',
                'opened',
                'latitude',
                'longitude',
                'oneCId',
                'active',
                'sort',
                'contacts',
                'polygon',
            ]
        ],
    ];
    public static array $addUserStoreStructure = [
        'userId',
        'store' => [
                'id',
                'title',
                'slug',
                'cityId',
                'address',
                'workTime',
                'opened',
                'latitude',
                'longitude',
                'oneCId',
                'active',
                'sort',
                'contacts',
                'polygon',
        ],
    ];

    public static function getUserStoresExpect(TestResponse $response): Expectation
    {
        $store = Arr::first(Arr::get($response, 'stores'));

        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$userStoresStructure)
            ->and(Arr::get($response, 'userId'))->toBeInt()
            ->and(Arr::get($response, 'stores'))->toBeArray()
            ->and(Arr::get($store, 'id'))->toBeInt()
            ->and(Arr::get($store, 'title'))->toBeString()
            ->and(Arr::get($store, 'slug'))->toBeString()
            ->and(Arr::get($store, 'cityId'))->toBeInt()
            ->and(Arr::get($store, 'address'))->toBeString()
            ->and(Arr::get($store, 'workTime'))->toBeString()
            ->and(Arr::get($store, 'opened'))->toBeBool()
            ->and(Arr::get($store, 'latitude'))->toBeString()
            ->and(Arr::get($store, 'longitude'))->toBeString()
            ->and(Arr::get($store, 'oneCId'))->toBeString()->toBeUuid()
            ->and(Arr::get($store, 'active'))->toBeBool()
            ->and(Arr::get($store, 'sort'))->toBeInt()
            ->and(Arr::get($store, 'contacts'))->toBeArray()
            ->and(Arr::get($store, 'polygon'))->toBeNull()
            ;
    }

    public static function getAddUserStoresExpect(TestResponse $response): Expectation
    {
        $store = Arr::get($response, 'store');

        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$addUserStoreStructure)
            ->and(Arr::get($response, 'userId'))->toBeInt()
            ->and(Arr::get($response, 'store'))->toBeArray()
            ->and(Arr::get($store, 'id'))->toBeInt()
            ->and(Arr::get($store, 'title'))->toBeString()
            ->and(Arr::get($store, 'slug'))->toBeString()
            ->and(Arr::get($store, 'cityId'))->toBeInt()
            ->and(Arr::get($store, 'address'))->toBeString()
            ->and(Arr::get($store, 'workTime'))->toBeString()
            ->and(Arr::get($store, 'opened'))->toBeBool()
            ->and(Arr::get($store, 'latitude'))->toBeString()
            ->and(Arr::get($store, 'longitude'))->toBeString()
            ->and(Arr::get($store, 'oneCId'))->toBeString()->toBeUuid()
            ->and(Arr::get($store, 'active'))->toBeBool()
            ->and(Arr::get($store, 'sort'))->toBeInt()
            ->and(Arr::get($store, 'contacts'))->toBeArray()
            ->and(Arr::get($store, 'polygon'))->toBeNull()
            ;
    }

    public static function getDeleteUserStoresExpect(TestResponse $response): HigherOrderExpectation
    {
        return expect($response)
            ->assertStatus(204)
            ->assertNoContent()
            ;
    }
}
