<?php

use Domain\Product\Models\Category;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Tests\BuyerTestHelper;
use Tests\Unit\ProductGroup\ProductGroupHelper;
use Tests\Unit\ProductGroup\ProductGroupRequests;

uses(ProductGroupRequests::class);

uses()->group('unit');
uses()->group('product');
uses()->group('productGroup');
uses()->group('productGroup_unit');

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

        $this->productGroup = $this->createProductGroup();

        $this->associateProductGroup(
            $this->productGroup,
            $this->product,
        );

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

    it('get products group', function () {
        $response = $this->getProductsGroup(
            $this->accessToken,
        );

        ProductGroupHelper::getGroupExpect($response);
    });

    it('get product group', function () {
        $response = $this->getProductGroup(
            $this->productGroup->slug,
            ['store_system_id' => $this->store->system_id],
            $this->accessToken,
        );
        expect($response)
            ->assertStatus(200);
        ProductGroupHelper::getProductGroupExpect($response);
    });

    it('get product group with filters vegan true', function ($data) {
        $response = $this->getProductGroup(
            $this->productGroup->slug,
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);
        ProductGroupHelper::getProductGroupExpect($response, true);
    })->with('get group product with filters');

    it('get product group with filters vegan false', function ($data) {
        $response = $this->getProductGroup(
            $this->productGroup->slug,
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(200);
        ProductGroupHelper::getProductGroupExpect($response);
    })->with('get group product with filters vegan false');

    it('get product group with filters invalid', function ($data) {
        $response = $this->getProductGroup(
            $this->productGroup->slug,
            Arr::get($data, 'request'),
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(422)
            ->assertExactJson($data['error'])
        ;
    })->with('get group product with filters invalid');
});
