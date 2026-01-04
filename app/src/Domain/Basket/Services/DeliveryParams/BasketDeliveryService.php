<?php

namespace Domain\Basket\Services\DeliveryParams;

use Domain\Basket\DTO\Delivery\BasketsDeliveryDTO;
use Domain\Basket\Exceptions\BasketDeliveryException;
use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Domain\Order\Services\Delivery\DeliveryTypeService;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Infrastructure\Services\Buyer\BuyerDeliveryDataSetService;

class BasketDeliveryService
{
    public function __construct(
        private readonly DeliveryTypeService $deliveryTypeService,
    ) {
    }

    /**
     * @throws BasketDeliveryException
     * @throws DeliveryTypeException
     */
    public function getPreparedDeliveryParams(BasketsDeliveryDTO $basketDTO): array
    {
        $deliveryParams = $this->getBasketDeliveryParams($basketDTO->getBaskets());

        if (count($deliveryParams) > 1) {
            // Сортируем данные по доставке, чтобы в кэш сохранить данные для обычной корзины (где дата меньше)
            ksort($deliveryParams);

            $this->checkPreorderDeliveryDate($deliveryParams);
        }

        // Обновляем данные доставки обычной корзины в кэше
        $this->updateBuyerData(
            Arr::first($deliveryParams)
        );

        return $deliveryParams;
    }

    /**
     * @throws DeliveryTypeException
     */
    private function getBasketDeliveryParams(array $baskets): array
    {
        $deliveryParams = [];

        foreach ($baskets as $date => $basket) {
            if ($basket instanceof DeliveryTypeSetDTO) {
                $data = $this->deliveryTypeService->processDeliveryTypeSet($basket);

                $deliveryParams[$date] = $data;
            }
        }

        return Arr::sort($deliveryParams);
    }

    /**
     * @throws BasketDeliveryException
     */
    private function checkPreorderDeliveryDate(array $deliveryParams): void
    {
        $basketPreorderDate = Arr::last(Arr::sort($deliveryParams));

        //  Для товаров по предзаказу, если они не в наличии на сегодня, дата доставки должна быть через 3 дня
        if ($basketPreorderDate < Carbon::now()->addDays(3)->format('Y-m-d')) {
            throw new BasketDeliveryException(
                'Дата доставки для товаров по предзаказу должна быть не менее чем через 3 дня от текущей'
            );
        }
    }

    private function updateBuyerData(array $data): void
    {
        BuyerDeliveryDataSetService::setBuyerDeliveryData($data);
    }
}
