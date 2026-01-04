<?php

namespace Tests\Unit\Product\Category;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CategoryRequests extends TestCase
{
    public function getCategories(
        string $store_system_id,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(sprintf(
                '/api/v1/categories?storeOneCId=%s',
                $store_system_id,
            ));
    }
}
