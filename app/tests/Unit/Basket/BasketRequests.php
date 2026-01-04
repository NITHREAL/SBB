<?php

namespace Tests\Unit\Basket;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BasketRequests extends TestCase
{
    public function getBasket(
        string $accessToken,
        string $buyerToken = null,
    ): TestResponse {
        return static::withHeaders(
            [
                'Authorization' => "Bearer {$accessToken}",
                'Bit-Buyer-Token' => $buyerToken,
            ]
        )
            ->get('/api/v1/basket');
    }

    public function addProduct(
        int $productId,
        string $accessToken,
        string $buyerToken = null,
    ): TestResponse {$headers = [
        'Authorization' => "Bearer {$accessToken}"
    ];

        if ($buyerToken) {
            Arr::add($headers, 'Bit-Buyer-Token', $buyerToken);
        }

        return static::withHeaders($headers)
            ->put(
                sprintf('/api/v1/basket/%u',
                    $productId,
                )
            );
    }

    public function deleteProduct(
        int $productId,
        string $accessToken,
        string $buyerToken = null,
    ): TestResponse {
        $headers = [
            'Authorization' => "Bearer {$accessToken}"
        ];

        if ($buyerToken) {
            Arr::add($headers, 'Bit-Buyer-Token', $buyerToken);
        }

        return static::withHeaders($headers)
            ->delete(
                sprintf('/api/v1/basket/%u',
                    $productId,
                )
            );
    }

    public function incrementProduct(
        int $productId,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(
            [
                'Authorization' => "Bearer {$accessToken}",
            ]
        )
            ->patch(
                sprintf('/api/v1/basket/increment/%u',
                    $productId,
                )
            );
    }

    public function decrementProduct(
        int $productId,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(
            [
                'Authorization' => "Bearer {$accessToken}",
            ]
        )
            ->patch(
                sprintf('/api/v1/basket/decrement/%u',
                    $productId,
                )
            );
    }

    public function clearBasket(
        array $request,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(
            [
                'Authorization' => "Bearer {$accessToken}",
            ]
        )
            ->post('/api/v1/basket/clear', $request);
    }

    public function setBasketDeliveryData(
        array $request,
        string $accessToken,
        string $buyerToken = null,
    ): TestResponse {
        $headers = [
            'Authorization' => "Bearer {$accessToken}"
        ];

        if ($buyerToken) {
            Arr::add($headers, 'Bit-Buyer-Token', $buyerToken);
        }

        return static::withHeaders($headers)
            ->post('/api/v1/basket/delivery', $request);
    }

    public function setPromocode(
        array $request,
        string $accessToken,
        string $buyerToken,
    ): TestResponse {
        return static::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Bit-Buyer-Token' => $buyerToken,
        ])
            ->post('/api/v1/basket/promocode/set', $request);
    }

    public function clearPromocode(
        string $accessToken,
        string $buyerToken,
    ): TestResponse {
        return static::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Bit-Buyer-Token' => $buyerToken,
        ])
            ->post('/api/v1/basket/promocode/clear');
    }

    public function setCoupon(
        array $request,
        string $accessToken,
        string $buyerToken,
    ): TestResponse {
        return static::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Bit-Buyer-Token' => $buyerToken,
        ])
            ->post('/api/v1/basket/coupon/set', $request);
    }

    public function clearCoupon(
        string $accessToken,
        string $buyerToken,
    ): TestResponse {
        return static::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Bit-Buyer-Token' => $buyerToken,
        ])
            ->post('/api/v1/basket/coupon/clear');
    }
}
