<?php


use Domain\City\Models\City;
use Domain\City\Models\Region;
use Domain\User\Models\User;
use Domain\User\Models\UserAddress;
use Tests\Unit\User\Address\UserAddressHelper;
use Tests\Unit\User\Address\UserAddressRequests;

uses(UserAddressRequests::class);

uses()->group('unit');
uses()->group('userAddresses');
uses()->group('userAddresses_unit');


describe('user address tests', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);
        $this->accessToken = $this->tokenData['access_token'];

        $this->city = City::factory()->create([
            'region_id' => Region::factory()->create()->id
        ]);
    });

    it('has valid request get user addresses', function () {
        UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
        ]);
        $response = $this->getUserAddresses($this->accessToken);

        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(UserAddressHelper::$userAddressesStructure);

        UserAddressHelper::getUserAddressesExpect($response);
    });

    it('has valid request get user address', function () {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
        ]);
        $response = $this->getUserAddress($address->id, $this->accessToken);

        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(UserAddressHelper::$userAddressStructure);

        UserAddressHelper::getUserAddressExpect($response);
    });

    it('has valid request add user address', function ($data) {
        $response = $this->addUserAddress(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(UserAddressHelper::$userAddressStructure);

        UserAddressHelper::getAddUserAddressExpect($response, $data['request'], $this->user);

    })->with('add user address valid');

    it('has already add user address', function ($data) {
        $address = UserAddress::factory()->create([
            'user_id'               => (int) $this->user->id,
            'city_id'               => (int) $this->city->id,
            'address'               => 'г Кемерово, ул Кемеровская 150',
            'city_name'             => 'Кемерово',
            'street'                => 'Кемеровская',
            'house'                 => '150',
            'building'              => 'а',
            'entrance'              => 1,
            'apartment'             => 222,
            'floor'                 => 4,
            'comment'               => 'comment for address',
            'other_customer'        => true,
            'other_customer_phone'  => '1111111111',
            'other_customer_name'   => 'Василий',
        ]);

        $response = $this->addUserAddress(
            [
                'cityId'                => (int) $this->city->id,
                'address'               => $address->address,
                'cityName'              => $address->city_name,
                'street'                => $address->street,
                'house'                 => $address->house,
                'building'              => $address->building,
                'entrance'              => (int) $address->entrance,
                'apartment'             => (int) $address->comment,
                'floor'                 => (int) $address->floor,
                'comment'               => $address->comment,
                'otherCustomer'         => $address->customer,
                'otherCustomerPhone'    => $address->other_customer_phone,
                'otherCustomerName'     => $address->other_customer_name,
            ],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);

    })->with('already add user address');

    it('has empty data request add user address', function ($data) {

        $response = $this->addUserAddress(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertContent($data['error']);
    })->with('user address empty data');

    it('has invalid request add user address', function ($data) {

        $response = $this->addUserAddress(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertContent($data['error']);
    })->with('user address invalid');

    it('has valid request update user address', function ($data) {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
        ]);

        $response = $this->updateUserAddress(
            $address->id,
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(UserAddressHelper::$userAddressStructure);

        UserAddressHelper::getAddUserAddressExpect($response, $data['request'], $this->user);

    })->with('update user address valid');

    it('has empty data request update user address', function ($data) {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
        ]);

        $response = $this->updateUserAddress(
            $address->id,
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertContent($data['error']);
    })->with('user address empty data');

    it('has invalid request update user address', function ($data) {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
        ]);

        $response = $this->updateUserAddress(
            $address->id,
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertContent($data['error']);
    })->with('user address invalid');

    it('has valid request delete user address', function () {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
        ]);

        $response = $this->deleteUserAddress($address->id, $this->accessToken);

        UserAddressHelper::getDeleteUserAddressExpect($response);
    });
});
