<?php

use Domain\City\Models\City;
use Domain\City\Models\Region;
use Domain\Store\Models\Store;
use Domain\User\Models\User;
use Domain\User\Models\UserStore;
use Tests\Unit\User\Stores\UserStoresHelper;
use Tests\Unit\User\Stores\UserStoresRequests;

uses(UserStoresRequests::class);

uses()->group('unit');
uses()->group('userStores');
uses()->group('userStores_unit');


describe('user store tests', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);
        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);
    });

    it('has valid request get user stores', function () {
        $userStore = new UserStore();

        $userStore->user()->associate($this->user);
        $userStore->store()->associate($this->store);

        $userStore->save();

        $response = $this->getUserStores($this->accessToken);

        UserStoresHelper::getUserStoresExpect($response);
    });

    it('has valid request add user store', function () {
        $response = $this->addUserStores($this->store->id, $this->accessToken);

        UserStoresHelper::getAddUserStoresExpect($response);
    });

    it('has valid request delete user store', function () {
        $response = $this->deleteUserStores($this->store->id, $this->accessToken);

        UserStoresHelper::getDeleteUserStoresExpect($response);
    });
});
