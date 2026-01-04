<?php

namespace Domain\Order\Helpers\Delivery;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Illuminate\Support\Arr;

class OrderDeliveryHelper
{
    public static function getDeliveryTypes(): array
    {
        return [
            DeliveryTypeEnum::delivery()->value,
            DeliveryTypeEnum::pickup()->value,
        ];
    }

    public static function getDeliverySubTypes(): array
    {
        return array_unique(
            array_merge(
                PickupTypeEnum::toValues(),
                PolygonDeliveryTypeEnum::toValues(),
            )
        );
    }

    public static function getDeliverySubTypesData(string $deliveryType): array
    {
        return OrderDeliveryHelper::isDelivery($deliveryType)
            ? PolygonDeliveryTypeEnum::toArray()
            : PickupTypeEnum::toArray();
    }

    public static function isValidDeliveryType(string $deliveryType = null): bool
    {
        return in_array($deliveryType, self::getDeliveryTypes());
    }

    public static function isPickup(string $deliveryType): bool
    {
        return $deliveryType === DeliveryTypeEnum::pickup()->value;
    }

    public static function isDelivery(string $deliveryType): bool
    {
        return $deliveryType === DeliveryTypeEnum::delivery()->value;
    }

    public static function onOtherDay(string $deliverySubType): bool
    {
        return in_array(
            $deliverySubType,
            [
                PolygonDeliveryTypeEnum::other()->value,
                PickupTypeEnum::other()->value
            ],
            true,
        );
    }

    public static function isDeliveryInterval(string $timeInterval): bool
    {
        $intervalData = explode('_', $timeInterval);

        // Длительность интервалов для доставки - 1 час
        return (int) Arr::get($intervalData, 1) - (int) Arr::get($intervalData, 0) === 1;
    }

    public static function getPreparedDeliveryTimeLabel(string $timeInterval): string
    {
        $timeArray = explode('_', $timeInterval);

        return sprintf(
            '%s:00 - %s:00',
            Arr::get($timeArray, 0),
            Arr::get($timeArray, 1),
        );
    }

    public static function getDefaultDeliverySubType(string $deliveryType): string
    {
        return self::isDelivery($deliveryType)
            ? PolygonDeliveryTypeEnum::extended()->value
            : PickupTypeEnum::today()->value;
    }
}
