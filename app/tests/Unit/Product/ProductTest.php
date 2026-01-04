<?php

use Domain\Product\Models\Category;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Tests\BuyerTestHelper;
use Tests\Unit\Product\ProductHelper;
use Tests\Unit\Product\ProductRequests;

uses(ProductRequests::class);

uses()->group('unit');
uses()->group('product');
uses()->group('product_unit');

describe('product', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->tokenData = $this->createUserToken($this->user);
        $this->accessToken = $this->tokenData['access_token'];

        $this->city = $this->createCity();

        $this->store = $this->createStore($this->city);

        $this->farmer = $this->createFarmer();
        $unit = $this->createUnit('кг');
        $count = 1;

        $this->product = $this->createProduct(
            $unit->system_id,
            false,
            $this->farmer->system_id,
        );
        $this->product = $this->addLeftovers($this->product, $this->store, $count);

        $this->createCategories();
        $this->parentCategory = Category::query()->first();
        $this->category = Category::query()->where('parent_system_id', $this->parentCategory->system_id)->first();

        $this->associateCategory($this->category, $this->product);

        BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
        BuyerTestHelper::setValueBuyerStore($this->store);
        BuyerTestHelper::getValueBuyerStore($this->store);
        BuyerTestHelper::getIdBuyerStore($this->store);
        BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);
    });

    it('get product', function () {
        $response = $this->getProduct(
            $this->product->slug,
            $this->accessToken,
        );

        ProductHelper::getProductExpect($response);
    });

    it('get not exist product', function ($data) {
        $response = $this->getProduct(
            'not_exist',
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(404);
    })->with('get not exist product');

    it('get product review', function () {

        $this->createProductReview($this->user, $this->product);

        $response = $this->getProductReviews(
            $this->product->slug,
            $this->accessToken,
        );

        ProductHelper::getProductReviewsExpect($response);
    });

    it('post product review', function ($data) {
        $response = $this->postProductReview(
            $this->product->slug,
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)->assertStatus(200);

        ProductHelper::getProductReviewExpect($response);
    })->with('create review product');

    it('post product review empty data', function ($data) {
        $response = $this->postProductReview(
            $this->product->slug,
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(302);
    })->with('create review product empty data');

    it('post product review invalid data', function ($data) {
        $response = $this->postProductReview(
            $this->product->slug,
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(302);
    })->with('create review product invalid data');

    it('search product', function ($data) {
        $response = $this->searchProduct(
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);

    })->with('search product');

    it('related product', function () {
        $unit = $this->createUnit('кг');
        $count = 1;

        $relatedProduct = $this->createProduct(
            $unit->system_id,
            false,
        );
        $relatedProduct = $this->addLeftovers($relatedProduct, $this->store, $count);

        $this->addRelatedProduct($this->product, $relatedProduct);

        $response = $this->getRelatedProduct(
            $this->product->slug,
            $this->accessToken,
        );

        ProductHelper::getRelatedProductExpect($response);
    });
});
