<?php

namespace Domain\Order\Services\Delivery\Types;

use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Models\Delivery\PolygonType;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Store\Helpers\StoreHelper;
use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class PickupService implements DeliveryServiceInterface
{
    /**
     * @throws DeliveryTypeException
     */
    public function getData(DeliveryTypeSetDTO $data): array
    {
        $city = $data->getCity();
        $store = $data->getStore();
        $deliveryIntervalDate = $data->getDate() ?? Carbon::now()->format('Y-m-d');

        $deliveryPolygonTypes = $this->getPickupSubTypes($store);

        $activePolugons = array_filter($deliveryPolygonTypes, function($polugon) {
            return $polugon['active'] === true;
        });

        if (empty($activePolugons)) {
            throw new DeliveryTypeException(
                'У выбранного магазина нет активного полигона доставки',
                Response::HTTP_BAD_REQUEST
            );
        }

        $deliverySubType = $data->getDeliverySubType() ?? PickupTypeEnum::today()->value;
        $deliveryIntervalTime = $this->resolvePickupIntervalTime($store, $deliverySubType, $deliveryIntervalDate);

        return [
            'cityTitle'             => $city->title,
            'cityId'                => $city->id,
            'storeId'               => $store->id,
            'storeName'             => $store->title,
            'store1cId'             => $store->getAttribute('system_id'),
            'deliveryType'          => $data->getDeliveryType(),
            'deliverySubType'       => $deliverySubType,
            'deliveryIntervalDate'  => $deliveryIntervalDate,
            'deliveryIntervalTime'  => $deliveryIntervalTime,
            'deliveryPolygonTypes'  => $deliveryPolygonTypes,
            'address'               => StoreHelper::getStorePreparedAddress($city->title, $store->title),
            'latitude'              => $store->latitude ? round($store->latitude, 4) : "",
            'longitude'             => $store->longitude ? round($store->longitude, 4) : "",
        ];
    }

    public function getPickupSubTypes(Store $store): array
    {
        $result = [];

        $polygonTypes = PolygonType::query()
            ->wherePickup()
            ->with(['scheduleWeekdays', 'scheduleDates'])
            ->get()
            ->keyBy('type');

        $now = Carbon::now();

        foreach (PickupTypeEnum::toValues() as $type) {
            $result[$type] = $this->getPickupSubTypeData($polygonTypes, $store, $now, $type);
        }

        return array_values($result);
    }

    private function resolvePickupIntervalTime(
        Store $store,
        string $deliverySubType,
        string $deliveryIntervalDate,
    ): string {
        $intervalHelper = new ReceiveInterval($store, $deliverySubType, DeliveryTypeEnum::pickup()->value);

        return $intervalHelper->getIntervalAllDay(Carbon::createFromFormat('Y-m-d', $deliveryIntervalDate));
    }

    private function getPickupSubTypeData(
        Collection $polygonTypes,
        Store $store,
        Carbon $date,
        string $type
    ): array {
        $polygonType = $polygonTypes->get($type);

        $intervals = $this->getPickupSubTypeIntervals($store, $date, $type);

        return [
            'title'         => $polygonType?->title,
            'description'   => $polygonType?->desciption,
            'tooltip'       => $polygonType?->tooltip,
            'type'          => $type,
            'active'        => count($intervals) > 0,
            'intervals'     => $intervals,
        ];
    }

    private function getPickupSubTypeIntervals(Store $store, Carbon $date, string $type): array
    {
        $intervals = [];

        $intervalHelper = new ReceiveInterval($store, $type, DeliveryTypeEnum::pickup()->value);

        if ($type === PickupTypeEnum::today()->value) {
            $intervals = $this->getIntervalsForToday($intervalHelper, $date);
        } elseif ($type === PickupTypeEnum::other()->value) {
            $intervals = $this->getIntervalsForOtherDay($intervalHelper, $date->addDay());
        }

        return $intervals;
    }

    private function getIntervalsForOtherDay(ReceiveInterval $intervalHelper, Carbon $date): array
    {
        $intervals = $intervalHelper->getPickupInterval($date);

        // Для доставки на другой день убираем сегодняшнюю дату
        if (count($intervals) && $this->isFirstIntervalDateIsToday($intervals)) {
            Arr::forget($intervals, array_key_first($intervals));
        }

        return $intervals;
    }

    private function getIntervalsForToday(ReceiveInterval $intervalHelper, Carbon $date): array
    {
        $result = [];

        $intervals = $intervalHelper->getPickupInterval($date);

        // Для доставки сегодня подходят только временные интервалы, которые сегодня.
        // Иначе подходящих интервалов нет
        if (count($intervals) && $this->isFirstIntervalDateIsToday($intervals)) {
            $result[] = Arr::first($intervals);
        }

        return $result;
    }

    private function isFirstIntervalDateIsToday(array $intervals): bool
    {
        return Arr::get(Arr::first($intervals), 'date') === Carbon::now()->format('Y-m-d');
    }

}
