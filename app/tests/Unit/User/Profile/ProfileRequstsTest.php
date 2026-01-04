<?php

use Domain\User\Models\User;
use Tests\Unit\User\Profile\ProfileHelper;
use Tests\Unit\User\Profile\ProfileRequests;

uses(ProfileRequests::class);

uses()->group('unit');
uses()->group('profile');
uses()->group('profile_unit');

describe('profile tests', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->phone = random_int(1111111111, 9999999999);

        $this->tokenData = $this->createUserToken($this->user);

        $this->accessToken = $this->tokenData['access_token'];
    });

    it('has valid request get profile', function () {
        $response = $this->getProfile($this->accessToken);

        ProfileHelper::getProfileExpect($response);
    });

    it('has valid request update profile', function ($data) {
        $response = $this->updateProfile($data['request'], $this->accessToken);

        ProfileHelper::getProfileExpect($response);
    })->with('update profile valid');

    it('has empty data request update phone', function ($data) {
        $response = $this->updatePhone($data['request'], $this->accessToken);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('update phone empty data');

    it('has empty data request check phone', function ($data) {

        $response = $this->checkCode($data['request'], $this->accessToken);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('check phone empty data');

    it('has invalid request check phone', function ($data) {

        $response = $this->checkCode($data['request'], $this->accessToken);

        expect($response)
            ->assertStatus(401)
            ->assertExactJson($data['error']);
    })->with('check phone invalid');

    it('has invalid request update phone', function ($data) {
        $response = $this->updatePhone($data['request'], $this->accessToken);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('update phone invalid');

    it('delete profile', function () {
        $response = $this->deleteProfile($this->accessToken);

        ProfileHelper::getDeleteProfileExpect($response);
    });
});
