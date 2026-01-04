<?php

namespace Tests\Unit\Product;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ProductRequests extends TestCase
{
    public function getProduct(
        string $slug,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/products/%s',
                    $slug,
                )
            );
    }

    public function postProductReview(
        string $slug,
        array $request,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post(
                sprintf(
                    '/api/v1/products/%s/review',
                    $slug,
                ),
                $request,
            );
    }

    public function getProductReviews(
        string $slug,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/products/%s/review',
                    $slug,
                )
            );
    }

    public function searchProduct(
        array $params,
        string $accessToken,
    ): TestResponse {

        $paramSearch = '';
        $valueSearch = '';
        $paramStore = '';
        $valueStore = '';
        if (array_key_exists('search', $params)) {
            $paramSearch = 'search';
            $valueSearch = $params['search'];
        }
        if (array_key_exists('storeOneCId', $params)) {
            $paramStore = 'storeOneCId';
            $valueStore = $params['storeOneCId'];
        }

        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/products/search?%s=%s&%s=%s',
                    $paramSearch,
                    $valueSearch,
                    $paramStore,
                    $valueStore,
                )
            );
    }

    public function getRelatedProduct(
        string $slug,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/products/%s/related',
                    $slug,
                )
            );
    }
}
