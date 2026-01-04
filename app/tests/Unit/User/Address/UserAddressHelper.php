<?php

namespace Tests\Unit\User\Address;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Pest\Expectation;
use Pest\Expectations\HigherOrderExpectation;

class UserAddressHelper
{
    public static array $userAddressStructure = [
        'id',
        'userId',
        'address',
        'cityName',
        'street',
        'house',
        'building',
        'apartment',
        'entrance',
        'floor',
        'comment',
        'otherCustomer',
        'otherCustomerPhone',
        'otherCustomerName',
        'cityId',
    ];
    public static array $userAddressesStructure = [
        [
            'id',
            'userId',
            'address',
            'cityName',
            'street',
            'house',
            'building',
            'apartment',
            'entrance',
            'floor',
            'comment',
            'otherCustomer',
            'otherCustomerPhone',
            'otherCustomerName',
            'cityId',
        ]
    ];

    public static function getUserAddressExpect(TestResponse|array $response): Expectation
    {
        return expect($response)
            ->and(Arr::get($response, 'id'))->toBeInt()
            ->and(Arr::get($response, 'userId'))->toBeInt()
            ->and(Arr::get($response, 'address'))->toBeString()
            ->and(Arr::get($response, 'cityName'))->toBeString()
            ->and(Arr::get($response, 'street'))->toBeString()
            ->and(Arr::get($response, 'house'))->toBeNumeric()
            ->and(Arr::get($response, 'building'))->toBeString()
            ->and(Arr::get($response, 'apartment'))->toBeNumeric()
            ->and(Arr::get($response, 'entrance'))->toBeNumeric()
            ->and(Arr::get($response, 'floor'))->toBeNumeric()
            ->and(Arr::get($response, 'comment'))->toBeString()
            ->and(Arr::get($response, 'otherCustomer'))->toBeBool()
            ->and(Arr::get($response, 'otherCustomerPhone'))->toBeNull()
            ->and(Arr::get($response, 'otherCustomerName'))->toBeNull()
            ->and(Arr::get($response, 'cityId'))->toBeInt()
            ;
    }

    public static function getAddUserAddressExpect(TestResponse|array $response, $request, $user): Expectation
    {
        return expect($response)
            ->and(Arr::get($response, 'id'))
            ->toBeInt()
            ->and(Arr::get($response, 'userId'))
            ->toBeInt()->toEqual($user->id)
            ->and(Arr::get($response, 'address'))
            ->toBeString()->toEqual(Arr::get($request, 'address'))
            ->and(Arr::get($response, 'cityName'))
            ->toBeString()->toEqual(Arr::get($request, 'cityName'))
            ->and(Arr::get($response, 'street'))
            ->toBeString()->toEqual(Arr::get($request, 'street'))
            ->and(Arr::get($response, 'house'))
            ->toBeNumeric()->toEqual(Arr::get($request, 'house'))
            ->and(Arr::get($response, 'building'))
            ->toBeString()->toEqual(Arr::get($request, 'building'))
            ->and(Arr::get($response, 'apartment'))
            ->toBeNumeric()->toEqual(Arr::get($request, 'apartment'))
            ->and(Arr::get($response, 'entrance'))
            ->toBeNumeric()->toEqual(Arr::get($request, 'entrance'))
            ->and(Arr::get($response, 'floor'))
            ->toBeNumeric()->toEqual(Arr::get($request, 'floor'))
            ->and(Arr::get($response, 'comment'))
            ->toBeString()->toEqual(Arr::get($request, 'comment'))
            ->and(Arr::get($response, 'otherCustomer'))
            ->toBeTrue()
            ->and(Arr::get($response, 'otherCustomerPhone'))
            ->toBeString()->toEqual(Arr::get($request, 'otherCustomerPhone'))
            ->and(Arr::get($response, 'otherCustomerName'))
            ->toBeString()->toEqual(Arr::get($request, 'otherCustomerName'))
            ->and(Arr::get($response, 'cityId'))
            ->toBeInt()->toEqual(Arr::get($request, 'cityId'))
            ;
    }

    public static function getUserAddressesExpect(TestResponse|array $response): Expectation
    {
        $address = Arr::get($response, '0');

        return UserAddressHelper::getUserAddressExpect($address);
    }

    public static function getDeleteUserAddressExpect(TestResponse $response): HigherOrderExpectation
    {
        return expect($response)
            ->assertStatus(204)
            ->assertNoContent()
            ;
    }
}

