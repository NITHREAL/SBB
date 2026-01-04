<?php

namespace Domain\Basket\Services\OutputPreparing\Splitter\Components;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Infrastructure\Services\Buyer\Facades\BuyerAddress;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryInterval;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class PreorderBasketDeliveryParams implements BasketDeliveryParamsInterface
{
    private const DEFAULT_PREORDER_DELIVERY_DAYS = 3;

    private ?string $deliveryIntervalTime;

    public function __construct()
    {
        $this->deliveryIntervalTime = BuyerDeliveryInterval::getDeliveryTimeInterval();
    }

    public function getDeliveryParams(array $basketParams): array
    {
        $result = [];

        if ($basketParams) {
            $deliveryParams = Arr::last($basketParams);

            $date = Arr::get($deliveryParams, 'deliveryIntervalDate');

            if ($date >= $this->getDefaultDeliveryDate()) {
                $result = $deliveryParams;
            }
        }

        if (empty($result)) {
            $result = $this->getDefaultDeliveryParams();
        }

        return $this->prepareDeliveryParams($result);
    }

    private function prepareDeliveryParams(array $deliveryParams): array
    {
        return OrderDeliveryHelper::isDelivery(Arr::get($deliveryParams, 'deliveryType'))
            ? $this->getPreparedDataForDelivery($deliveryParams)
            : $this->getPreparedDataForPickup($deliveryParams);
    }

    private function getPreparedDataForDelivery(array $deliveryParams): array
    {
        $deliveryParams['address'] = Arr::get($deliveryParams, 'address') ?? BuyerAddress::getValue();

        return $deliveryParams;
    }

    private function getPreparedDataForPickup(array $deliveryParams): array
    {
        $storeId = Arr::get($deliveryParams, 'storeId') ??  BuyerStore::getId();
        $store = Store::find($storeId);

        if (empty(Arr::get($deliveryParams, 'address'))) {
            $deliveryParams['address'] = $store ? $store->getAttribute('title') : BuyerAddress::getValue();
        }

        if (OrderDeliveryHelper::isDeliveryInterval(Arr::get($deliveryParams, 'deliveryIntervalTime'))) {
            $deliveryParams['deliveryIntervalTime'] = $this->getPickupTime(
                $store,
                Arr::get($deliveryParams, 'deliveryType'),
                Arr::get($deliveryParams, 'deliverySubType'),
                Arr::get($deliveryParams, 'deliveryIntervalDate'),
            );
        }

        return $deliveryParams;
    }

    private function getPickupTime(
        Store $store,
        string $deliveryType,
        string $deliverySubType,
        string $deliveryIntervalDate,
    ): string {
        $day = Carbon::createFromFormat('Y-m-d', $deliveryIntervalDate);

        $intervalHelper = new ReceiveInterval($store, $deliverySubType, $deliveryType);

        $firstDayIntervals = Arr::get(
            Arr::first($intervalHelper->getPickupInterval($day)),
            'intervals',
        );

        // Возвращаем поле value (интервал в подготовленном виде) первого интервала первого дня
        return Arr::get(Arr::first($firstDayIntervals), 'value');
    }

    private function getDefaultDeliveryDate(): string
    {
        return Carbon::now()->addDays(self::DEFAULT_PREORDER_DELIVERY_DAYS)->format('Y-m-d');
    }

    private function getDefaultDeliveryParams(): array
    {
        return [
            'deliveryIntervalDate'  => $this->getDefaultDeliveryDate(),
            'deliveryIntervalTime'  => $this->deliveryIntervalTime,
            'deliveryType'          => DeliveryTypeEnum::pickup()->value,
            'deliverySubType'       => PickupTypeEnum::other()->value,
        ];
    }
}
