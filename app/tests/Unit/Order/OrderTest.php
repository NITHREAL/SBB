<?php

use Domain\Basket\Services\BasketService;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Tests\BuyerTestHelper;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Order\OrderHelper;
use Tests\Unit\Order\OrderRequests;

uses(OrderRequests::class);

uses()->group('unit');
uses()->group('order');
uses()->group('order_unit');

describe('order', function () {

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

    it('create order', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $response = $this->createOrder(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);

        OrderHelper::getCreateExpect($response);

    })->with('create order');

    it('get order', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_IN_STORE,
            OrderStatusEnum::created()->value,
            $this->store,
            $this->user,
        );
        $this->associateOrder($order, $this->product);

        $response = $this->getOrder(
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);

        OrderHelper::getOrderExpect($response);

        OrderHelper::getOrderFieldsExpect($response);
    });

    it('get orders pending', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::waitingPayment()->value,
            $this->store,
            $this->user,
        );
        $this->associateOrder($order, $this->product);

        $response = $this->getOrders(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);

        OrderHelper::getOrdersExpect($response);

        OrderHelper::getOrderFieldsExpect(
            Arr::first(
                Arr::get($response, 'orders')
            )
        );

        OrderHelper::getOrdersPendingStatusesExpect($response);
    })->with('get orders pending');

    it('get orders finished', function ($data) {
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

        $response = $this->getOrders(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);

        OrderHelper::getOrdersExpect($response);

        OrderHelper::getOrderFieldsExpect(
            Arr::first(
                Arr::get($response, 'orders')
            )
        );

        OrderHelper::getOrdersFinishedStatusesExpect($response);

        OrderHelper::getOrderPriceExpect($response, $order);
    })->with('get orders finished');

    it('get orders empty data', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->getOrders(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('get orders empty data');

    it('get orders invalid types', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->getOrders(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('get orders invalid types');

    it('repeat order', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_IN_STORE,
            OrderStatusEnum::created()->value,
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

        OrderHelper::getOrderRepeatExpect($this->product, $response, 1);
    });

    it('cancel order', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_IN_STORE,
            OrderStatusEnum::created()->value,
            $this->store,
            $this->user,
        );
        $this->associateOrder($order, $this->product);

        $response = $this->cancelOrder(
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200)
            ->and(Arr::get($response, 'status'))->toEqual('canceled_by_customer');

        OrderHelper::getOrderExpect($response);
    });

    it('cancel order error with delivering status', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::delivering()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $this->product);

        $response = $this->cancelOrder(
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(OrderHelper::getCanNotCancelOrderError());
    });

    it('cancel order error with completed status', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::completed()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $this->product);

        $response = $this->cancelOrder(
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(OrderHelper::getCanNotCancelOrderError());
    });

    it('cancel order error with by_order product', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $unit = $this->createUnit('кг');

        $productPreorder = $this->createProduct($unit->system_id,true);
        $productPreorder = $this->addLeftovers($productPreorder, $this->store, 0);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::completed()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $productPreorder);

        $response = $this->cancelOrder(
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(OrderHelper::getCanNotCancelOrderError());
    });

    it('cancel order error wrong user', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $user = $this->createUser();

        $tokenData = $this->createUserToken($user);

        $accessToken = $tokenData['access_token'];

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::created()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $this->product);

        $response = $this->cancelOrder(
            $order->id,
            $accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(OrderHelper::getOrderWrongUserError());
    });

    it('create order invalid date', function ($data) {
        $basketService = Mockery::mock(BasketService::class);
        $basketService->shouldReceive('getBasket')
            ->once()
            ->andReturn(Arr::get($data, 'mockData'));
        $this->app->bind(BasketService::class, fn() => $basketService);

        $response = $this->createOrder(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson($data['error']);
    })->with('create order invalid basket date');

    it('create order invalid types', function ($data) {
        $response = $this->createOrder(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('create order invalid types');

    it('create order empty request', function ($data) {
        $response = $this->createOrder(
            $data['request'],
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('create order empty data');

    it('post orders review', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $unit = $this->createUnit('кг');

        $productPreorder = $this->createProduct($unit->system_id,true);
        $productPreorder = $this->addLeftovers($productPreorder, $this->store, 0);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::completed()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $productPreorder);

        $response = $this->reviewOrder(
            Arr::get($data, 'request'),
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);

        OrderHelper::getOrderReviewExpect($response);

    })->with('post orders review');

    it('post orders review invalid', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $unit = $this->createUnit('кг');

        $productPreorder = $this->createProduct($unit->system_id,true);
        $productPreorder = $this->addLeftovers($productPreorder, $this->store, 0);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::completed()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $productPreorder);

        $response = $this->reviewOrder(
            Arr::get($data, 'request'),
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('post orders review invalid');

    it('post orders review empty data', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $unit = $this->createUnit('кг');

        $productPreorder = $this->createProduct($unit->system_id,true);
        $productPreorder = $this->addLeftovers($productPreorder, $this->store, 0);

        $order = $this->createMockOrder(
            OrderHelper::PAYMENT_ONLINE,
            OrderStatusEnum::completed()->value,
            $this->store,
            $this->user,
            OrderHelper::DELIVERY_TYPE_DELIVERY,
        );
        $this->associateOrder($order, $productPreorder);

        $response = $this->reviewOrder(
            Arr::get($data, 'request'),
            $order->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('post orders review empty data');
});
