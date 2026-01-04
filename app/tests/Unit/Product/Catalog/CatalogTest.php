<?php

use Domain\Product\Models\Category;
use Domain\User\Models\User;
use Tests\BuyerTestHelper;
use Tests\Unit\Product\Catalog\CatalogHelper;
use Tests\Unit\Product\Catalog\CatalogRequests;

uses(CatalogRequests::class);

uses()->group('unit');
uses()->group('product');
uses()->group('catalog');
uses()->group('catalog_unit');

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

    it('get catalog', function () {
        $response = $this->getCatalogProduct(
            $this->category->slug,
            $this->accessToken,
            ['store_system_id' => $this->store->system_id],
        );

        CatalogHelper::getCatalogExpect($response);
    });

    it('get catalog invalid', function () {
        $response = $this->getCatalogProduct(
            'invalid',
            $this->accessToken,
            ['store_system_id' => $this->store->system_id],
        );

        expect($response)
            ->assertStatus(404)
        ;
    });

    it('get catalog preview', function () {
        $response = $this->getCatalogPreview(
            $this->parentCategory->slug,
            $this->accessToken,
        );

        CatalogHelper::getCatalogPreviewExpect($response);
    });

    it('get catalog preview invalid', function () {
        $response = $this->getCatalogPreview(
            'invalid',
            $this->accessToken,
        );

        expect($response)
            ->assertStatus(404)
        ;
    });
});
