<?php

namespace Tests\Feature\Api\V1\Order\DeliveryType;

use Domain\Basket\Models\Basket;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\TestResponse;

class DeliveryCacheHelper
{
    const BUYER_BASKET_TOKEN_PREFIX = 'buyer_basket_token_';
    const BUYER_CITY_ID_PREFIX = 'buyer_city_id_';
    const BUYER_COORDINATES_PREFIX = 'buyer_coordinates_';
    const BUYER_DELIVERY_INTERVALS_PREFIX = 'buyer_delivery_intervals_';
    const BUYER_DELIVERY_SUB_TYPE_PREFIX = 'buyer_delivery_sub_type_';
    const BUYER_DELIVERY_TYPE_PREFIX = 'buyer_delivery_type_';

    const BUYER_ADDRESS_PREFIX = 'buyer_address_';

    public static function getCachedBuyerBasketToken(string $token): string
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_BASKET_TOKEN_PREFIX,
                $token,
            )
        );
    }

    public static function getCachedBuyerCityId(string $token): int
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_CITY_ID_PREFIX,
                $token,
            )
        );
    }

    public static function getCachedBuyerCoordinates(string $token): array
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_COORDINATES_PREFIX,
                $token,
            )
        );
    }

    public static function getCachedBuyerDeliveryIntervals(string $token): array
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_DELIVERY_INTERVALS_PREFIX,
                $token,
            )
        );
    }

    public static function getCachedBuyerDeliverySubType(string $token): string
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_DELIVERY_SUB_TYPE_PREFIX,
                $token,
            )
        );
    }

    public static function getCachedBuyerStoreDeliveryType(string $token): string
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_DELIVERY_TYPE_PREFIX,
                $token,
            )
        );
    }

    public static function getCachedBuyerAddress(string $token): string
    {
        return Cache::get(
            sprintf(
                '%s%s',
                self::BUYER_ADDRESS_PREFIX,
                $token,
            )
        );
    }

    public static function getIntervalsArray(TestResponse $response): array
    {
        return [
            'delivery_date' => $response['deliveryIntervalDate'],
            'delivery_time' => $response['deliveryIntervalTime'],
        ];
    }

    public static function getCoordinatesArray(TestResponse $response): array
    {
        return [
            'latitude' => $response['latitude'],
            'longitude' => $response['longitude'],
        ];
    }

    public static function getExpectWithCache(
        TestResponse $response,
        string $token,
        User $user,
    ): void {
        $basket = self::getBasket($user->id);

        $coordinates = DeliveryCacheHelper::getCoordinatesArray($response);

        expect($response['deliveryType'])
            ->toEqual(DeliveryCacheHelper::getCachedBuyerStoreDeliveryType($token))
            ->and($response['cityId'])
            ->toEqual(DeliveryCacheHelper::getCachedBuyerCityId($token))
            ->and($coordinates)
            ->toEqual(DeliveryCacheHelper::getCachedBuyerCoordinates($token));

        if (!empty($basket) && Arr::get(Arr::first($basket->delivery_params), 'storeSystemId')) {
            expect($basket->token)
                ->toEqual(DeliveryCacheHelper::getCachedBuyerBasketToken($token))
                ->and($response['storeOneCId'])
                ->toEqual(Arr::get(Arr::first($basket->delivery_params), 'storeSystemId'));
        }
    }

    private static function getBasket(int $userId): ?Basket
    {
        /** @var Basket */
        return Basket::query()
            ->where('user_id', $userId)
            ->first();
    }
}
