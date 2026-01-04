<?php

namespace Tests\Unit\ProductGroup;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

class ProductGroupHelper
{
    public static array $groupStructure = [
        [
            'id',
            'title',
            'slug',
            'image',
            'imageBlurHash',
            'sort',
        ]
    ];

    public static array $productGroupStructure = [
        'productGroup' => [
            'id',
            'title',
            'slug',
            'image',
            'imageBlurHash',
            'backgroundImage',
            'story',
        ],
        'products',
        'filters' => [],
        'pagination' => [
            'total',
            'currentPage',
            'lastPage',
            'limit',
        ],
    ];


    public static function getGroupExpect(TestResponse $response): void
    {
        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$groupStructure);

        $response = json_decode($response->content(), true);

        $group = Arr::first($response);

        expect(Arr::get($group, 'id'))->toBeInt()
            ->and(Arr::get($group, 'title'))->toBeString()
            ->and(Arr::get($group, 'slug'))->toBeString()
            ->and(Arr::get($group, 'image'))->toBeNull()
            ->and(Arr::get($group, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($group, 'sort'))->toBeInt()
            ;
    }

    public static function getProductGroupExpect(
        TestResponse $response,
        bool $vegan = false,
    ): void {
        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$productGroupStructure);

        $response = json_decode($response->content(), true);

        $productGroup = Arr::get($response, 'productGroup');
        $products = Arr::get($response, 'products');
        $filters = Arr::get($response, 'filters');
        $limit = Arr::get($response, 'limit');

        expect(Arr::get($productGroup, 'id'))->toBeInt()
            ->and(Arr::get($productGroup, 'title'))->toBeString()
            ->and(Arr::get($productGroup, 'slug'))->toBeString()
            ->and(Arr::get($productGroup, 'image'))->toBeNull()
            ->and(Arr::get($productGroup, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($productGroup, 'backgroundImage'))->toBeNull()
            ->and(Arr::get($productGroup, 'story'))->toBeNull()
            ->and(Arr::get($limit, 'total'))->toBeNull()
            ->and(Arr::get($limit, 'currentPage'))->toBeNull()
            ->and(Arr::get($limit, 'lastPage'))->toBeNull()
            ->and(Arr::get($limit, 'limit'))->toBeNull()
        ;
        if ($filters) {
            self::getFiltersExpect($filters);
        }
        if ($vegan) {
            self::getEmptyProductsExpect($products);
        } else self::getProductsExpect($products);

    }

    public static function getFiltersExpect(array $filters): void
    {
        expect($filters)->toBeArray();

        $filter = Arr::first($filters);
        $items = Arr::first(Arr::get($filter, 'items'));

        expect(Arr::get($filter, 'title'))->toBeString()
            ->and(Arr::get($filter, 'items'))->toBeArray()
            ->and(Arr::get($items, 'title'))->toBeString()
            ->and(Arr::get($items, 'slug'))->toBeString()
            ->and(Arr::get($items, 'displayType'))->toBeString()
            ->and(Arr::get($items, 'isAvailable'))->toBeBool()
            ->and(Arr::get($items, 'isSelected'))->toBeBool()
        ;
    }

    public static function getProductsExpect(array $products): void
    {
        $product = Arr::first($products);

        expect(Arr::get($product, 'id'))->toBeInt()
            ->and(Arr::get($product, 'image'))->toBeNull()
            ->and(Arr::get($product, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($product, 'title'))->toBeString()
            ->and(Arr::get($product, 'slug'))->toBeString()
            ->and(Arr::get($product, 'rating'))->toBeInt()
            ->and(Arr::get($product, 'unit'))->toBeString()
            ->and(Arr::get($product, 'weight'))->toEqual(0)
            ->and(Arr::get($product, 'inStock'))->toBeBool()
            ->and(Arr::get($product, 'countInBaskets'))->toEqual(0)
            ->and(Arr::get($product, 'priceDiscount'))->toBeNull()
            ->and(Arr::get($product, 'priceUnit'))->toBeString()
            ->and(Arr::get($product, 'price'))->toBeFloat()
            ->and(Arr::get($product, 'dateSupply'))->toBeString()
            ->and(Arr::get($product, 'deliveryInCountry'))->toBeBool()
            ->and(Arr::get($product, 'byPreorder'))->toBeBool()
            ->and(Arr::get($product, 'cooking'))->toBeBool()
            ->and(Arr::get($product, 'availableCount'))->toBeInt()
            ->and(Arr::get($product, 'canBuy'))->toBeBool()
            ->and(Arr::get($product, 'favorited'))->toBeBool()
            ->and(Arr::get($product, 'categoryId'))->toBeNull()
            ->and(Arr::get($product, 'farmer'))->toBeArray()
            ->and(Arr::get($product, 'tags'))->toBeArray()
        ;
    }

    public static function getEmptyProductsExpect(array $products): void
    {
        expect($products)->toHaveCount(0)
        ;
    }
}
