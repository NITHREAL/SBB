<?php

namespace Tests\Feature\Api\V1\Auth\AuthController;

use Illuminate\Testing\TestResponse;
use Infrastructure\Services\SMS\SmsCodeService;
use Mockery;
use Tests\TestCase;

class AuthControllerTestHelper extends TestCase
{
    public string $phone = '9998007766';
    public string $code = '7766';


    public function sendSmsCode(): TestResponse
    {
        return static::post('/api/v1/auth/phone', ['phone' => $this->phone]);
    }

    public function checkCode($signature): TestResponse
    {
        return static::post('/api/v1/auth/code/check', [
            'phone' => $this->phone,
            'code' => (string)$this->code,
            'signature' => $signature,
        ]);
    }

    public function logout($accessToken): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/auth/logout');
    }

    public function refreshToken($accessToken, $refreshToken): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/auth/refresh-token', ['refreshToken' => $refreshToken]);
    }


    public function checkToken($accessToken): TestResponse
    {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get('/api/v1/check-token');
    }

    public function checkVersion(): TestResponse
    {
        return static::get('/api/v1/check-version');
    }
}
