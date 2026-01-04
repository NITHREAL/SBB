<?php

namespace Infrastructure\Services\Buyer\Components;


use Infrastructure\Services\Buyer\BuyerDataService;

class BuyerDeliveryCoordinatesService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'coordinates';

    private const ATTRIBUTE_COORDINATES_LATITUDE_KEY = 'latitude';
    private const ATTRIBUTE_COORDINATES_LONGITUDE_KEY = 'longitude';

    public function setValue(array|string $value): void
    {
        if (is_array($value) === false) {
            $value = $this->getDefaultValue();
        } else {
            $value = array_filter(
                $value,
                function ($item, $key) {
                    return is_string($item) && in_array($key, $this->getAvailableAttributeKeys());
                },
                ARRAY_FILTER_USE_BOTH,
            );
        }

        if (count($value)) {
            $this->setCachedValue($value);
        }
    }

    public function getDefaultValue(): array
    {
        return [];
    }

    private function getAvailableAttributeKeys(): array
    {
        return [
            self::ATTRIBUTE_COORDINATES_LATITUDE_KEY ,
            self::ATTRIBUTE_COORDINATES_LONGITUDE_KEY,
        ];
    }
}
