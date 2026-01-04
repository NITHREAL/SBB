<?php

namespace Tests\Feature\Api\V1\Basket;

use Domain\Basket\Models\Basket;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\Feature\Api\V1\Order\DeliveryType\DeliveryCacheHelper;

class BasketDeliveryCacheHelper
{
    public static function getExpectWithCache(
        array $request,
        string $token,
        TestResponse $response,
        $user,
    ): void {

        $addressCached = DeliveryCacheHelper::getCachedBuyerAddress($token);

        $deliveryParams = Arr::get($request, 'deliveryParams.0');

        $basket = Basket::query()->where('user_id', $user->id)->first();

        expect(Arr::get($deliveryParams, 'deliveryType'))
            ->toEqual(DeliveryCacheHelper::getCachedBuyerStoreDeliveryType($token))
            ->and(Arr::get($request, 'cityId'))
            ->toEqual(DeliveryCacheHelper::getCachedBuyerCityId($token))
            ->and($basket->token)
            ->toEqual(DeliveryCacheHelper::getCachedBuyerBasketToken($token))
            ->and(Arr::get($response, 'baskets.0.deliveryAddress'))
            ->toEqual($addressCached);
    }
}
