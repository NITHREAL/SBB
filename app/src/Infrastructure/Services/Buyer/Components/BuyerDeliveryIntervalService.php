<?php

namespace Infrastructure\Services\Buyer\Components;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Infrastructure\Services\Buyer\BuyerDataService;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class BuyerDeliveryIntervalService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'delivery_intervals';

    private const ATTRIBUTE_DELIVERY_DATE_KEY = 'delivery_date';
    private const ATTRIBUTE_DELIVERY_TIME_KEY = 'delivery_time';

    public function __construct()
    {
        parent::__construct();

        $this->cacheTtl = config('api.buyer.delivery_interval_ttl');
    }

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

    public function getValue(): array
    {
        $value = $this->getCachedValue();

        if (empty($value) || is_array($value) === false) { // Если в кэше ничего нет или лежит в неправильном формате (не массив)
            $value = $this->getDefaultValue();

            $this->setCachedValue($value);
        } elseif (empty($this->getDeliveryDateInterval($value))) { // Если в кэше нет даты доставки
            // Кладем в полученный из кэша массив дефолтное значение
            $value[self::ATTRIBUTE_DELIVERY_DATE_KEY] = $this->getDefaultDeliveryDate(
                $this->getIntervalHelperInstance(),
            );

            $this->setCachedValue($value);
        } elseif (empty($this->getDeliveryTimeInterval($value))) {// Если в кэше нет времени доставки
            // Кладем в полученный из кэша массив дефолтное значение
            $value[self::ATTRIBUTE_DELIVERY_TIME_KEY] = $this->getDefaultDeliveryTime(
                $this->getIntervalHelperInstance(),
                $this->getDeliveryDateInterval($value),
            );

            $this->setCachedValue($value);
        }

        return $value;
    }

    public function getDeliveryDateInterval(array $intervalsData = []): ?string
    {
        if (empty($intervalsData)) {
            $intervalsData = $this->getValue();
        }

        return Arr::get($intervalsData, self::ATTRIBUTE_DELIVERY_DATE_KEY);
    }

    public function getDeliveryTimeInterval(array $intervalsData = []): ?string
    {
        if (empty($intervalsData)) {
            $intervalsData = $this->getValue();
        }

        return Arr::get($intervalsData, self::ATTRIBUTE_DELIVERY_TIME_KEY);
    }

    protected function getDefaultValue(): array
    {
        $intervalHelper = $this->getIntervalHelperInstance();

        $deliveryIntervalDate = $this->getDefaultDeliveryDate($intervalHelper);
        $deliveryIntervalTime = $deliveryIntervalDate
            ? $this->getDefaultDeliveryTime($intervalHelper, $deliveryIntervalDate)
            : null;

        return [
            self::ATTRIBUTE_DELIVERY_DATE_KEY => $deliveryIntervalDate,
            self::ATTRIBUTE_DELIVERY_TIME_KEY => $deliveryIntervalTime,
        ];
    }

    private function getDefaultDeliveryDate(ReceiveInterval $intervalHelper): ?string
    {
        $now = Carbon::now()->startOfDay();
        $intervals = $intervalHelper->getInterval($now);

        if (count($intervals) <= 0) {
            $intervals = $intervalHelper->getInterval($now->addDay());

        }

        $result = Arr::get(Arr::first($intervals), 'date', $now->format('Y-m-d'));

        $this->updateDeliverySubType($result);

        return $result;
    }

    private function getDefaultDeliveryTime(
        ReceiveInterval $intervalHelper,
        string $deliveryIntervalDate
    ): ?string {
        $result = '';

        $deliveryType = BuyerDeliveryType::getValue();

        if ($deliveryType === DeliveryTypeEnum::pickup()->value) {
            $result = $intervalHelper->getIntervalAllDay(
                Carbon::createFromFormat('Y-m-d', $deliveryIntervalDate)
            );
        } else {
            $intervals = $intervalHelper->getInterval(Carbon::parse($deliveryIntervalDate)->startOfDay());
            if (isset($intervals[0])) {
                $result = $intervals[0]['intervals'][0]['value'] ?? '';
            }
        }

        return $result;
    }

    private function getIntervalHelperInstance(): ReceiveInterval
    {
        $store = BuyerStore::getSelectedStore();
        $deliverySubType = BuyerDeliverySubType::getValue();

        return new ReceiveInterval($store, $deliverySubType);
    }

    private function getAvailableAttributeKeys(): array
    {
        return [
            self::ATTRIBUTE_DELIVERY_DATE_KEY,
            self::ATTRIBUTE_DELIVERY_TIME_KEY,
        ];
    }

    private function updateDeliverySubType(string $result): void
    {
        $deliveryType = BuyerDeliveryType::getValue();

        if ($result > now()->format('Y-m-d')) {
            $deliverySubType = $deliveryType === DeliveryTypeEnum::delivery()->value
                ? PolygonDeliveryTypeEnum::other()->value
                : PickupTypeEnum::other()->value;
        } else {
            $deliverySubType = $deliveryType === DeliveryTypeEnum::delivery()->value
                ? PolygonDeliveryTypeEnum::extended()->value
                : PickupTypeEnum::today()->value;
        }

        BuyerDeliverySubType::setValue($deliverySubType);
    }
}
