<?php

namespace Tests\Unit\Product;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Pest\Expectation;

class ProductHelper
{
    public static array $productStructure = [
        'id',
        'title',
        'description',
        'composition',
        'slug',
        'rating',
        'unit',
        'available',
        'byPreorder',
        'cooking',
        'countInBasket',
        'inStock',
        'dateSupply',
        'deliveryInCountry',
        'availableCount',
        'canBuy',
        'reviewed',
        'priceDiscount',
        'priceUnit',
        'price',
        'sum',
        'sumUnit',
        'weight',
        'images',
        'properties'    => [
            'proteins',
            'fats',
            'carbohydrates',
            'nutritionKcal',
            'nutritionKj',
            'storageConditions',
            'shelfLife',
            'vegan',
        ],
        'favorited',
        'categoryId',
        'farmer',
        'tags',
        'relatedProducts',
        'isReviewAvailability',
        'reviewCount',
    ];

    public static array $productReviewStructure = [
        [
            'id',
            'rating',
            'text',
            'date',
            'user'    => [
                'id',
                'firstName',
                'lastName',
            ],
        ]
    ];

    public static function getProductExpect(TestResponse $response): Expectation
    {
        $properties = Arr::get($response, 'properties');

        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$productStructure)
            ->and(Arr::get($response, 'id'))->toBeInt()
            ->and(Arr::get($response, 'title'))->toBeString()
            ->and(Arr::get($response, 'description'))->toBeString()
            ->and(Arr::get($response, 'composition'))->toBeString()
            ->and(Arr::get($response, 'couponTitle'))->toBeNull()
            ->and(Arr::get($response, 'slug'))->toBeString()
            ->and(Arr::get($response, 'rating'))->toBeInt()
            ->and(Arr::get($response, 'unit'))->toBeString()
            ->and(Arr::get($response, 'available'))->toBeBool()
            ->and(Arr::get($response, 'byPreorder'))->toBeBool()
            ->and(Arr::get($response, 'cooking'))->toBeBool()
            ->and(Arr::get($response, 'countInBaskets'))->toEqual(0)
            ->and(Arr::get($response, 'inStock'))->toBeBool()
            ->and(Arr::get($response, 'dateSupply'))->toBeString()
            ->and(Arr::get($response, 'deliveryInCountry'))->toBeBool()
            ->and(Arr::get($response, 'availableCount'))->toBeInt()
            ->and(Arr::get($response, 'couponTitle'))->toBeNull()
            ->and(Arr::get($response, 'canBuy'))->toBeBool()
            ->and(Arr::get($response, 'reviewed'))->toBeBool()
            ->and(Arr::get($response, 'priceDiscount'))->toBeNull()
            ->and(Arr::get($response, 'priceUnit'))->toBeString()
            ->and(Arr::get($response, 'price'))->toBeFloat()
            ->and(Arr::get($response, 'sum'))->toEqual(0)
            ->and(Arr::get($response, 'sumUnit'))->toBeString()
            ->and(Arr::get($response, 'weight'))->toEqual(0)
            ->and(Arr::get($response, 'images'))->toBeNull()
            ->and(Arr::get($response, 'properties'))->toBeArray()
            ->and(Arr::get($response, 'favorited'))->toBeBool()
            ->and(Arr::get($response, 'categoryId'))->toBeInt()
            ->and(Arr::get($response, 'farmer'))->toBeArray()
            ->and(Arr::get($response, 'tags'))->toBeArray()
            ->and(Arr::get($response, 'relatedProducts'))->toBeArray()
            ->and(Arr::get($response, 'isReviewAvailability'))->toBeBool()
            ->and(Arr::get($response, 'reviewCount'))->toBeInt()
            ->and(Arr::get($properties, 'proteins'))->toBeFloat()
            ->and(Arr::get($properties, 'fats'))->toBeNumeric()
            ->and(Arr::get($properties, 'carbohydrates'))->toBeNumeric()
            ->and(Arr::get($properties, 'nutritionKcal'))->toBeNumeric()
            ->and(Arr::get($properties, 'nutritionKj'))->toBeNumeric()
            ->and(Arr::get($properties, 'storageConditions'))->toBeString()
            ->and(Arr::get($properties, 'shelfLife'))->toBeInt()
            ->and(Arr::get($properties, 'vegan'))->toBeBetween(0, 1)
            ;
    }

    public static function getProductReviewsExpect(TestResponse $response): void
    {
        expect($response)->assertStatus(200)
            ->assertJsonStructure(self::$productReviewStructure)
        ;

        $response = json_decode($response->content(), true);
        expect($response)->toBeArray();

        $review = Arr::first($response);

        ProductHelper::getProductReviewExpect($review);
    }

    public static function getProductReviewExpect(TestResponse|array $review): Expectation
    {
        $user = Arr::get($review, 'user');

        return expect(Arr::get($review, 'id'))->toBeInt()
            ->and(Arr::get($review, 'rating'))->toBeInt()
            ->and(Arr::get($review, 'text'))->toBeString()
            ->and(Arr::get($review, 'date'))->toBeString()
            ->and(Arr::get($review, 'user'))->toBeArray()
            ->and(Arr::get($user, 'id'))->toBeInt()
            ->and(Arr::get($user, 'firstName'))->toBeString()
            ->and(Arr::get($user, 'lastName'))->toBeString()
            ;
    }

    public static function getRelatedProductExpect(TestResponse $response): Expectation
    {
        expect($response)
            ->assertStatus(200)
        ;

        $response = json_decode($response->content(), true);

        $relatedProduct = Arr::first($response);

        return expect(Arr::get($relatedProduct, 'id'))->toBeInt()
            ->and(Arr::get($relatedProduct, 'image'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'title'))->toBeString()
            ->and(Arr::get($relatedProduct, 'slug'))->toBeString()
            ->and(Arr::get($relatedProduct, 'rating'))->toBeInt()
            ->and(Arr::get($relatedProduct, 'unit'))->toBeString()
            ->and(Arr::get($relatedProduct, 'weight'))->toEqual(0)
            ->and(Arr::get($relatedProduct, 'inStock'))->toBeBool()
            ->and(Arr::get($relatedProduct, 'countInBaskets'))->toEqual(0)
            ->and(Arr::get($relatedProduct, 'priceDiscount'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'priceUnit'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'price'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'dateSupply'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'deliveryInCountry'))->toBeBool()
            ->and(Arr::get($relatedProduct, 'byPreorder'))->toBeBool()
            ->and(Arr::get($relatedProduct, 'cooking'))->toBeBool()
            ->and(Arr::get($relatedProduct, 'availableCount'))->toBeInt()
            ->and(Arr::get($relatedProduct, 'canBuy'))->toBeBool()
            ->and(Arr::get($relatedProduct, 'favorited'))->toBeBool()
            ->and(Arr::get($relatedProduct, 'categoryId'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'farmer'))->toBeNull()
            ->and(Arr::get($relatedProduct, 'tags'))->toBeArray()
            ;
    }
}
