<?php

namespace Domain\Basket\Services;

use Domain\Basket\DTO\ClearBasketDTO;
use Domain\Basket\Exceptions\BasketProductException;
use Domain\Basket\Models\Basket;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Product\Models\Product;
use Domain\Product\Services\Leftover\ProductLeftoverService;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;

class BasketUpdateService
{
    private const DEFAULT_WEIGHT = 0.1;

    public function __construct(
        private readonly ProductLeftoverService $leftoverService,
    ) {
    }

    /**
     * @throws BasketProductException
     */
    public function addProduct(
        Basket $basket,
        Product $product,
        bool $fromOrder = false,
        int $count = 1,
    ): bool {
        $result = false;

        if ($basket->products->where('id', $product->id)->count() < 1) {
            $canAdd = $this->canAddProduct($product);
            $onOtherDay = OrderDeliveryHelper::onOtherDay(BuyerDeliverySubType::getValue());

            $availableToAdd = $canAdd || $fromOrder || $onOtherDay;

            if (!$availableToAdd) {
                throw new BasketProductException('Товара нет в наличии');
            }

            $weight = $product->is_weight ? self::DEFAULT_WEIGHT : $product->weight;

            $basket->products()->attach(
                $product,
                [
                    'count' => $count,
                    'weight' => $weight,
                    'from_order' => $fromOrder
                ]
            );

            $result = true;
        }

        return $result;
    }

    public function removeProduct(
        Basket $basket,
        Product $product,
    ): bool {
        $result = false;

        if ($basket->products->where('id', $product->id)->count()) {
            $basket->products()->detach($product);

            $result = true;
        }

        return $result;
    }

    /**
     * @throws BasketProductException
     */
    public function incrementProduct(
        Basket $basket,
        Product $product,
    ): void {
        if ($product->is_weight) {
            $weight = $product->basketWeight + 0.1;

            $this->setProductWeight($basket, $product, $weight);
        } else {
            $count = $product->count + 1;

            $this->setProductCount($basket, $product, $count);
        }
    }

    /**
     * @throws BasketProductException
     */
    public function updateProductCount(
        Basket $basket,
        Product $product,
        int $count,
    ): void {
        $this->setProductCount($basket, $product, $count);
    }

    public function updateProductWeight(
        Basket $basket,
        Product $product,
        float $weight,
    ): void {
        $this->setProductWeight($basket, $product, $weight);
    }

    public function decrementProduct(
        Basket $basket,
        Product $product,
    ): void {
        if ($product->is_weight) {
            $weight = $product->basketWeight - 0.1;

            $this->decrementProductWeight($basket, $product, $weight);
        } else {
            $count = $product->count - 1;

            $this->decrementProductCount($basket, $product, $count);
        }
    }

    public function decrementProductCount(Basket $basket, Product $product, int $count): void
    {
        if ($count > 0) {
            $basket->products()->updateExistingPivot($product->id, ['count' => $count]);
        } else {
            $this->removeProduct($basket, $product);
        }
    }

    public function decrementProductWeight(Basket $basket, Product $product, float $weight): void
    {
        if ($weight > 0) {
            $basket->products()->updateExistingPivot($product->id, ['weight' => $weight]);
        } else {
            $this->removeProduct($basket, $product);
        }
    }

    public function clear(
        Basket $basket,
        ?ClearBasketDTO $clearBasketDTO = null,
    ): void {
        if (!empty($clearBasketDTO) && $clearBasketDTO->getIsOnlyUnavailable()) {
            $unavailableProductIds = $basket->unavailableProducts->pluck('id')->toArray();

            $basket->products()->detach($unavailableProductIds);
        } else {
            $basket->products()->detach();
        }
    }

    public function updateDeliveryParams(
        Basket $basket,
        array $deliveryParams,
    ): void {
        Basket::query()
            ->whereId($basket->id)
            ->update([
                'delivery_params' => $deliveryParams
            ]);
    }

    /**
     * @throws BasketProductException
     */
    private function setProductCount(
        Basket $basket,
        Product $product,
        int $count,
    ): void {
        $count = max($count, 1);

        $product = $this->setLeftoverProperties($product);

        if (!$this->canIncreaseProductCount($product, $count)) {
            throw new BasketProductException(
                'Выбрано максимальное количество товара. Приобрести больше можно выбрав заказ на другой день'
            );
        }

        $basket->products()->updateExistingPivot($product->id, ['count' => $count]);
    }

    /**
     * @throws BasketProductException
     */
    private function setProductWeight(
        Basket $basket,
        Product $product,
        float $weight
    ): void {
        $weight = max($weight, 0.1);

        $product = $this->setLeftoverProperties($product);

        if (!$this->canIncreaseProductWeight($product, $weight)) {
            throw new BasketProductException(
                'Выбран максимальный вес товара'
            );
        }

        $basket->products()->updateExistingPivot($product->id, ['weight' => $weight]);
    }

    private function canAddProduct(Product $product): bool
    {
        if ($product->cooking) {
            return true;
        }

        $inSpecialCategory = $product->categories()->where('special_type', true)->count();

        if ($inSpecialCategory) {
            return true;
        }

        if ($product->by_preorder) {
            return true;
        }

        $product = $this->setLeftoverProperties($product);

        return $product->date_supply || $product->available_count;
    }

    private function canIncreaseProductCount(Product $product, int $count): bool
    {
        return $count <= $product->available_count
            || $product->cooking
            || $product->by_preorder;
    }

    private function canIncreaseProductWeight(Product $product, float $weight): bool
    {
        return $weight <= $product->available_count
            || $product->cooking
            || $product->by_preorder;
    }

    private function setLeftoverProperties(Product $product): Product
    {
        return $this->leftoverService->setLeftoverPropertiesForOne($product);
    }
}
