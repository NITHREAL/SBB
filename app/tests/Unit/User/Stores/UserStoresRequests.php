<?php

namespace Tests\Unit\User\Stores;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UserStoresRequests extends TestCase
{
    public function getUserStores(string $accessToken): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get('/api/v1/user/stores');
    }

    public function addUserStores(
        int $id,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post(
                sprintf(
                    '/api/v1/user/stores/%s',
                    $id,
                )
            );
    }

    public function deleteUserStores(
        int $id,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->delete(
                sprintf(
                    '/api/v1/user/stores/%s',
                    $id,
                )
            );
    }
}
