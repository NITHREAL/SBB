<?php

namespace Tests\Unit\User\Address;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UserAddressRequests extends TestCase
{
    public function getUserAddresses(string $accessToken): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get('/api/v1/user/address');
    }

    public function getUserAddress(
        int $id,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/user/address/%s',
                    $id,
                )
            );
    }

    public function addUserAddress(
        array $request,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/user/address', $request);
    }

    public function updateUserAddress(
        int $id,
        array $request,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->put(
                sprintf(
                    '/api/v1/user/address/%s',
                    $id,
                ), $request
            );
    }

    public function deleteUserAddress(
        int    $id,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->delete(
                sprintf(
                    '/api/v1/user/address/%s',
                    $id,
                )
            );
    }
}
