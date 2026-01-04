<?php

namespace Tests\Unit\ProductGroup;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ProductGroupRequests extends TestCase
{
    public function getProductsGroup(
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get('/api/v1/product-groups',);
    }

    public function getProductGroup(
        string $slug,
        array $params,
        string $accessToken,
    ): TestResponse {

        if (array_key_exists('filter', $params)) {
            $url = sprintf(
                '/api/v1/product-groups/%s?store_system_id=%s&filter[available_today]=%s&filter[for_vegan]=%s',
                $slug,
                $params['store_system_id'],
                $params['filter']['available_today'],
                $params['filter']['for_vegan'],
            );
        } else {
            $url = sprintf(
                '/api/v1/product-groups/%s?store_system_id=%s',
                $slug,
                $params['store_system_id'],
            );
        }

        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get($url);
    }
}
