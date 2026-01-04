<?php

namespace Domain\Basket\Services\OutputPreparing\Splitter;

use Domain\Basket\Models\Basket;
use Domain\Basket\Services\OutputPreparing\Splitter\Components\PreorderBasketDeliveryParams;
use Domain\Basket\Services\OutputPreparing\Splitter\Components\SplitBasketData;
use Domain\Basket\Services\OutputPreparing\Splitter\Components\UsualBasketDeliveryParams;
use Domain\Product\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BasketSplitter
{
    private const DEFAULT_PREORDER_DELIVERY_DAYS = 3;

    private Collection $basketAvailableProducts;

    private Collection $basketUnavailableProducts;

    private array $deliveryParams;

    private array $preparedBasket = [];

    private readonly UsualBasketDeliveryParams $usualBasketParams;

    private readonly PreorderBasketDeliveryParams $preorderBasketParams;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        private Basket $basket,
    ) {
        $this->basketAvailableProducts = $this->basket->availableProducts;
        $this->basketUnavailableProducts = $this->basket->unavailableProducts;
        $this->deliveryParams = (array) $this->basket->delivery_params;

        $this->usualBasketParams = app()->make(UsualBasketDeliveryParams::class);
        $this->preorderBasketParams = app()->make(PreorderBasketDeliveryParams::class);
    }

    public function getSplittedByDateBaskets(): array
    {
        $this->handleUsualBasket();
        $this->handlePreorderBasket();

        return array_values($this->preparedBasket);
    }

    private function handleUsualBasket(): void
    {
        $deliveryParams = $this->usualBasketParams->getDeliveryParams($this->deliveryParams);
        $availableProducts = collect();
        $unavailableProducts = $this->basketUnavailableProducts->where('by_preorder', false);

        foreach ($this->basketAvailableProducts as $key => $product) {
            if ($this->isProductDeliveryAvailable($product, Arr::get($deliveryParams, 'deliveryIntervalDate'))) {
                $availableProducts->push($product);

                $this->basketAvailableProducts->forget($key);
            } else {
                if (!$product->by_preorder) {
                    $unavailableProducts->push($product);
                }
            }
        }

        $preparedBasket = $this->getPreparedBasket($availableProducts, $unavailableProducts, $deliveryParams);

        $this->addBasketToResults($preparedBasket);
    }

    private function handlePreorderBasket(): void
    {
        $deliveryParams = $this->preorderBasketParams->getDeliveryParams($this->deliveryParams);
        $availableProducts = collect();
        $unavailableProducts = $this->basketUnavailableProducts->where('by_preorder', true);

        $products = $this->basketAvailableProducts->where('by_preorder', true);

        foreach ($products as $product) {
            $availableProducts->push($product);
        }

        $preparedBasket = $this->getPreparedBasket($availableProducts, $unavailableProducts, $deliveryParams);

        $this->addBasketToResults($preparedBasket);

    }

    private function addBasketToResults(array $basket): void
    {
        $basketProducts = Arr::get($basket, 'products', []);
        $basketUnavailableProducts = Arr::get($basket, 'unavailable_products', []);

        if (count($basketProducts) || count($basketUnavailableProducts)) {
            $deliveryDate = Arr::get($basket, 'date');
            $existedBasket = Arr::get($this->preparedBasket, $deliveryDate);

            if ($existedBasket) {
                /** @var Collection $existedProducts */
                $existedProducts = Arr::get($existedBasket, 'products');

                $this->preparedBasket[$deliveryDate]['products'] = $existedProducts->merge($basketProducts);
            } else {
                $this->preparedBasket[$deliveryDate] = $basket;
            }
        }
    }

    private function getPreparedBasket(
        Collection $availableProducts,
        Collection $unavailableProducts,
        array $basketParams,
    ): array {
        $splitBasketData = new SplitBasketData($this->basket);

        return $splitBasketData->getPreparedBasket($availableProducts, $unavailableProducts, $basketParams);
    }

    private function isProductDeliveryAvailable(Product $product, string $basketDate): bool
    {
        return !$product->by_preorder || $this->handlePreorderProductDeliveryAvailable($product, $basketDate);
    }

    private function handlePreorderProductDeliveryAvailable(Product $product, string $basketDate): bool
    {
        $result = true;
        $defaultPreorderDate = $this->getDefaultPreorderDeliveryDate();

        if ($basketDate < $defaultPreorderDate) {// Если дата доставки/получения меньше стандартной для предзаказа
            $nearestDeliveryDate = $product->nearest_date;

            if (empty($nearestDeliveryDate) && $product->available_count === 0) {
                $result = false;
            }
        }

        return $result;
    }

    private function getDefaultPreorderDeliveryDate(): string
    {
        return Carbon::now()->addDays(self::DEFAULT_PREORDER_DELIVERY_DAYS)->format('Y-m-d');
    }
}
