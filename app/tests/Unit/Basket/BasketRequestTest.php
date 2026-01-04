<?php

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Infrastructure\Services\Buyer\Facades\BuyerStore;
use Tests\BuyerTestHelper;
use Tests\Unit\Basket\BasketHelper;
use Tests\Unit\Basket\BasketRequests;

uses(BasketRequests::class);

uses()->group('unit');
uses()->group('basket');
uses()->group('basket_unit');

describe('basket', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);
        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);
    });

    it('has valid basket structure', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $response = $this->getBasket(
            $this->accessToken,
        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getEmptyBasketExpect($response);
    });

    it('set delivery by basket', function ($data) {
        $coordinates = BasketHelper::$coordinates;

        $this->createPolygon($this->store, $coordinates);
        $polygonType = $this->createPolygonType('pickup');
        $this->createStoreScheduleWeekday($this->store, $polygonType);

        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $response = $this->setBasketDeliveryData(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getEmptyBasketExpect($response);

    })->with('set delivery data valid');

    it('add product in basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);

        $unit = $this->createUnit('кг');
        $count = 1;

        $product = $this->createProduct($unit->system_id,false);
        $product = $this->addLeftovers($product, $this->store, $count);

        $response = $this->addProduct(
            $product->id,
            $this->accessToken,
        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getExpectWithProducts($response, $product, $count);

        expect(Arr::get($response, 'promocode'))->toBeNull()
            ->and(Arr::get($response, 'coupon'))->toBeNull()
            ->and(Arr::get($response, 'couponTitle'))->toBeNull();
    });

    it('increment product in basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);

        $unit = $this->createUnit('кг');
        $count = 2;

        $product = $this->createProduct($unit->system_id,false);
        $product = $this->addLeftovers($product, $this->store, $count);

        $delivery = [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ];

        $basket = $this->createBasket(
            $this->user,
            $this->store,
            $delivery,
        );

        $this->associateBasket($basket, $product, 1);

        $response = $this->incrementProduct(
            $product->id,
            $this->accessToken,

        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getExpectWithProducts($response, $product, 2);

        expect(Arr::get($response, 'promocode'))->toBeNull()
            ->and(Arr::get($response, 'coupon'))->toBeNull()
            ->and(Arr::get($response, 'couponTitle'))->toBeNull();
    });

    it('decrement product in basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);

        $unit = $this->createUnit('кг');
        $count = 2;

        $product = $this->createProduct($unit->system_id,false);
        $product = $this->addLeftovers($product, $this->store, $count);

        $delivery = [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ];

        $basket = $this->createBasket(
            $this->user,
            $this->store,
            $delivery,
        );

        $this->associateBasket($basket, $product, 2);

        $response = $this->decrementProduct(
            $product->id,
            $this->accessToken,
        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getExpectWithProducts($response, $product, 1);

        expect(Arr::get($response, 'promocode'))->toBeNull()
            ->and(Arr::get($response, 'coupon'))->toBeNull()
            ->and(Arr::get($response, 'couponTitle'))->toBeNull();
    });

    it('delete product in basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $unit = $this->createUnit('кг');
        $count = 2;

        $product = $this->createProduct($unit->system_id,false);
        $product = $this->addLeftovers($product, $this->store, $count);

        $delivery = [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ];

        $basket = $this->createBasket(
            $this->user,
            $this->store,
            $delivery,
        );

        $this->associateBasket($basket, $product, 2);

        $response = $this->deleteProduct(
            $product->id,
            $this->accessToken,
        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getEmptyBasketExpect($response);
    });

    it('clear basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);

        $unit = $this->createUnit('кг');
        $count = 2;

        $product = $this->createProduct($unit->system_id,false);
        $product = $this->addLeftovers($product, $this->store, $count);

        $delivery = [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ];

        $basket = $this->createBasket(
            $this->user,
            $this->store,
            $delivery,
        );

        $this->associateBasket($basket, $product, 2);

        $response = $this->clearBasket(
            [
                'date'              => Arr::get(
                                            Arr::first($basket->delivery_params),
                                            'deliveryIntervalDate',
                                        ),
                'onlyUnavailable'   => false,
            ],
            $this->accessToken,
        );

        BasketHelper::getMainExpect($response);

        BasketHelper::getEmptyBasketExpect($response);
    });

    it('add not exists product in basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->addProduct(
            9999,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(BasketHelper::getNotExistsProductError());
    });

    it('increment not exists product in basket', function () {
        BuyerStore::shouldReceive('getSelectedStore')
            ->andReturn($this->store);
        BuyerStore::shouldReceive('setValue')
            ->andReturn($this->store->getAttribute('id'));

        $response = $this->incrementProduct(
            999,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(BasketHelper::getNotExistsInBasketProductError());
    });

    it('increment not enough product in store', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $unit = $this->createUnit('кг');
        $count = 1;

        $product = $this->createProduct($unit->system_id, false);
        $product = $this->addLeftovers($product, $this->store, $count);

        $delivery = [
            'delivery_type' => 'pickup',
            'delivery_sub_type' => 'other',
            'delivery_date' => Carbon::tomorrow()->format('Y-m-d'),
            'delivery_time' => 'other',
        ];

        $basket = $this->createBasket(
            $this->user,
            $this->store,
            $delivery,
        );

        $this->associateBasket($basket, $product, 1);

        $response = $this->incrementProduct(
            $product->id,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(BasketHelper::getNotEnoughProductError());
    });

    it('decrement not exists product in basket', function () {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->decrementProduct(
            999,
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(400)
            ->assertExactJson(BasketHelper::getNotExistsInBasketProductError());
    });

    it('set delivery by basket empty data', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->setBasketDeliveryData(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('set delivery empty data');

    it('set delivery by basket invalid types', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->setBasketDeliveryData(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('set delivery data invalid');

    it('clear basket empty data', function ($data) {
        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);

        $response = $this->clearBasket(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error']);
    })->with('clear basket invalid');
});


