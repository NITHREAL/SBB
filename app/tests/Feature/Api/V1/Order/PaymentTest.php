<?php

namespace Tests\Feature\Api\V1\Order;

use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Order\OrderRequests;

uses(OrderRequests::class);

uses()->group('feature');
uses()->group('payment');

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

    $basket = $this->createBasket(
        $this->user,
        $this->store,
        [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ],
    );

    $this->associateBasket($basket, $this->product, 1);
});

it('check online payment without binding', function ($data) {

    $response = $this->createOrder(
        Arr::get($data, 'request'),
        $this->accessToken,
    );

    expect($response)
        ->assertStatus(200);

    $order = Order::query()->where('id', Arr::get($response, '0.id'))->with('payments')->first();

    $payment = OnlinePayment::query()->where('id', $order->payments->first()->id)->first();

    expect(Arr::get($response, '0.status'))->toEqual('waiting_payment')
        ->and(Arr::get($response, '0.paymentType'))->toEqual('by_online')
        ->and($payment)->toBeTruthy()
        ->and($payment->amount)->toEqual(20.00)
        ->and($payment->getAttribute('status'))->toEqual('registered')
        ->and($payment->payed)->toEqual(0)
    ;

})->with('create order online');
