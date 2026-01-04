<?php

namespace Domain\User\Services\Delivery;

use Domain\Basket\Services\BasketService;
use Domain\Order\DTO\Delivery\DeliveryDateTimeSetDTO;
use Domain\Order\DTO\Delivery\DeliveryTypeByCityDTO;
use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Services\Delivery\DeliveryTypeService;
use Domain\Order\Services\Delivery\Exceptions\DeliveryDateTimeSetException;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\User\DTO\Delivery\DeliveryAddressChosenDTO;
use Domain\User\Services\Addresses\AddressesService;
use Domain\User\Services\Store\UserStoreService;
use Infrastructure\Services\DaData\Exceptions\DaDataException;

readonly class UserDeliveryService
{
    public function __construct(
        private DeliveryTypeService $deliveryTypeService,
        private BasketService $basketService,
        private AddressesService  $addressesService,
        private UserStoreService $storeService,
    ) {
    }

    /**
     * @throws DeliveryTypeException
     */
    public function setDeliveryData(DeliveryTypeSetDTO $deliveryDTO, int $userId): array
    {
        $deliveryParams = $this->deliveryTypeService->processDeliveryTypeSet($deliveryDTO);

        // Обновляем данные по доставки для корзины
        $this->basketService->updateDeliveryParams([$deliveryParams]);

        $this->setDeliveryAddressChosen(
            DeliveryAddressChosenDTO::make($deliveryParams, $userId)
        );

        return $deliveryParams;
    }

    /**
     * @throws DaDataException
     */
    public function setDeliveryDataByCity(DeliveryTypeByCityDTO $deliveryDTO): array
    {
        $deliveryParams = $this->deliveryTypeService->processDeliveryTypeByCity($deliveryDTO);

        // Обновляем данные по доставки для корзины
        $this->basketService->updateDeliveryParams([$deliveryParams]);

        return $deliveryParams;
    }

    /**
     * @throws DaDataException
     */
    public function getCurrentCachedDeliveryData(): array
    {
        $deliveryParams = $this->deliveryTypeService->getCurrentDeliveryTypeData();

        // Обновляем данные по доставки для корзины
        $this->basketService->updateDeliveryParams([$deliveryParams]);

        return $deliveryParams;
    }

    /**
     * @throws DeliveryDateTimeSetException
     * @throws DaDataException
     */
    public function setDateTimeDeliveryData(DeliveryDateTimeSetDTO $deliveryDTO): array
    {
        $deliveryParams = $this->deliveryTypeService->processDeliveryDateTimeSet($deliveryDTO);

        // Обновляем данные по доставки для корзины
        $this->basketService->updateDeliveryParams([$deliveryParams]);

        return $deliveryParams;
    }

    private function setDeliveryAddressChosen(DeliveryAddressChosenDTO $deliveryData): void
    {
        $userId = $deliveryData->getUserId();

        if (OrderDeliveryHelper::isDelivery($deliveryData->getDeliveryType())) {
            $this->addressesService->setAddressChosen($deliveryData->getAddress(), $userId);
        } else {
            $this->storeService->setStoreChosen($deliveryData->getStoreId(), $userId);
        }
    }
}
