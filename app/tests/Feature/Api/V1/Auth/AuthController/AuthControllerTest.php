<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Infrastructure\Services\Auth\Token;
use Domain\User\Models\User;
use Infrastructure\Services\SMS\SmsCodeService;
use Tests\Feature\Api\V1\Auth\AuthController\AuthControllerTestHelper;

uses(AuthControllerTestHelper::class);

uses()->group('feature');
uses()->group('auth');

it('has full authentication without user', function () {
    $smsCodeService = Mockery::mock(SmsCodeService::class)->makePartial();
    $smsCodeService->shouldReceive('processTechCodeSend')->once()->andReturn($this->code);
    $this->app->bind(SmsCodeService::class, fn() => $smsCodeService);

    $response = $this->sendSmsCode();

    expect($response)->assertStatus(200)
        ->assertJsonStructure(['signature']);

    $signature = $response->json()['signature'];

    expect($response->json()['signature'])->toBeString();

    $responseData = $this->checkCode($signature);

    expect($responseData)->assertStatus(200)
        ->assertJsonStructure([
            "accessToken",
            "refreshToken",
            "expiresIn",
            "isUserNew"
        ]);
});


it('has logout without token', function () {
    $user = User::factory()->create();

    $tokenData = Token::create($user);

    $accessToken = $tokenData['access_token'];

    $response = $this->logout($accessToken);

    expect($response)->assertStatus(204);

});

it('has refresh token', function () {
    $user = User::factory()->create();

    $tokenData = Token::create($user);

    $accessToken = $tokenData['access_token'];
    $refreshToken = $tokenData['refresh_token'];

    $responseData = $this->refreshToken($accessToken, $refreshToken);

    expect($responseData)->assertStatus(200)
        ->assertJsonStructure([
            "accessToken",
            "refreshToken",
            "expiresIn"
        ]);
});

it('has check token', function () {
    $user = User::factory()->create();

    $tokenData = Token::create($user);

    $accessToken = $tokenData['access_token'];

    $responseData = $this->checkToken($accessToken);

    expect($responseData)->assertStatus(200)->assertJson(["isValid"=>true]);

});


it('has check version', function () {
    $responseData = $this->checkVersion();

    expect($responseData)
        ->assertStatus(200)
        ->assertJsonStructure(["ios", "android"]);
});
