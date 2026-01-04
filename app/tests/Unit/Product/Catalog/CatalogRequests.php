<?php

namespace Tests\Unit\Product\Catalog;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CatalogRequests extends TestCase
{
    public function getCatalogProduct(
        string $slug,
        string $accessToken,
        array|null $params,
    ): TestResponse {
        if (!empty($params) && array_key_exists('filter', $params)) {
            $url = sprintf(
                '/api/v1/products/catalog/%s?filter[available_today]=%s&filter[for_vegan]=%s',
                $slug,
                $params['filter']['available_today'],
                $params['filter']['for_vegan'],
            );
        } else {
            $url = sprintf(
                '/api/v1/products/catalog/%s',
                $slug,
            );
        }

        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get($url);
    }

    public function getCatalogPreview(
        string $slug,
        string $accessToken,

    ): TestResponse {


        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/products/catalog/preview/%s',
                    $slug,
                )
            );
    }
}
