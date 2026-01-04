<?php

namespace Tests\Unit\User\Profile;

use Domain\User\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ProfileRequests extends TestCase
{

    public function getProfile(string $accessToken): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get('/api/v1/user/profile');
    }

    public function updateProfile(
        array  $request,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/user/profile', $request);
    }

    public function deleteProfile(
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->delete('/api/v1/user/profile');
    }

    public function updatePhone(
        array  $request,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/user/profile/phone', $request);
    }

    public function checkCode(
        array  $request,
        string $accessToken,
    ): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/user/profile/phone/check-code', $request);
    }
}
