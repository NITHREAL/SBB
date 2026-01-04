<?php

namespace Domain\Basket\Services\OutputPreparing\Splitter\Components;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Illuminate\Support\Arr;
use Infrastructure\Services\Buyer\Facades\BuyerAddress;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryInterval;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class UsualBasketDeliveryParams implements BasketDeliveryParamsInterface
{
    private string $deliveryType;

    private string $deliverySubType;

    private ?string $deliveryIntervalDate;

    private ?string $deliveryIntervalTime;

    private ?string $deliveryAddress;

    private array $storeData;

    public function __construct()
    {
        $this->deliveryType = BuyerDeliveryType::getValue();
        $this->deliverySubType = BuyerDeliverySubType::getValue();
        $this->deliveryIntervalDate = BuyerDeliveryInterval::getDeliveryDateInterval();
        $this->deliveryIntervalTime = BuyerDeliveryInterval::getDeliveryTimeInterval();
        $this->deliveryAddress = BuyerAddress::getValue();
        $this->storeData = BuyerStore::getValue();
    }

    public function getDeliveryParams(array $basketParams): array
    {
        $defaultParams = $this->getDefaultDeliveryParams();

        if (count($basketParams)) {
            // Если параметры доставки для корзины заадны, то берем те, которые на более близкую дату
            $deliveryParams = Arr::first($basketParams);

            $deliveryParams = $this->prepareDeliveryParams($deliveryParams, $defaultParams);
        } else {
            $deliveryParams = $defaultParams;
        }

        return $deliveryParams;
    }

    private function prepareDeliveryParams(array $deliveryParams, array $defaultDeliveryParams): array
    {
        $deliveryIntervalDate = Arr::get($deliveryParams, 'deliveryIntervalDate');
        $defaultDeliveryIntervalDate = Arr::get($defaultDeliveryParams, 'deliveryIntervalDate');

        $deliveryParams['deliveryIntervalDate'] = !empty($deliveryIntervalDate)
        && $deliveryIntervalDate >= $defaultDeliveryIntervalDate
            ? $deliveryIntervalDate
            : $defaultDeliveryIntervalDate;

        $deliveryParams['deliveryType'] = Arr::get($deliveryParams, 'deliveryType')
            ?? Arr::get($defaultDeliveryParams, 'deliveryType');

        $deliveryParams['deliveryIntervalTime'] = Arr::get($defaultDeliveryParams, 'deliveryIntervalTime')
            ?? Arr::get($defaultDeliveryParams, 'deliveryIntervalTime');

        $deliveryParams['deliverySubType'] = Arr::get($deliveryParams, 'deliverySubType')
            ?? Arr::get($defaultDeliveryParams, 'deliverySubType');

        $deliveryParams['address'] = $this->getDeliveryParamsAddress($deliveryParams);

        return $deliveryParams;
    }

    private function getDeliveryParamsAddress(array $deliveryParams): ?string
    {
        $address = Arr::get($deliveryParams, 'address');

        // Если для корзины присвоены дефолтные данные для доставки, в которых нет адреса, то
        // если выбрана доставка - присваивается адрес из кэша
        // если выбран самовывоз - присваивается адрес магазина, из которого будет производиться самовывоз
        if (empty($address)) {
            $address = OrderDeliveryHelper::isDelivery(Arr::get($deliveryParams, 'deliveryType'))
                ? $this->deliveryAddress
                : Arr::get($this->storeData, 'title');
        } else {
            // Если для корзины был сохранен адрес, но в кэше сейчас другой тип доставки, то берем адрес из кэша
            $basketDeliveryType = Arr::get($deliveryParams, 'deliveryType');

            if (BuyerDeliveryType::getValue() !== $basketDeliveryType) {
                $address = $basketDeliveryType === DeliveryTypeEnum::delivery()->value
                    ? $this->deliveryAddress
                    : Arr::get($this->storeData, 'title');
            }
        }

        return $address;
    }

    private function getDefaultDeliveryParams(): array
    {
        return [
            'deliveryIntervalDate'  => $this->deliveryIntervalDate,
            'deliveryIntervalTime'  => $this->deliveryIntervalTime,
            'deliveryType'          => $this->deliveryType,
            'deliverySubType'       => $this->deliverySubType,
            'address'               => $this->deliveryAddress,
        ];
    }
}
