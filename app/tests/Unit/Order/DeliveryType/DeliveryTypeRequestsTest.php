<?php

use Domain\Order\Services\Delivery\Polygon\PolygonService;
use Domain\User\Models\User;
use Infrastructure\Services\Buyer\Facades\BuyerAddress;
use Infrastructure\Services\Buyer\Facades\BuyerCity;
use Infrastructure\Services\DaData\Address\DaDataAddressService;
use Tests\BuyerTestHelper;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Order\DeliveryType\DeliveryTypeHelper;
use Tests\Unit\Order\DeliveryType\DeliveryTypeRequests;

uses(DeliveryTypeRequests::class);

uses()->group('unit');
uses()->group('delivery');
uses()->group('delivery_unit');


describe('get available delivery type', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);

        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);

        $this->coordinates = BasketHelper::$coordinates;

        $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('pickup');
        $this->createStoreScheduleWeekday($this->store, $polygonType);
    });

    it('has valid request pickup', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->getAvailableDeliveryType($this->accessToken, $data['request']);
//        dump($response);
        DeliveryTypeHelper::getMainExpect($response);

        expect($response)
            ->assertJsonPath('deliveryType', $data['request']['deliveryType'])
            ->assertJsonPath('cityId', $data['request']['cityId'])
            ->and($response['deliverySubType'])->toBeString()
            ->and($response['deliveryIntervalDate'])->toBeString()
            ->and($response['deliveryIntervalTime'])->toBeString();
    })->with('get available delivery type pickup valid');

    it('has valid request delivery', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $polygon = $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('delivery');
        $this->createStoreScheduleWeekday($this->store, $polygonType);

        $dadataAddressService = Mockery::mock(DadataAddressService::class)->makePartial();
        $dadataAddressService
            ->shouldReceive('getOneAddressDataByQuery')
            ->andReturn([
                'value'   => $this->store->address,
                'latitude'  => '55.43082653862545',
                'longitude' => '86.10187189960669',
            ]);
        $this->app->bind(DadataAddressService::class, fn() => $dadataAddressService);

        $polygonService = Mockery::mock(PolygonService::class)->makePartial();
        $polygonService
            ->shouldReceive('findPolygonByCoordinates')
            ->andReturn($polygon);
        $this->app->bind(PolygonService::class, fn() => $polygonService);

        $response = $this->getAvailableDeliveryType($this->accessToken, $data['request']);

        DeliveryTypeHelper::getMainExpect($response);

        expect($response)
            ->assertJsonPath('deliveryType', $data['request']['deliveryType'])
            ->assertJsonPath('cityId', $data['request']['cityId'])
            ->and($response['deliverySubType'])->toBeNull()
            ->and($response['deliveryIntervalDate'])->toBeString()
            ->and($response['deliveryIntervalTime'])->toBeNull();
    })->with('get available delivery type delivery valid');

    it('has invalid additional param', function ($data) {
        $response = $this->getAvailableDeliveryType($this->accessToken, $data['request']);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('get available delivery type invalid');

    it('has empty data', function ($data) {
        $response = $this->getAvailableDeliveryType($this->accessToken, $data['request']);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('get available delivery type invalid empty');

    it('has invalid types pickup', function ($data) {
        $response = $this->getAvailableDeliveryType($this->accessToken, $data['request']);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('get available delivery type wrong type');
});

describe('set available delivery type', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);

        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);

        $this->coordinates = BasketHelper::$coordinates;

        $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('pickup');
        $this->createStoreScheduleWeekday($this->store, $polygonType);
    });

    it('has valid request', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $polygon = $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('delivery');
        $this->createStoreScheduleWeekday($this->store, $polygonType);

        $dadataAddressService = Mockery::mock(DadataAddressService::class)->makePartial();
        $dadataAddressService
            ->shouldReceive('getOneAddressDataByQuery')
            ->andReturn([
                'value'   => $this->store->address,
                'latitude'  => '55.43082653862545',
                'longitude' => '86.10187189960669',
            ]);
        $this->app->bind(DadataAddressService::class, fn() => $dadataAddressService);

        $polygonService = Mockery::mock(PolygonService::class)->makePartial();
        $polygonService
            ->shouldReceive('findPolygonByCoordinates')
            ->andReturn($polygon);
        $this->app->bind(PolygonService::class, fn() => $polygonService);

        $response = $this->setDeliveryType($data['request'], $this->accessToken);

        DeliveryTypeHelper::getMainExpect($response);

        expect($response)
            ->assertJsonPath('deliveryType', $data['request']['deliveryType'])
            ->assertJsonPath('cityId', $data['request']['cityId'])
            ->and($response['deliverySubType'])->toBeString()
            ->and($response['deliveryIntervalDate'])->toBeString()
            ->and($response['deliveryIntervalTime'])->toBeString();
    })->with('set delivery type valid');

    it('has invalid request', function ($data) {
        $response = $this->setDeliveryType($data['request'], $this->accessToken);

        $response->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('set delivery type invalid pickup');

    it('has invalid delivery', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $response = $this->setDeliveryType($data['request'], $this->accessToken);

        expect($response)
            ->assertStatus(400)
            ->assertExactJson($data['error']);
    })->with('set delivery type invalid delivery');
});

describe('set delivery type by city', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);

        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);

        $this->coordinates = BasketHelper::$coordinates;

        $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('pickup');
        $this->createStoreScheduleWeekday($this->store, $polygonType);
    });
    it('has valid request', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $polygon = $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('delivery');
        $this->createStoreScheduleWeekday($this->store, $polygonType);

        $dadataAddressService = Mockery::mock(DadataAddressService::class)->makePartial();
        $dadataAddressService
            ->shouldReceive('getAddressByCoordinates')
            ->andReturn([
                'value'             => 'City street house',
                'location'          => 'City',
                'street'            => 'street',
                'house'             => 'house',
                'latitude'          => '55.43082653862545',
                'longitude'         => '86.10187189960669',
                'fias_id'           => '86.10187189960669',
                'region_fias_id'    => '86.10187189960669',
                'city_id'             => $this->city->id,
            ]);
        $this->app->bind(DadataAddressService::class, fn() => $dadataAddressService);

        $polygonService = Mockery::mock(PolygonService::class)->makePartial();
        $polygonService
            ->shouldReceive('findPolygonByCoordinates')
            ->andReturn($polygon);
        $this->app->bind(PolygonService::class, fn() => $polygonService);

        $response = $this->setDeliveryTypeByCity($data['request']);

        DeliveryTypeHelper::getMainExpect($response);

        expect($response)
            ->assertJsonPath('deliveryType', $data['request']['deliveryType'])
            ->assertJsonPath('cityId', $data['request']['cityId'])
            ->and($response['deliverySubType'])->toBeNull()
            ->and($response['deliveryIntervalDate'])->toBeNull()
            ->and($response['deliveryIntervalTime'])->toBeNull();

    })->with('set delivery type by city valid');

    it('has invalid request', function ($data) {
        $polygon = $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('delivery');
        $this->createStoreScheduleWeekday($this->store, $polygonType);

        $polygonService = Mockery::mock(PolygonService::class)->makePartial();
        $polygonService
            ->shouldReceive('findPolygonByCoordinates')
            ->andReturn($polygon);
        $this->app->bind(PolygonService::class, fn() => $polygonService);

        $response = $this->setDeliveryTypeByCity($data['request']);

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('set delivery type by city invalid');
});

describe('get delivery type', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);

        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);

        $this->coordinates = BasketHelper::$coordinates;

        $this->createPolygon($this->store, $this->coordinates);
        $polygonType = $this->createPolygonType('pickup');
        $this->createStoreScheduleWeekday($this->store, $polygonType);
    });

    it('has valid request', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerAddress::shouldReceive('getValue')
            ->andReturn('address');
        BuyerCity::shouldReceive('getValue')
            ->andReturn($this->city->id);
        BuyerCity::shouldReceive('getSelectedCity')
            ->andReturn($this->city);

        $response = $this->getDelivery();

        DeliveryTypeHelper::getMainExpect($response);

        expect($response)
            ->and($response['deliverySubType'])->toBeString()
            ->and($response['deliveryIntervalDate'])->toBeString()
            ->and($response['deliveryIntervalTime'])->toBeString();
    });
});
