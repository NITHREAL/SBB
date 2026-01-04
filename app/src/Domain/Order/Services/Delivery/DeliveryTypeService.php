<?php

namespace Domain\Order\Services\Delivery;

use Domain\Basket\Services\BasketService;
use Domain\City\Models\City;
use Domain\Order\DTO\Delivery\DeliveryDateTimeSetDTO;
use Domain\Order\DTO\Delivery\DeliveryTypeByCityDTO;
use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Services\Delivery\Exceptions\DeliveryDateTimeSetException;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Order\Services\Delivery\Types\DeliveryService;
use Domain\Order\Services\Delivery\Types\PickupService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Services\Buyer\BuyerDeliveryDataSetService;
use Infrastructure\Services\Buyer\Components\Store\Helper\StoreEntityHelper;
use Infrastructure\Services\Buyer\Facades\BuyerAddress;
use Infrastructure\Services\Buyer\Facades\BuyerCity;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryCoordinates;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryInterval;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;
use Infrastructure\Services\Buyer\Facades\BuyerToken;
use Infrastructure\Services\DaData\Address\DaDataAddressService;
use Infrastructure\Services\DaData\Exceptions\DaDataException;

class DeliveryTypeService
{
    public function __construct(
        private readonly DeliveryService      $deliveryService,
        private readonly PickupService        $pickupService,
        private readonly DaDataAddressService $dataAddressService,
        private readonly StoreEntityHelper    $storeEntityHelper,
        private readonly BasketService        $basketService,
    )
    {
    }

    /**
     * @throws DeliveryTypeException
     */
    public function processDeliveryTypeSet(DeliveryTypeSetDTO $deliveryTypeDTO): array
    {
        $data = $this->getDeliveryTypeData($deliveryTypeDTO);

        if (count($data)) {
            BuyerDeliveryDataSetService::setBuyerDeliveryData($data);
        }

        $data['token'] = BuyerToken::getValue();

        return $data;
    }

    /**
     * @throws DaDataException
     * @throws DeliveryDateTimeSetException
     */
    public function processDeliveryDateTimeSet(DeliveryDateTimeSetDTO $dateTimeSetDTO): array
    {
        $data = $this->getCurrentDeliveryTypeData();

        $this->checkDeliveryIntervalsAvailability($data, $dateTimeSetDTO);

        BuyerDeliveryDataSetService::setBuyerDeliveryData([
            'deliveryIntervalDate'  => $dateTimeSetDTO->getDate(),
            'deliveryIntervalTime'  => $dateTimeSetDTO->getInterval(),
            'deliverySubType'       => $dateTimeSetDTO->getDeliverySubType(),
        ]);

        $data['deliveryIntervalDate'] = BuyerDeliveryInterval::getDeliveryDateInterval();
        $data['deliveryIntervalTime'] = BuyerDeliveryInterval::getDeliveryTimeInterval();
        $data['deliverySubType'] = BuyerDeliverySubType::getValue();

        $data['token'] = BuyerToken::getValue();

        return $data;
    }

    /**
     * @throws DaDataException
     */
    public function processDeliveryTypeByCity(DeliveryTypeByCityDTO $deliveryTypeDTO): array
    {
        $data = $this->getDeliveryTypeDataByCoordinates($deliveryTypeDTO);

        if (count($data)) {
            BuyerDeliveryDataSetService::setBuyerDeliveryData($data);

            // $data оборачивается в массив для сохранения структуры параметров доставки в одном виде,
            // предусматривающем параметры для нескольких корзин
            $this->basketService->updateDeliveryParams([$data]);
        }

        $data['token'] = BuyerToken::getValue();

        return $data;
    }

    /**
     * @throws DaDataException
     */
    public function getCurrentDeliveryTypeData(): array
    {
        $city = BuyerCity::getSelectedCity();
        $store = BuyerStore::getSelectedStore();
        $coordinates = BuyerDeliveryCoordinates::getValue();
        $address = BuyerAddress::getValue();
        $deliveryInterval = BuyerDeliveryInterval::getValue();
        $deliveryType = BuyerDeliveryType::getValue();

        if (empty($coordinates)) {
            $coordinates = $this->getCoordinatesByCity($city);
        }

        if (empty($address) && Auth::check()) {
            if (Auth::user()?->orders()->exists()) {
                $order = Auth::user()?->orders()->latest()->first();
                $store = $order?->store;
                $deliveryInterval = [
                    'delivery_date' => $order?->receive_date,
                    'delivery_time' => $order?->receive_interval,
                ];
                $deliveryType = $order?->delivery_type;
                $address = $order?->contacts->address;
            }
        }

        $latitude = Arr::get($coordinates, 'latitude');
        $longitude = Arr::get($coordinates, 'longitude');


        if (OrderDeliveryHelper::isDelivery($deliveryType)) {
            $deliveryPolygonTypes = $this->deliveryService->getDeliverySubTypes($city->stores, $latitude, $longitude);
        } else if ($store) {
            $deliveryPolygonTypes = $this->pickupService->getPickupSubTypes($store);
        }

        return [
            'token' => BuyerToken::getValue(),
            'cityTitle' => $city->title,
            'cityId' => $city->id,
            'storeId' => $store?->id,
            'store1cId' => $store?->getAttribute('system_id'),
            'storeName' => $store?->title,
            'deliveryType' => $deliveryType,
            'deliverySubType' => BuyerDeliverySubType::getValue(),
            'deliveryIntervalDate' => BuyerDeliveryInterval::getDeliveryDateInterval($deliveryInterval),
            'deliveryIntervalTime' => BuyerDeliveryInterval::getDeliveryTimeInterval($deliveryInterval),
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'deliveryPolygonTypes' => $deliveryPolygonTypes ?? null,
        ];
    }

    /**
     * @throws DeliveryTypeException
     */
    public function getAvailableDeliveryTypeData(DeliveryTypeSetDTO $deliveryTypeDTO): array
    {
        $data = $this->getDeliveryTypeData($deliveryTypeDTO);

        $data['token'] = BuyerToken::getValue();

        return $data;
    }

    /**
     * @throws DeliveryTypeException
     */
    private function getDeliveryTypeData(DeliveryTypeSetDTO $deliveryTypeDTO): array
    {
        return OrderDeliveryHelper::isDelivery($deliveryTypeDTO->getDeliveryType())
            ? $this->deliveryService->getData($deliveryTypeDTO)
            : $this->pickupService->getData($deliveryTypeDTO);
    }

    /**
     * @throws DaDataException
     */
    private function getDeliveryTypeDataByCoordinates(DeliveryTypeByCityDTO $deliveryTypeDTO): array
    {
        $city = $deliveryTypeDTO->getCity();
        $coordinates = $this->getCoordinatesByCity($city);

        $latitude = Arr::get($coordinates, 'latitude');
        $longitude = Arr::get($coordinates, 'longitude');

        $store = $this->storeEntityHelper->getClosestStoreByCoords($city, $latitude, $longitude) ?? BuyerStore::getSelectedStore();

        return [
            'cityTitle' => $city->title,
            'cityId' => $city->id,
            'storeId' => $store->id,
            'store1cId' => $store->getAttribute('system_id'),
            'storeName' => $store->title,
            'deliveryType' => $deliveryTypeDTO->getDeliveryType(),
            'address' => $this->getAddressByCoordinates($latitude, $longitude),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

    private function getCoordinatesByCity(City $city): array
    {
        $coordinates = [
            'latitude' => $city->latitude,
            'longitude' => $city->longitude,
        ];

        BuyerDeliveryCoordinates::setValue($coordinates);

        return $coordinates;
    }

    /**
     * @throws DaDataException
     */
    private function getAddressByCoordinates(string $latitude, string $longitude): string
    {
        $locationData = $this->dataAddressService->getAddressByCoordinates($latitude, $longitude);

        $address = Arr::get($locationData, 'value');

        BuyerAddress::setValue($address);

        return $address;
    }

    /**
     * @throws DeliveryDateTimeSetException
     */
    public function checkDeliveryIntervalsAvailability(array $data, DeliveryDateTimeSetDTO $dateTimeSetDTO): void
    {
        $selectedType = Arr::first(
            Arr::where($data['deliveryPolygonTypes'], function ($array) use ($dateTimeSetDTO) {
                if ($array['type'] === $dateTimeSetDTO->getDeliverySubType()) {
                    return $array;
                }
            })
        );

        $dateInterval = Arr::first(
            Arr::where($selectedType['intervals'], function ($array) use ($dateTimeSetDTO) {
                if ($array['date'] === $dateTimeSetDTO->getDate()) {
                    return $array;
                }
            })
        );

        if (!$dateInterval) {
            $message = __('delivery.exceptions.date_not_found');
            $message = str_replace('%type%', $dateTimeSetDTO->getDeliverySubType(), $message);
            $message = str_replace('%address%', $data['address'], $message);

            throw new DeliveryDateTimeSetException(
                $message
            );
        }

        $intervals = data_get($dateInterval, 'intervals.*.value');

        if (!in_array($dateTimeSetDTO->getInterval(), $intervals)) {
            $message = __('delivery.exceptions.incorrect_interval');
            $message = str_replace('%type%', $dateTimeSetDTO->getDeliverySubType(), $message);
            $message = str_replace('%address%', $data['address'], $message);

            throw new DeliveryDateTimeSetException(
                $message
            );
        }
    }
}
