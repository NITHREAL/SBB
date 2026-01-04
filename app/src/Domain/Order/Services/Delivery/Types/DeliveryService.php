<?php

namespace Domain\Order\Services\Delivery\Types;

use Domain\City\Models\City;
use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Order\Models\Delivery\PolygonType;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Order\Services\Delivery\Polygon\PolygonService;
use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Infrastructure\Services\DaData\Address\DaDataAddressService;

class DeliveryService implements DeliveryServiceInterface
{
    public function __construct(
        private readonly DaDataAddressService $dadataAddressService,
        private readonly PolygonService $polygonService,
    ) {
    }

    /**
     * @throws DeliveryTypeException
     */
    public function getData(DeliveryTypeSetDTO $data): array
    {
        $city = $data->getCity();
        $cityStores = Store::query()
            ->whereActive()
            ->byCityCollection($city->id)
            ->with(['polygons'])
            ->get();
        $deliveryIntervalDate = $data->getDate();
        $addressInput = $data->getAddress();
        $deliverySubTypeInput = $data->getDeliverySubType();

        $addressData = $this->getAddressAndCoords($city, $addressInput);

        $address = Arr::get($addressData, 'address');
        $latitude = Arr::get($addressData, 'latitude');
        $longitude = Arr::get($addressData, 'longitude');

        $deliveryPolygonTypes = $this->getDeliverySubTypes($cityStores, $latitude, $longitude);
        [$store, $polygon] = $this->polygonService->getStoreAndPolygonForDelivery(
            $cityStores,
            $latitude,
            $longitude,
            $deliverySubTypeInput,
        );

        // Если выбрана быстрая доставка, то необходимо расчитать интервал доставки
        $deliveryIntervalTime = $deliverySubTypeInput === PolygonDeliveryTypeEnum::fast()->value
            ? $this->resolveDeliveryTimeInterval($store, $deliverySubTypeInput, $deliveryIntervalDate)
            : $data->getInterval();

        $sumForDeliveryFree = $polygon->getDeliveryPriceForFree()?->from;

        return [
            'cityTitle'             => $city->title,
            'cityId'                => $city->id,
            'storeId'               => $store->id,
            'storeName'             => $store->title,
            'store1cId'             => $store->getAttribute('system_id'),
            'deliveryType'          => $data->getDeliveryType(),
            'deliverySubType'       => $deliverySubTypeInput,
            'deliveryIntervalDate'  => $deliveryIntervalDate,
            'deliveryIntervalTime'  => $deliveryIntervalTime,
            'deliveryPolygonTypes'  => $deliveryPolygonTypes,
            'sumForDeliveryFree'    => $sumForDeliveryFree,
            'address'               => $address,
            'latitude'              => $latitude,
            'longitude'             => $longitude,
        ];
    }

    /**
     * @throws DeliveryTypeException
     */
    public function getNearestStoreByAddress(
        int $cityId,
        string $address,
    ): Store {
        $city = City::findOrFail($cityId);
        $stores = $city->stores()->whereActive()->get();

        $addressData = $this->getAddressAndCoords($city, $address);

        return Arr::first(
            $this->polygonService->getStoreAndPolygonForDelivery(
                $stores,
                Arr::get($addressData, 'latitude'),
                Arr::get($addressData, 'longitude'),
            )
        );
    }

    /**
     * @throws DeliveryTypeException
     */
    public function getAddressAndCoords(City $city, string $address): array
    {
        $addressData = $this->dadataAddressService->getOneAddressDataByQuery($city, $address);

        if (empty($addressData)) {
            throw new DeliveryTypeException('Доставка на выбранный адрес недоступна', 400);
        }

        return [
            'address'   => Arr::get($addressData, 'value'),
            'latitude'  => Arr::get($addressData, 'latitude'),
            'longitude' => Arr::get($addressData, 'longitude'),
        ];
    }

    public function getDeliverySubTypes(Collection $stores, string $latitude, string $longitude): array
    {
        $deliverySubTypesData = $this->getDeliverySubTypesData();

        foreach ($stores as $store) {
            $polygons = $this->polygonService->getPolygonsByCoordinates($store, $latitude, $longitude);

            foreach ($polygons as $polygon) {
                $deliverySubTypesData = $this->getPreparedDeliverySubTypes($store, $polygon->type, $deliverySubTypesData);
            }
        }

        $deliverySubTypesData = $this->handleFastAndTodayIntervals($deliverySubTypesData);

        return array_values($deliverySubTypesData);
    }

    private function getPreparedDeliverySubTypes(
        Store $store,
        string $type,
        array $deliverySubTypes
    ): array {
        if ($type === PolygonDeliveryTypeEnum::fast()->value) {
            $availableIntervals = $this->getDeliveryFastIntervals($store);

            $deliverySubTypes[PolygonDeliveryTypeEnum::fast()->value]['active'] = count($availableIntervals) > 0;
            $deliverySubTypes[PolygonDeliveryTypeEnum::fast()->value]['intervals'] = $availableIntervals;
        } elseif ($type === PolygonDeliveryTypeEnum::extended()->value) {
            $availableIntervals = $this->getDeliveryExtendedIntervals($store);

            $deliverySubTypes[PolygonDeliveryTypeEnum::extended()->value]['active'] = count($availableIntervals) > 0;
            $deliverySubTypes[PolygonDeliveryTypeEnum::extended()->value]['intervals'] = $availableIntervals;
        } elseif ($type === PolygonDeliveryTypeEnum::other()->value) {
            $availableIntervals = $this->getDeliveryOtherDayIntervals($store);

            $deliverySubTypes[PolygonDeliveryTypeEnum::other()->value]['active'] = count($availableIntervals) > 0;
            $deliverySubTypes[PolygonDeliveryTypeEnum::other()->value]['intervals'] = $availableIntervals;
        }

        return $deliverySubTypes;
    }

    private function getDeliverySubTypesData(): array
    {
        $result = [];

        $polygonTypes = PolygonType::query()->whereDelivery()->get()->keyBy('type');

        foreach (PolygonDeliveryTypeEnum::toValues() as $type) {
            $polygonType = $polygonTypes->get($type);

            $result[$type] = [
                'type'          => $type,
                'active'        => false,
                'title'         => $polygonType?->title,
                'description'   => $polygonType?->description,
                'tooltip'       => $polygonType?->tooltip,
            ];
        }

        return $result;
    }

    private function getDeliveryExtendedIntervals(Store $store): array
    {
        return $this->getDeliveryTodayIntervals($store, PolygonDeliveryTypeEnum::extended()->value);
    }

    private function getDeliveryOtherDayIntervals(Store $store): array
    {
        $type = PolygonDeliveryTypeEnum::other()->value;
        $date = Carbon::now()->startOfDay()->addDay();

        return $this->getDeliverySubTypeIntervals($store, $date, $type);
    }

    private function getDeliveryFastIntervals(Store $store): array
    {
        $result = [];

        // Из-за того, что сейчас быстрая доставка также привязана к интервалам,
        // то ее доступность определяется наличием доступных интервалов на сегодня
        $intervals = $this->getDeliveryTodayIntervals($store, PolygonDeliveryTypeEnum::fast()->value);

        if (!empty($intervals)) {
            $firstInterval = Arr::first($intervals);

            $firstInterval['intervals'] = array_slice($firstInterval['intervals'], 0, 1);

            $result[] = $firstInterval;
        }

        return $result;
    }

    private function getDeliveryTodayIntervals(Store $store, string $polygonType): array
    {
        $result = [];

        $date = Carbon::now()->startOfDay();

        $intervals = $this->getDeliverySubTypeIntervals($store, $date, $polygonType);

        //проверяем, что первый интервал - сегодняшний
        if (
            count($intervals)
            && Arr::get(Arr::first($intervals), 'date') === Carbon::now()->format('Y-m-d')
        ) {
            $result = array_slice($intervals, 0, 1);
        }

        return $result;
    }

    private function getDeliverySubTypeIntervals(Store $store, Carbon $date, string $type): array
    {
        $intervalHelper = new ReceiveInterval($store, $type);

        $result = $intervalHelper->getInterval($date, 1, true);

        return array_values($result);
    }

    /**
     * @throws DeliveryTypeException
     */
    private function resolveDeliveryTimeInterval(
        Store $store,
        string $deliverySubType,
        string $deliveryIntervalDate,
    ): string {
        $intervalHelper = new ReceiveInterval($store, $deliverySubType);

        $intervals = $intervalHelper->getTimeIntervals(Carbon::createFromFormat('Y-m-d', $deliveryIntervalDate));

        if (empty($intervals)) {
            throw new DeliveryTypeException('Нет доступных временных интервалов для доставки', 400);
        }

        return Arr::get(Arr::first($intervals), 'value', '');
    }

    private function handleFastAndTodayIntervals(array $deliverySubTypes): array
    {
        $fastTypeData = Arr::first(
            Arr::where($deliverySubTypes, fn ($value, $key) => Arr::get($value, 'type') === PolygonDeliveryTypeEnum::fast()->value)
        );

        if (Arr::get($fastTypeData, 'active') === true) {
            $deliverySubTypes = Arr::map(
                $deliverySubTypes,
                function ($typeData) {
                    if (Arr::get($typeData, 'type') === PolygonDeliveryTypeEnum::extended()->value) {
                        foreach ($typeData['intervals'] as &$interval) {
                            array_shift($interval['intervals']);
                        }
                    }

                    return $typeData;
                }
            );
        }

        return $deliverySubTypes;
    }
}
