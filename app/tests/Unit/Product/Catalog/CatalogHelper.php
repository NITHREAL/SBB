<?php

namespace Tests\Unit\Product\Catalog;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

class CatalogHelper
{
    public static array $catalogStructure = [
        'category' => [
            'id',
            'title',
            'slug',
        ],
        'products' => [[
            'id',
            'image',
            'imageBlurHash',
            'title',
            'slug',
            'rating',
            'unit',
            'weight',
            'inStock',
            'countInBasket',
            'price',
            'priceDiscount',
            'priceUnit',
            'dateSupply',
            'deliveryInCountry',
            'byPreorder',
            'cooking',
            'availableCount',
            'canBuy',
            'farmer',
            'favorited',
            'categoryId',
            'tags',
        ]],
        'filters' => [
            [
                'title',
                'items' => [[
                    'title',
                    'slug',
                    'displayType',
                    'isAvailable',
                    'isSelected',
                ]]
            ]
        ],
        'pagination' => [
            'total',
            'currentPage',
            'lastPage',
            'limit',
        ],
    ];

    public static array $catalogPreviewStructure = [
        'id',
        'title',
        'slug',
        'childs' => [[
            'id',
            'title',
            'slug',
            'products' => [[
                'id',
                'image',
                'title',
                'imageBlurHash',
                'slug',
                'rating',
                'unit',
                'weight',
                'inStock',
                'countInBasket',
                'priceDiscount',
                'priceUnit',
                'price',
                'dateSupply',
                'deliveryInCountry',
                'availableCount',
                'byPreorder',
                'cooking',
                'farmer' => [
                    'id',
                    'name',
                    'slug',
                ],
                'favorited',
                'tags',
            ]]
        ]]
    ];

    public static function getCatalogExpect(TestResponse $response): void
    {
        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$catalogStructure)
            ;

        $response = json_decode($response->content(), true);

        $category = Arr::get($response, 'category');
        $products = Arr::get($response, 'products');
        $filters = Arr::get($response, 'filters');
        $pagination = Arr::get($response, 'pagination');

        self::getCategoryExpect($category);
        self::getProductsExpect($products);
        self::getFiltersExpect($filters);
        self::getPaginationExpect($pagination);
    }

    public static function getCatalogPreviewExpect(TestResponse $response): void
    {
        expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$catalogPreviewStructure)
            ->and(Arr::get($response, 'childs'))->toBeArray()
            ;
        $response = json_decode($response->content(), true);

        self::getCategoryExpect($response);

        $child = Arr::first(Arr::get($response, 'childs'));

        self::getCategoryExpect($child);

        $products = Arr::get($child, 'products');

        self::getProductsExpect($products);
    }

    public static function getCategoryExpect(array $category): void
    {
        expect($category)->toBeArray()
            ->and(Arr::get($category, 'id'))->toBeInt()
            ->and(Arr::get($category, 'title'))->toBeString()
            ->and(Arr::get($category, 'slug'))->toBeString();
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

    public static function getPaginationExpect(array $pagination): void
    {
        expect($pagination)->toBeArray()
            ->and(Arr::get($pagination, 'total'))->toBeInt()
            ->and(Arr::get($pagination, 'currentPage'))->toBeInt()
            ->and(Arr::get($pagination, 'lastPage'))->toBeInt()
            ->and(Arr::get($pagination, 'limit'))->toBeInt()
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
            ->and(Arr::get($product, 'categoryId'))->toBeInt()
            ->and(Arr::get($product, 'farmer'))->toBeArray()
            ->and(Arr::get($product, 'tags'))->toBeArray()
            ;
    }
}
