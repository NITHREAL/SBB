<?php

namespace Tests\Unit\User\Profile;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Pest\Expectation;
use Pest\Expectations\HigherOrderExpectation;

class ProfileHelper
{
    const CODE = '7766';

    public static string $phone = '9998007766';
    public static array $profileStructure = [
        'id',
        'firstName',
        'middleName',
        'lastName',
        'phone',
        'email',
        'birthdate',
    ];

    public static array $phoneStructure = [
        'signature'
    ];

    public static array $checkPhoneStructure = [
        'phone'
    ];

    public static function getProfileExpect(TestResponse $response): Expectation
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$profileStructure)
            ->and(Arr::get($response, 'id'))->toBeInt()
            ->and(Arr::get($response, 'firstName'))->toBeString()
            ->and(Arr::get($response, 'middleName'))->toBeString()
            ->and(Arr::get($response, 'lastName'))->toBeString()
            ->and(Arr::get($response, 'phone'))->toBeString()->toBeDigits()->toHaveLength(10)
            ->and(Arr::get($response, 'email'))->toBeString()
            ->and(Arr::get($response, 'birthdate'))->toBeString()
            ;
    }

    public static function getUpdatePhoneExpect(TestResponse $response): Expectation
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$phoneStructure)
            ->and(Arr::get($response, 'signature'))->toBeString()
            ;
    }

    public static function getCheckPhoneExpect(TestResponse $response): Expectation
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$checkPhoneStructure)
            ->and(Arr::get($response, 'phone'))->toBeString()->toBeDigits()->toHaveLength(10)
            ;
    }

    public static function getDeleteProfileExpect(TestResponse $response): HigherOrderExpectation
    {
        return expect($response)
            ->assertStatus(204)
            ->assertNoContent()
            ;
    }

    public static function getUpdateProfileRequest(): array
    {
        return [
            'firstName'     => 'John',
            'middleName'    => 'Ivanovich',
            'lastName'      => 'Doe',
            'birthdate'     => '2000-01-01',
            'email'         => 'test@test.test',
        ];
    }
}
