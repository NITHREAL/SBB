<?php

namespace Tests\Feature\Api\V1\Order;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Tests\BuyerTestHelper;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Order\OrderHelper;
use Tests\Unit\Order\OrderRequests;

uses(OrderRequests::class);

uses()->group('feature');
uses()->group('order');
uses()->group('order_feature');

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

    $unit = $this->createUnit('кг');
    $count = 1;

    $this->product = $this->createProduct($unit->system_id,false);
    $this->product = $this->addLeftovers($this->product, $this->store, $count);
    $this->product->setAttribute('unit1cId', $this->product->unit_system_id );

    $this->price = $this->product->price;

    $this->basket = $this->createBasket(
        $this->user,
        $this->store,
        [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ],
    );

    $this->associateBasket($this->basket, $this->product, 1);
});

it('check basket and default payment type after create order', function ($data) {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

    expect($this->basket->products)->toHaveCount(1)
        ->and($this->user->default_payment_type)->toBeNull();

    $this->buyerToken = Arr::get($this->basket, 'token');

    $response = $this->createOrder(
        Arr::get($data, 'request'),
        $this->accessToken,
    );

    $this->basket->refresh();

    expect($response)
        ->assertStatus(200)
        ->and($this->basket->products)->toHaveCount(0);
})->with('create order');

it('check repeat order products', function () {
    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

    $order = $this->createMockOrder(
        OrderHelper::PAYMENT_IN_STORE,
        OrderStatusEnum::completed()->value,
        $this->store,
        $this->user,
    );
    $this->associateOrder($order, $this->product);


    $response = $this->repeatOrder(
        $order->id,
        $this->accessToken,
    );

    expect($response)
        ->assertStatus(200);

    $basket = Arr::first(
        Arr::get($response, 'baskets')
    );

    $orderProductsCount = ($order->products)->count();

    $orderProduct = $order->products->first();

    $responseProduct = Arr::first(
        Arr::get($basket, 'products')
    );

    expect(Arr::get($basket, 'products'))->toHaveCount($orderProductsCount)
        ->and($orderProduct->id)->toEqual(Arr::get($responseProduct, 'id'))
        ->and($orderProduct->getAttribute('system_id'))->toEqual(Arr::get($responseProduct, 'id1c'))
        ->and($orderProduct->pivot->count)->toEqual(Arr::get($responseProduct, 'count'));
});
