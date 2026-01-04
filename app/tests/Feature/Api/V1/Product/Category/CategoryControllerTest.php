<?php

namespace Tests\Feature\Api\V1\Product\Category;

use Domain\Product\Models\Category;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Tests\BuyerTestHelper;
use Tests\Unit\Product\Category\CategoryRequests;

uses(CategoryRequests::class);

uses()->group('feature');
uses()->group('product');
uses()->group('category');
uses()->group('category_feature');

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
    $this->category = Category::query()
        ->where(
            'parent_system_id',
            $this->parentCategory->system_id,
        )
        ->first();

    $this->associateCategory($this->category, $this->product);

    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);
});

it('check category cache', function () {
    $response = $this->getCategories(
        $this->store->system_id,
        $this->accessToken,
    );
    expect($response)->assertStatus(200);

    $categoriesCached = Arr::first(
        Cache::get(
            sprintf(
                '%s%s',
                'categories_store_',
                $this->store->system_id,
            )
        )
    );

    $response = json_decode($response->content(), true);

    $responseChilds = Arr::get(Arr::first($response),'childs');

    expect($categoriesCached->slug)
        ->toEqual(Arr::get(Arr::first($response), 'slug'))
        ->and($categoriesCached->childs->first()->slug)
        ->toEqual(Arr::get(Arr::first($responseChilds), 'slug'))
    ;
});


