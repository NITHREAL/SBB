<?php

namespace Tests\Unit\Order;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class OrderRequests extends TestCase
{
    public function createOrder(
        array $request,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/order', $request);
    }

    public function getOrders(
        array $params,
        string $accessToken,
    ): TestResponse {

        $param = '';
        $value = '';
        if (array_key_exists('state', $params)) {
            $param = 'state';
            $value = $params['state'];
        }
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/user/orders?requestFrom=%s&%s=%s',
                    $params['requestFrom'] ?? '',
                    $param,
                    $value,
                )
            );
    }

    public function getOrder(
        int $id,
        string $accessToken,
    ): TestResponse {

        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/user/orders/%s',
                    $id,
                )
            );
    }

    public function repeatOrder(
        int $id,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post(
                sprintf(
                    '/api/v1/user/orders/%s/repeat',
                    $id,
                )
            );
    }

    public function reviewOrder(
        array $request,
        int $id,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post(
                sprintf(
                    '/api/v1/user/orders/%s/review',
                    $id,
                ),
                $request,
            );
    }


    public function cancelOrder(
        int $id,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post(
                sprintf(
                    '/api/v1/user/orders/%s/cancel',
                    $id,
                )
            );
    }

    public function collectOrder(
        array $request,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/order/collect', $request);
    }

    public function completeOrder(
        array $request,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/order/complete', $request);
    }
}
