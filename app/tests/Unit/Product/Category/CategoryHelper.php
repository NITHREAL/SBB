<?php

namespace Tests\Unit\Product\Category;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

class CategoryHelper
{
    public static function getCategoriesExpect(TestResponse $response): void
    {
        expect($response)
            ->assertStatus(200)
            ;

        $response = json_decode($response->content(), true);
        expect($response)->toBeArray();

        $category = Arr::first($response);

        self::getParentCategoryExpect($category);

        $child = Arr::first(Arr::get($category, 'childs'));

        self::getCategoryExpect($child);

    }

    public static function getCategoryExpect(array $category): void
    {
        expect(Arr::get($category, 'id'))->toBeInt()
            ->and(Arr::get($category, 'parentId'))->toBeInt()
            ->and(Arr::get($category, 'slug'))->toBeString()
            ->and(Arr::get($category, 'image'))->toBeNull()
            ->and(Arr::get($category, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($category, 'title'))->toBeString()
            ->and(Arr::get($category, 'marginLeft'))->toBeInt()
            ->and(Arr::get($category, 'marginRight'))->toBeInt()
            ->and(Arr::get($category, 'level'))->toBeInt()
        ;
    }

    public static function getParentCategoryExpect(array $category): void
    {
        expect(Arr::get($category, 'id'))->toBeInt()
            ->and(Arr::get($category, 'parentId'))->toBeNull()
            ->and(Arr::get($category, 'slug'))->toBeString()
            ->and(Arr::get($category, 'image'))->toBeNull()
            ->and(Arr::get($category, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($category, 'title'))->toBeString()
            ->and(Arr::get($category, 'marginLeft'))->toBeInt()
            ->and(Arr::get($category, 'marginRight'))->toBeInt()
            ->and(Arr::get($category, 'level'))->toBeInt()
        ;
    }
}
