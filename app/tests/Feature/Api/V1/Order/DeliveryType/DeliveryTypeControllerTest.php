<?php

namespace Tests\Feature\Api\V1\Order\DeliveryType;

use Domain\Order\Services\Delivery\Polygon\PolygonService;
use Domain\User\Models\User;
use Infrastructure\Services\DaData\Address\DaDataAddressService;
use Mockery;
use Tests\BuyerTestHelper;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Order\DeliveryType\DeliveryTypeRequests;

uses(DeliveryTypeRequests::class);

uses()->group('feature');
uses()->group('delivery');
uses()->group('delivery_feature');

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

it('has cache and basket params with get delivery', function () {
    $response = $this->getDelivery($this->accessToken);

    expect($response)
        ->assertStatus(200);

    $token = $response['token'];

    $intervals = DeliveryCacheHelper::getIntervalsArray($response);

    DeliveryCacheHelper::getExpectWithCache($response, $token, $this->user);

    expect($response['deliverySubType'])
        ->toEqual(DeliveryCacheHelper::getCachedBuyerDeliverySubType($token))
        ->and($intervals)
        ->toEqual(DeliveryCacheHelper::getCachedBuyerDeliveryIntervals($token));
});

it('set cache delivery and basket params by city delivery', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);

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

    $response = $this->setDeliveryTypeByCity($data['request'], $this->accessToken);

    expect($response)
        ->assertStatus(200);

    $token = $response['token'];

    DeliveryCacheHelper::getExpectWithCache($response, $token, $this->user);
})->with('set delivery type by city valid');

it('set cache delivery and basket params', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);

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

    $response = $this->setDeliveryType($data['request'], $this->accessToken);

    expect($response)
        ->assertStatus(200);

    $token = $response['token'];

    DeliveryCacheHelper::getExpectWithCache($response, $token, $this->user);
})->with('set delivery type valid');

it('has available delivery type', function ($data) {
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

    expect($response)
        ->assertStatus(200)
        ->and($response->json()['deliveryType'])->toEqual($data['request']['deliveryType']);
})->with('get available delivery type valid');
