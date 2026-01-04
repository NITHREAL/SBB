<?php

namespace Domain\Basket\Services\Promocode;

use Domain\Basket\Models\Basket;
use Domain\Promocode\Exceptions\PromocodeException;
use Domain\Promocode\Models\Promocode;
use Domain\Promocode\Service\PromocodeCheck;
use Illuminate\Support\Arr;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;

class BasketPromocodeSetService
{
    /**
     * @throws PromocodeException
     */
    public function setPromocode(Basket $basket, string $code): bool
    {
        $promocode = Promocode::query()->active()->whereActual()->whereCode($code)->first();

        if (empty($promocode)) {
            throw new PromocodeException('Промокод несуществует или устарел');
        }

        $this->checkPromocode($promocode, $basket);

        $originalBasket = Basket::findOrFail($basket->id);

        $originalBasket->promocode()->associate($promocode);

        return $originalBasket->save();
    }

    public function clearPromocode(Basket $basket): bool
    {
        $originalBasket = Basket::findOrFail($basket->id);

        $originalBasket->promocode()->dissociate();

        return $originalBasket->save();
    }

    /**
     * @throws PromocodeException
     */
    private function checkPromocode(Promocode $promocode, Basket $basket): void
    {
        $user = $basket->user;
        $userPhone = $user?->phone;
        $userId = $user->id;

        $deliveryType = $this->getBasketDeliveryType($basket);
        $basketProducts = $basket->availableProducts;
        $basketTotal = $basketProducts->sum(fn($item) => $item->sum);

        $promocodeCheck = new PromocodeCheck($promocode);

        $promocodeCheck->checkUsingByDeliveryType($deliveryType);

        if (empty($userPhone)) {
            throw new PromocodeException('Данный промокод требует авторизации', 400);
        }

        $promocodeCheck->checkMultipleUsing($userPhone);

        $promocodeCheck->checkUsingByUser($userId);

        $promocodeCheck->checkUsingForProducts($basketProducts->toArray());

        $promocodeCheck->checkAllowPromocodeUsingByUser($userId);

        $promocodeCheck->checkProductsSum($basketTotal);
    }

    private function getBasketDeliveryType(Basket $basket): string
    {
        $basketDeliveryParams = Arr::first($basket->delivery_params);

        return $basketDeliveryParams
            ? Arr::get($basketDeliveryParams, 'deliveryType')
            : BuyerDeliveryType::getValue();
    }
}
