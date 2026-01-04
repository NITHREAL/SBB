<?php

namespace Tests\Feature\Api\V1\Product;

use Domain\Product\Models\Category;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Tests\BuyerTestHelper;
use Tests\Unit\Product\Catalog\CatalogRequests;

uses(CatalogRequests::class);

uses()->group('feature');
uses()->group('product');
uses()->group('catalog');
uses()->group('catalog_feature');

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
        )->first();

    $this->associateCategory($this->category, $this->product);

    BuyerTestHelper::getSelectedStoreBuyerStore($this->store);
    BuyerTestHelper::setValueBuyerStore($this->store);
    BuyerTestHelper::getValueBuyerStore($this->store);
    BuyerTestHelper::getIdBuyerStore($this->store);
    BuyerTestHelper::getOneCIdStoreBuyerStore($this->store);
});

it('check catalog preview cache', function () {
    $response = $this->getCatalogPreview(
        $this->parentCategory->slug,
        $this->accessToken,
    );
    expect($response)->assertStatus(200);

    $categoriesCached = Cache::get(
        sprintf(
            '%s%s',
            'category_',
            $this->parentCategory->slug,
        )
    );

    $response = json_decode($response->content(), true);

    expect($categoriesCached->slug)
        ->toEqual(Arr::get($response, 'slug'))
        ;

    $categoriesChildsCached = Cache::get(
        sprintf(
            '%s_%s_store_1c_id_%s',
            'categories_childs',
            $this->parentCategory->slug,
            $this->store->system_id,
        )
    );

    $responseChilds = Arr::first(Arr::get($response, 'childs'));

    $productCache = $categoriesChildsCached->first()->products->first();
    $responseCache = Arr::first(Arr::get($responseChilds,'products'));

    expect($categoriesChildsCached->first()->slug)->toEqual(Arr::get($responseChilds,'slug'))
        ->and($productCache->slug)->toEqual(Arr::get($responseCache,'slug'))
    ;
});

it('check catalog cache', function ($data) {
    $response = $this->getCatalogProduct(
        $this->category->slug,
        $this->accessToken,
        Arr::get($data, 'request'),
    );

    expect($response)->assertStatus(200);

    $categoriesCached = Cache::get(
        sprintf(
            '%s%s',
            'filters_list_',
            md5(json_encode(Arr::get($data, 'filter'))),
        )
    );

    $response = json_decode($response->content(), true);

    expect(Arr::get($response, 'filters'))
        ->toEqual($categoriesCached)
    ;
})->with('get catalog with filters');

