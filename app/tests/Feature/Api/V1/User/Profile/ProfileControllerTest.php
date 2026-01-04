<?php

namespace Tests\Feature\Api\V1\User\Profile;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\Services\SMS\SmsCodeService;
use Mockery;
use Tests\Unit\User\Profile\ProfileHelper;
use Tests\Unit\User\Profile\ProfileRequests;

uses(ProfileRequests::class);

uses()->group('feature');
uses()->group('profile');
uses()->group('profile_feature');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->phone = random_int(1111111111, 9999999999);

    $this->tokenData = $this->createUserToken($this->user);

    $this->accessToken = $this->tokenData['access_token'];
});

it('update phone in database', function () {
    $smsCodeService = Mockery::mock(SmsCodeService::class)->makePartial();
    $smsCodeService
        ->shouldReceive('processCodeSend')
        ->once()
        ->with($this->phone)
        ->andReturn(ProfileHelper::CODE);
    $this->app->bind(SmsCodeService::class, fn() => $smsCodeService);

    $request = [
        'phone' => (string) $this->phone,
    ];

    $response = $this->updatePhone($request, $this->accessToken);

    ProfileHelper::getUpdatePhoneExpect($response);

    $signature = Arr::get($response, 'signature');

    $requestCheck = [
        'phone' => (string) $this->phone,
        'code' => ProfileHelper::CODE,
        'signature' => $signature,
    ];

    $responseCheck = $this->checkCode($requestCheck, $this->accessToken);

    ProfileHelper::getCheckPhoneExpect($responseCheck);

    $user = User::query()->where('id', $this->user->id)->first();

    ProfileHelper::getCheckPhoneExpect($responseCheck);

    expect(Arr::get($responseCheck, 'phone'))
        ->toEqual($user->phone)
    ;
});
