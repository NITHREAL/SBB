<?php

namespace Infrastructure\Services\Buyer;

use Illuminate\Support\Arr;
use Infrastructure\Services\Buyer\Facades\BuyerAddress;
use Infrastructure\Services\Buyer\Facades\BuyerCity;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryCoordinates;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryInterval;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class BuyerDeliveryDataSetService
{
    public static function setBuyerDeliveryData(array $data): void
    {
        self::setDeliveryType(Arr::get($data, 'deliveryType'));
        self::setDeliverySubType(Arr::get($data, 'deliverySubType'));
        self::setCity(Arr::get($data, 'cityId'));
        self::setStore(Arr::get($data, 'storeId'));
        self::setAddress(Arr::get($data, 'address'));
        self::setDeliveryIntervals(
            Arr::get($data, 'deliveryIntervalDate'),
            Arr::get($data, 'deliveryIntervalTime'),
        );
        self::setDeliveryCoordinates(
            Arr::get($data, 'latitude'),
            Arr::get($data, 'longitude'),
        );
    }

    public static function setDeliveryType(?string $deliveryType): void
    {
        if ($deliveryType) {
            BuyerDeliveryType::setValue($deliveryType);
        }
    }

    public static function setDeliverySubType(?string $deliverySubType): void
    {
        if ($deliverySubType) {
            BuyerDeliverySubType::setValue($deliverySubType);
        }
    }

    public static function setCity(?string $cityId): void
    {
        if ($cityId) {
            BuyerCity::setValue($cityId);
        }
    }

    public static function setStore(?string $storeId): void
    {
        if ($storeId) {
            BuyerStore::setValue($storeId);
        }
    }

    public static function setAddress(?string $address): void
    {
        if ($address) {
            BuyerAddress::setValue($address);
        }
    }

    public static function setDeliveryIntervals(?string $deliveryIntervalDate, ?string $deliveryIntervalTime): void
    {
        if ($deliveryIntervalDate || $deliveryIntervalTime) {
            BuyerDeliveryInterval::setValue([
                'delivery_date' => $deliveryIntervalDate,
                'delivery_time' => $deliveryIntervalTime,
            ]);
        }
    }

    public static function setDeliveryCoordinates(?string $latitude, ?string $longitude): void
    {
        if ($latitude && $longitude) {
            BuyerDeliveryCoordinates::setValue([
                'latitude'  => $latitude,
                'longitude' => $longitude,
            ]);
        }
    }
}
