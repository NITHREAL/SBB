<?php

use Domain\Product\Models\Category;
use Domain\User\Models\User;
use Tests\BuyerTestHelper;
use Tests\Unit\Product\Category\CategoryHelper;
use Tests\Unit\Product\Category\CategoryRequests;

uses(CategoryRequests::class);

uses()->group('unit');
uses()->group('product');
uses()->group('category');
uses()->group('category_unit');

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

    it('get category', function () {
        $response = $this->getCategories(
            $this->store->system_id,
            $this->accessToken,
        );
        CategoryHelper::getCategoriesExpect($response);
    });
});
