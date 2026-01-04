<?php

namespace Tests\Feature\Api\V1\Order\DeliveryType;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Tests\BuyerTestHelper;
use Tests\Feature\Api\V1\Basket\BasketDeliveryCacheHelper;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Basket\BasketRequests;

uses(BasketRequests::class);

uses()->group('feature');
uses()->group('basket');
uses()->group('basket_feature');

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->tokenData = $this->createUserToken($this->user);
    $this->accessToken = $this->tokenData['access_token'];

    $this->city = $this->createCity();

    $this->store = $this->createStore($this->city);

    $coordinates = BasketHelper::$coordinates;

    $this->createPolygon($this->store, $coordinates);
    $polygonType = $this->createPolygonType('pickup');
    $this->createStoreScheduleWeekday($this->store, $polygonType);
});

it('work promocode', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

    $response = $this->getBasket(
        $this->accessToken,
    );

    expect($response)
        ->assertStatus(200);

    $buyerToken = Arr::get($response, 'token');

    $response = $this->setBasketDeliveryData(
        Arr::get($data, 'request'),
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    $unit = $this->createUnit('кг');
    $count = 1;

    $product = $this->createProduct($unit->system_id,false);
    $product = $this->addLeftovers($product, $this->store, $count);

    $response = $this->addProduct(
        $product->id,
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketHelper::getExpectWithProducts($response, $product, 1);

    $promocode = $this->createPromocode(false, 50.0);

    $response = $this->setPromocode(
        ['promocode' => $promocode->code],
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketHelper::getExpectWithProducts($response, $product, 1, $promocode);

    $response = $this->clearPromocode(
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketHelper::getExpectWithProducts($response, $product, 1);
})->with('set delivery data valid');

it('work percent promocode', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

    $response = $this->getBasket(
        $this->accessToken,
    );

    expect($response)
        ->assertStatus(200);

    $buyerToken = Arr::get($response, 'token');

    $response = $this->setBasketDeliveryData(
        Arr::get($data, 'request'),
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    $unit = $this->createUnit('кг');
    $count = 1;

    $product = $this->createProduct($unit->system_id,false);
    $product = $this->addLeftovers($product, $this->store, $count);

    $response = $this->addProduct(
        $product->id,
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketHelper::getExpectWithProducts($response, $product, 1);

    $promocode = $this->createPromocode(true, 10.0);

    $response = $this->setPromocode(
        ['promocode' => $promocode->code],
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketHelper::getExpectWithProducts($response, $product, 1, $promocode);

    $response = $this->clearPromocode(
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketHelper::getExpectWithProducts($response, $product, 1);
})->with('set delivery data valid');

it('work preorder', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

    $response = $this->getBasket(
        $this->accessToken,
    );

    expect($response)
        ->assertStatus(200);

    $buyerToken = Arr::get($response, 'token');

    $response = $this->setBasketDeliveryData(
        Arr::get($data, 'request'),
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    $unit = $this->createUnit('кг');
    $count = 1;

    $product = $this->createProduct($unit->system_id,false);
    $product = $this->addLeftovers($product, $this->store, $count);

    $response = $this->addProduct(
        $product->id,
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    $productPreorder = $this->createProduct($unit->system_id,true);
    $productPreorder = $this->addLeftovers($productPreorder, $this->store, 0);

    $response = $this->addProduct(
        $productPreorder->id,
        $this->accessToken,
        $buyerToken,
    );
    expect($response)
        ->assertStatus(200)
        ->and(Arr::get($response, 'baskets'))
        ->toHaveCount(2);

    $response = $this->deleteProduct(
        $productPreorder->id,
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200)
        ->and(Arr::get($response, 'baskets'))
        ->toHaveCount(1);
})->with('set delivery data valid');

it('work set delivery', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

    $this->storeAnother = $this->createStore($this->city);

    $coordinates = BasketHelper::$coordinates;

    $this->createPolygon($this->store, $coordinates);
    $polygonType = $this->createPolygonType('pickup');
    $this->createStoreScheduleWeekday($this->storeAnother, $polygonType);

    $response = $this->getBasket(
        $this->accessToken,
    );

    $buyerToken = Arr::get($response, 'token');

    expect($response)
        ->assertStatus(200);

    $unit = $this->createUnit('кг');
    $count = 1;

    $product = $this->createProduct($unit->system_id,false);
    $this->addLeftovers($product, $this->store, $count);
    $this->addLeftovers($product, $this->storeAnother, $count);

    $response = $this->addProduct(
        $product->id,
        $this->accessToken,
        $buyerToken
    );

    expect($response)
        ->assertStatus(200);

    $response = $this->setBasketDeliveryData(
        Arr::get($data, 'request'),
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);

    BasketDeliveryCacheHelper::getExpectWithCache(
        Arr::get($data, 'request'),
        $buyerToken,
        $response,
        $this->user,
    );

    $requestAnother = array_merge(
        Arr::get($data, 'request'),
        [
            'deliveryType.storeId' => $this->storeAnother->id,
        ],
    );

    $response = $this->setBasketDeliveryData(
        $requestAnother,
        $this->accessToken,
        $buyerToken,
    );

    expect($response)
        ->assertStatus(200);
})->with('set delivery data valid');



