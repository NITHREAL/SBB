<?php

namespace Domain\Order\Services\Delivery\Polygon;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Order\Models\Delivery\Polygon;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Store\Models\Store;
use Illuminate\Support\Collection;

class PolygonService
{
    public function getPolygonsByCoordinates(Store $store, float $lat, float $lon): array
    {
        return $store->polygons
            ->filter(function ($polygon) use ($lat, $lon) {
                return self::pointIsInsidePolygon($polygon, $lat, $lon);
            })
            ->all();
    }

    public function resolvePolygonType(?string $deliverySubType): string
    {
        return is_null($deliverySubType) || $deliverySubType === PolygonDeliveryTypeEnum::other()->value
            ? PolygonDeliveryTypeEnum::extended()->value
            : $deliverySubType;
    }

    /**
     * @throws DeliveryTypeException
     */
    public function getStoreAndPolygonForDelivery(
        Collection $stores,
        string $latitude,
        string $longitude,
        ?string $deliverySubType = null,
    ): array {
        foreach ($stores as $store) {
            $polygon = $this->findPolygonByCoordinates($store, $latitude, $longitude, $deliverySubType);

            if ($polygon) {
                return [$store, $polygon];
            }
        }

        throw new DeliveryTypeException('Доставка на выбранный адрес недоступна', 400);
    }

    public function findPolygonByCoordinates(
        Store $store,
        string $latitude,
        string $longitude,
        ?string $deliverySubType = null,
    ): ?Polygon {
        $polygons = $store->polygons;

        foreach ($polygons as $polygon) {
            if (
                $this->isValidPolygonType($polygon, $deliverySubType)
                && $this->pointIsInsidePolygon($polygon, $latitude, $longitude)
            ) {
                return $polygon;
            }
        }

        return null;
    }

    public function getDeliveryDataBySum(Polygon $polygon, float $forSum): array
    {
        $deliveryPrice = $polygon
            ->deliveryPrices()
            ->where(function ($query) use ($forSum) {
                return $query
                    ->where(function ($query) use ($forSum) {
                        return $query
                            ->whereNotNull('from')
                            ->where('from', '<=', $forSum);
                    })
                    ->orWhereNull('from');
            })
            ->where(function ($query) use ($forSum) {
                return $query
                    ->where(function ($query) use ($forSum) {
                        return $query
                            ->whereNotNull('to')
                            ->where('to', '>=', $forSum);
                    })
                    ->orWhereNull('to');
            })
            ->orderBy('from')
            ->first();

        if ($deliveryPrice) {
            $isAvailable = true;
            $availableFrom = null;
            $price = round($deliveryPrice->price ?? 0, 2);
        } else {
            $defaultDeliveryPrice = $polygon->deliveryPrices()->orderBy('from')->first();

            $isAvailable = false;
            $availableFrom = $defaultDeliveryPrice?->from;
            $price = 0;
        }

        return compact('isAvailable', 'availableFrom', 'price');
    }

    private function isValidPolygonType(Polygon $polygon, ?string $deliverySubType = null): bool
    {
        return $deliverySubType === 'today' || !$deliverySubType || $polygon->type === $deliverySubType;
    }


    private function pointIsInsidePolygon(Polygon $polygon, float $lat, float $lon): bool
    {
        $coordinates = $polygon->coordinates;
        $countCoordinates = count($coordinates);
        $j = $countCoordinates - 1;

        $inPolygon = false;

        for ($i = 0; $i < $countCoordinates; $i++) {
            [$yp, $xp] = $coordinates[$i];
            [$ypPrev, $xpPrev] = $coordinates[$j];

            if (
                ($yp > $lat != ($ypPrev > $lat))
                && ($lon < ($xpPrev - $xp) * ($lat - $yp) / ($ypPrev - $yp) + $xp)
            ) {
                $inPolygon = !$inPolygon;
            }

            $j = $i;
        }

        return $inPolygon;
    }
}
