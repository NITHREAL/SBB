<?php

namespace Tests\Unit\Order\DeliveryType;

use Domain\User\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;

class DeliveryTypeHelper
{
    const DELIVERY_TYPE_PICKUP = 'pickup';

    const DELIVERY_TYPE_DELIVERY = 'delivery';

    const DEFAULT_ADDRESS = 'г Кемерово, пр-кт Шахтеров, д 56';

    const DELIVERY_SUB_TYPE = "today";

    const DELIVERY_TIME = '12_13';

    public static User $user;

    public static array $setDeliveryStructure = [
        "token",
        "cityTitle",
        "cityId",
        "storeId",
        "storeOneCId",
        "deliveryType",
        "deliverySubType",
        "deliveryIntervalDate",
        "deliveryIntervalTime",
        "deliveryPolygonTypes",
        "address",
        "latitude",
        "longitude",
        "sumForFreeDelivery"
    ];

    public static function getDefaultRequest($city, $store): array
    {
        return [
            "address" => self::DEFAULT_ADDRESS,
            "cityId" => $city->id,
            "storeId" => $store->id,
            "deliveryType" => self::DELIVERY_TYPE_PICKUP,
            "deliverySubType" => self::DELIVERY_SUB_TYPE,
            "date" => self::getDeliveryDate(),
            "time" => self::DELIVERY_TIME,
        ];
    }

    public static function getDeliveryDate(): string
    {
        return Carbon::today()->addDays(rand(1, 15))->format('Y-m-d');
    }

    public static function getMainExpect(TestResponse $response)
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$setDeliveryStructure)
            ->and($response['token'])->toBeString()
            ->and($response['cityTitle'])->toBeString()
            ->and($response['cityId'])->toBeInt()
            ->and($response['storeId'])->toBeInt()
            ->and($response['deliveryType'])->toBeString()
            ->and($response['address'])->toBeString()
            ->and($response['latitude'])->toBeString()
            ->and($response['longitude'])->toBeString()
            ->and($response['deliveryPolygonTypes'])->toBeArray()
            ->and($response['sumForFreeDelivery'])->toBeNull();
    }

    public static function getDeliveryParams($city): array
    {
        return [
            'deliveryType'  => DeliveryTypeHelper::DELIVERY_TYPE_DELIVERY,
            'cityId'        => $city->id,
        ];
    }

    public static function getPickupParams($city): array
    {
        return [
            'deliveryType'     => DeliveryTypeHelper::DELIVERY_TYPE_PICKUP,
            'cityId'           => $city->id,
        ];
    }
}
