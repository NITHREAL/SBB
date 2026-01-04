<?php

namespace Domain\Basket\Services;

use Domain\Basket\DTO\ClearBasketDTO;
use Domain\Basket\DTO\SetBasketCountDTO;
use Domain\Basket\Exceptions\BasketDeliveryException;
use Domain\Basket\Exceptions\BasketProductException;
use Domain\Basket\Models\Basket;
use Domain\Basket\Services\OutputPreparing\BasketStructureBuilder;
use Domain\Basket\Services\Products\BasketProductService;
use Domain\Basket\Services\Promocode\BasketPromocodeSetService;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Product\Models\Product;
use Domain\Promocode\Exceptions\PromocodeException;
use Domain\Store\Models\Store;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Services\Buyer\Facades\BuyerBasketToken;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class BasketService
{
    private Basket $basket;

    private Store $store;

    public function __construct(
        private readonly BasketProductService      $basketProductService,
        private readonly BasketUpdateService       $basketUpdateService,
        private readonly BasketPromocodeSetService $basketPromocodeSetService,
    ) {
        $this->initBasket();

        $this->store = BuyerStore::getSelectedStore();
    }

    public function getBasket(): array
    {
        $this->actualizeProducts();

        $basketOutput = new BasketStructureBuilder($this->basket);

        return $basketOutput->getPreparedBasketData();
    }

    /**
     * @throws BasketProductException
     */
    public function addProduct(
        int $productId,
        bool $fromOrder = false,
        int $count = 1,
    ): array {
        $this->actualizeProducts();

        $product = $this->getProduct($productId);

        $result = $this->basketUpdateService->addProduct($this->basket, $product, $fromOrder, $count);

        if ($result) {
            $this->basket->refresh();

            $this->basket->touch();
        }

        $this->actualizeProducts();

        return $this->getBasket();
    }

    public function removeProduct(int $productId): array
    {
        $this->actualizeProducts();

        $product = Product::findOrFail($productId);

        $result = $this->basketUpdateService->removeProduct($this->basket, $product);

        if ($result) {
            $this->basket->refresh();

            $this->basket->touch();

            $this->actualizeProducts();

            // Проверяем наличие товаров, которые подходят условиям промокода и изменилось ли состояние промокода в корзине
//            if ($this->basketPromocodeSetService->promocodeStateByProductsChanged($this->basket)) {
//                $this->basket->refresh();
//
//                $this->actualizeProducts();
//            }
        }


        return $this->getBasket();
    }

    /**
     * @throws BasketProductException
     */
    public function incrementProduct(int $productId): array
    {
        $this->actualizeProducts();

        $product = $this->getBasketProduct($productId);

        $this->basketUpdateService->incrementProduct($this->basket, $product);

        $this->basket->refresh();

        $this->basket->touch();

        $this->actualizeProducts();

        return $this->getBasket();
    }

    /**
     * @throws BasketProductException
     */
    public function setCountProduct(SetBasketCountDTO $setBasketCountDTO): array
    {
        $this->actualizeProducts();

        $product = $this->getBasketProduct($setBasketCountDTO->getProductId());

        if ($product->is_weight) {
            $this->basketUpdateService->updateProductWeight($this->basket, $product, $setBasketCountDTO->getWeight());
        } else {
            $this->basketUpdateService->updateProductCount($this->basket, $product, $setBasketCountDTO->getCount());
        }

        $this->basket->refresh();

        $this->basket->touch();

        $this->actualizeProducts();

        return $this->getBasket();
    }

    /**
     * @throws BasketProductException
     */
    public function decrementProduct(int $productId): array
    {
        $this->actualizeProducts();

        $product = $this->getBasketProduct($productId);

        $this->basketUpdateService->decrementProduct($this->basket, $product);

        $this->basket->refresh();

        $this->basket->touch();

        $this->actualizeProducts();

        // Проверяем наличие товаров, которые подходят условиям промокода и изменилось ли состояние промокода в корзине
//        if ($this->basketPromocodeSetService->promocodeStateByProductsChanged($this->basket)) {
//            $this->basket->refresh();
//
//            $this->actualizeProducts();
//        }

        return $this->getBasket();
    }

    public function clear(?ClearBasketDTO $clearBasketDTO = null): array
    {
        $this->actualizeProducts();

        $this->basketUpdateService->clear($this->basket, $clearBasketDTO);

        // Если корзина очищается полностью (а не только недоступные товары)
//        if (empty($clearBasketDTO) || $clearBasketDTO->getIsOnlyUnavailable() === false) {
            // Чистим промокод
//            $this->basketPromocodeSetService->clearPromocode($this->basket);
            //Чистим купон
//            $this->basketCouponSetService->clearCoupon($this->basket);
//        }

        $this->basket->refresh();

        $this->basket->touch();

        $this->actualizeProducts();

        return $this->getBasket();
    }

    /**
     * @throws DeliveryTypeException
     * @throws BasketDeliveryException
     */
    public function setDeliveryParams(array $deliveryParams): array
    {
        $this->actualizeProducts();

        if (count($deliveryParams)) {
            $this->updateDeliveryParams($deliveryParams);

            $this->basket->refresh();

            $this->actualizeProducts();
        }

        return $this->getBasket();
    }

    public function updateDeliveryParams(array $deliveryParams): void
    {
        $this->actualizeProducts();

        $newDeliveryParamsCount = count($deliveryParams);

        if ($newDeliveryParamsCount) {
            $basketDeliveryParams = $this->basket->delivery_params;

            if (
                is_array($basketDeliveryParams)
                && $newDeliveryParamsCount < count($basketDeliveryParams)
            ) {
                $deliveryParams[] = Arr::last($basketDeliveryParams);
            }

            $this->basketUpdateService->updateDeliveryParams($this->basket, $deliveryParams);
        }
    }

    /**
     * @throws PromocodeException
     */
    public function setPromocode(string $promocode): array
    {
        $this->actualizeProducts();

        $result = $this->basketPromocodeSetService->setPromocode($this->basket, $promocode);

        if ($result) {
            $this->basket->refresh();

            $this->actualizeProducts();
        }

        return $this->getBasket();
    }

    public function clearPromocode(): array
    {
        $this->actualizeProducts();

        $result = $this->basketPromocodeSetService->clearPromocode($this->basket);

        if ($result) {
            $this->basket->refresh();

            $this->actualizeProducts();
        }

        return $this->getBasket();
    }

//    public function setCoupon(string $coupon): array
//    {
//        $this->actualizeProducts();
//
//        $result = $this->basketCouponSetService->setCoupon($this->basket, $coupon);
//
//        if ($result) {
//            $this->basket->refresh();
//
//            $this->actualizeProducts();
//        }
//
//        return $this->getBasket();
//    }

//    public function clearCoupon(): array
//    {
//        $this->actualizeProducts();
//
//        $result = $this->basketCouponSetService->clearCoupon($this->basket);
//
//        if ($result) {
//            $this->basket->refresh();
//
//            $this->actualizeProducts();
//        }
//
//        return $this->getBasket();
//    }

    public function clearAll(): void
    {
        $this->actualizeProducts();

        $this->basketUpdateService->clear($this->basket);
//        $this->basketCouponSetService->clearCoupon($this->basket);
        $this->basketPromocodeSetService->clearPromocode($this->basket);

        $this->basket->refresh();

        $this->basket->delivery_params = [];
        $this->basket->save();

        $this->actualizeProducts();
    }

    public function actualizeProducts(): void
    {
        $productsData = $this->basketProductService->getRecalculatedProducts($this->basket, $this->store);

        $this->basket->setRelation(
            'products',
            Arr::get($productsData, 'products', collect()),
        );

        $this->basket->setAttribute(
            'availableProducts',
            Arr::get($productsData, 'availableProducts', collect()),
        );

        $this->basket->setAttribute(
            'unavailableProducts',
            Arr::get($productsData, 'unavailableProducts', collect()),
        );
    }

    public function getBasketInstance(): Basket
    {
        return $this->basket;
    }

    private function initBasket(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            $basket = $this->getBasketByUser($user);

            if (empty($basket)) {
                $basket = $this->getBasketByToken();

                if ($basket && empty($basket->user_id)) {
                    $basket->user()->associate($user);

                    $basket->save();
                }
            }
        } else {
            $basket = $this->getBasketByToken();
        }

        if (empty($basket)) {
            $basket = $this->createBasket($user);
        }

        $this->basket = $basket;
    }

    private function createBasket(?User $user): Basket
    {
        $token = BuyerBasketToken::getValue();

        // Получаем корзину по пользователю или создаем новую, если пользователь неавторизован
        if ($user) {
            $basket = $this->getBasketByUser($user);

            // Если не получили, то создаем новую и привязываем к ней пользователя
            if (empty($basket)) {
                $basket = new Basket();
                $basket->user()->associate($user);
            }
        } else {
            $basket = new Basket();
        }

        // Обновляем токен для полученной/созданной корзины и сохраняем ее
        $basket->token = $token;
        $basket->save();

        return $basket;
    }

    private function getBasketByToken(string $token = null): ?object
    {
        $token = $token ?? BuyerBasketToken::getValue();

        return Basket::query()->whereToken($token)->first();
    }

    private function getBasketByUser(User $user): ?object
    {
        return Basket::query()->whereUser($user->id)->first();
    }

    /**
     * @throws BasketProductException
     */
    private function getProduct(int $productId): Product
    {
        $store1cId = $this->store->getAttribute('system_id');

        /** @var Product $product */
        $product = Product::query()
            ->baseQuery()
            ->whereStoreOneCId($store1cId)
            ->whereId($productId)
            ->first();

        if (empty($product)) {
            throw new BasketProductException();
        }

        return $product;
    }

    /**
     * @throws BasketProductException
     */
    private function getBasketProduct(int $productId): Product
    {
        $store1cId = $this->store->getAttribute('system_id');

        $product = Product::query()
            ->basketQuery($store1cId, $this->basket->id)
            ->where('products.id', $productId)
            ->first();

        if (empty($product)) {
            throw new BasketProductException('Товара с таким идентификатором в корзине нет');
        }

        return $product;
    }
}
