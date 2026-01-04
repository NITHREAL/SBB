<?php

namespace Tests\Unit\Order\DeliveryType;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeliveryTypeRequests extends TestCase
{
    public function getDelivery(string $accessToken = null): TestResponse
    {
        $headers = $accessToken ? ['Authorization' => "Bearer {$accessToken}"]  : [];

        return static::withHeaders($headers)
            ->get('/api/v1/delivery');
    }

    public function setDeliveryTypeByCity(
        array $request,
        string $accessToken = null,
    ): TestResponse {
        $headers = $accessToken ? ['Authorization' => "Bearer {$accessToken}"]  : [];

        return static::withHeaders($headers)
            ->post('/api/v1/delivery/type/city', $request);
    }

    public function getAvailableDeliveryType(
        string $accessToken,
        array $request,
    ): TestResponse {
        $param = '';
        $value = '';
         if (array_key_exists('storeId', $request)) {
             $param = 'storeId';
             $value = $request['storeId'];
         }
        if (array_key_exists('address', $request)) {
            $param = 'address';
            $value = $request['address'];
        }
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->get(
                sprintf(
                    '/api/v1/delivery/check?cityId=%u&deliveryType=%s&%s=%s',
                    $request['cityId'] ?? '',
                    $request['deliveryType'] ?? '',
                    $param,
                    $value,
                )
            );
    }

    public function setDeliveryType(
        array $request,
        string $accessToken,
    ): TestResponse {
        return static::withHeaders(['Authorization' => "Bearer {$accessToken}"])
            ->post('/api/v1/delivery/type', $request);
    }
}
