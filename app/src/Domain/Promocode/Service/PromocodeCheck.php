<?php

namespace Domain\Promocode\Service;

use Domain\Product\Models\Product;
use Domain\Promocode\Enums\PromocodeDeliveryTypeEnum;
use Domain\Promocode\Exceptions\PromocodeException;
use Domain\Promocode\Models\Promocode;
use Domain\Promocode\Models\PromocodeUsedPhone;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PromocodeCheck
{
    private PromocodeService $promocodeService;

    public function __construct(
        private Promocode $promocode,
    ) {
        $this->promocodeService = app()->make(PromocodeService::class);
    }

    public function createPhoneUsed(string $phone): void
    {
        if ($this->onlyOneUseAvailable() || $this->onlyOneUsePerPhone()) {
            $usedByPhone = new PromocodeUsedPhone();
            $usedByPhone->promo_id = $this->promocode->id;
            $usedByPhone->phone = $phone;
            $usedByPhone->save();
        }
    }

    /**
     * @throws PromocodeException
     */
    public function checkUsingByDeliveryType(string $deliveryType): void
    {
        if ($this->canUseByDeliveryType($deliveryType) === false) {
            $message = 'Промокод доступен только при способе получения заказа: ';

            $message .= match ($this->promocode->delivery_type) {
                PromocodeDeliveryTypeEnum::pickup()->value      => PromocodeDeliveryTypeEnum::pickup()->label,
                PromocodeDeliveryTypeEnum::delivery()->value    => PromocodeDeliveryTypeEnum::delivery()->label
            };

            throw new PromocodeException($message);
        }
    }

    /**
     * @throws PromocodeException
     */
    public function checkMultipleUsing(string $phone): void
    {
        if ($this->onlyOneUseAvailable() && $this->isPromocodeUsed()) {
            throw new PromocodeException('Данный промокод уже был использован', 400);
        }

        if ($this->onlyOneUsePerPhone() && $this->isPromocodeUsedByPhone($phone)) {
            throw new PromocodeException('Вы уже использовали этот промокод', 400);
        }
    }

    /**
     * @throws PromocodeException
     */
    public function checkUsingByUser(int $userId): void
    {
        if (empty($this->promocode->any_user) && !$this->isUserAvailableForPromocode($userId)) {
            throw new PromocodeException('Вы не можете использовать этот промокод', 400);
        }
    }

    /**
     * @throws PromocodeException
     */
    public function checkUsingForProducts(array $products): void
    {
        if (
            !$this->promocode->any_product
            && $this->promocode->use_excluded
            && $this->canUseForProducts($products) === false
        ) {
            throw new PromocodeException('В вашей корзине нет товаров, удовлетворяющих условию промокода', 400);
        }
    }

    /**
     * @throws PromocodeException
     */
    public function checkAllowPromocodeUsingByUser(int $userId): void
    {
        if (!$this->promocode->any_user) {
            $usedCount = $this->promocodeService->getUsedCountByUser($this->promocode->id, $userId);
            $canUse = DB::table('promo_user')
                ->where('promo_id', $this->promocode->id)
                ->where('user_id', $userId)
                ->where(function ($query) use ($usedCount) {
                    $query
                        ->where('max_uses', '>', $usedCount)
                        ->orWhereNull('max_uses');
                })
                ->count() > 0;

            if (!$canUse) {
                throw new PromocodeException('Этот промокод уже использован', 400);
            }
        }
    }

    /**
     * @throws PromocodeException
     */
    public function checkProductsSum(float $sum): void
    {
        if (!$this->isProductsSumAvailable($sum)) {
            $message = "Для использования промокода сумма заказа должна быть не менее {$this->promocode->min_amount} руб.";

            throw new PromocodeException($message, 400);
        }
    }

    private function isUserAvailableForPromocode(int $userId): bool
    {
        return $this->promocode->users()->where('id', $userId)->exists();
    }

    private function isPromocodeUsedByPhone(string $phone): bool
    {
        return PromocodeUsedPhone::query()
            ->wherePromocode($this->promocode->id)
            ->wherePhone($phone)
            ->exists();
    }

    private function isPromocodeUsed(): bool
    {
        return PromocodeUsedPhone::query()
            ->wherePromocode($this->promocode->id)
            ->exists();
    }

    private function onlyOneUseAvailable(): bool
    {
        return (bool) $this->promocode->only_one_use;
    }

    private function onlyOneUsePerPhone(): bool
    {
        return (bool) $this->promocode->one_use_per_phone;
    }

    private function isProductsSumAvailable(float $sum): bool
    {
        $promocodeMinAmount = $this->promocode->min_amount;

        return empty($promocodeMinAmount) || $sum >= $promocodeMinAmount;
    }

    private function canUseByDeliveryType(string $deliveryType): bool
    {
        $promocodeDeliveryType = $this->promocode->delivery_type;

        return $promocodeDeliveryType === PromocodeDeliveryTypeEnum::any()->value
            || $promocodeDeliveryType === $deliveryType;
    }

    private function canUseForProducts(array $products): bool
    {
        foreach ($products as $product) {
            if ($this->canUseForProduct($product)) {
                return true;
            }
        }

        return false;
    }

    // TODO данный метод не перерыбатывался
    private function canUseForProduct(object $product): bool
    {
        $categoriesId = [];

        if ($this->promocode->use_excluded || $this->promocode->categories) {
            // Итерация по категориям продукта
            foreach ($product->categories as $category) {
                // Получение идентификаторов всех предков текущей категории (включая саму категорию)
                $ids = $category->getAncestorsAndSelf()
                    ->pluck('id')
                    ->toArray();

                // Объединение идентификаторов категорий
                $categoriesId = array_merge($categoriesId, $ids);
            }
        }

        // Проверка, используются ли исключения
        if ($this->promocode->use_excluded) {
            $productGroupIds = $product->groups->pluck('id')->toArray();

            // Проверка, присутствует ли категория товара в списке исключенных
            $isExcludedCategory = (bool)$this->promocode
                ->excludedCategories
                ->whereIn('id', $categoriesId)
                ->count();

            // Проверка, присутствует ли товар в списке исключенных
            $isExcludedProduct = (bool)$this->promocode
                ->excludedProducts
                ->find($product->id);

            // Проверка, присутствует ли подборки товара в списке исключенных
            $isExcludedGroup = $this->promocode
                ->excludedGroups
                ->whereIn('id', $productGroupIds)
                ->count();

            // Если категория или товар присутствуют в исключениях, возвращаем false
            if ($isExcludedCategory || $isExcludedProduct || $isExcludedGroup) {
                return false;
            }
        }

        // Проверка, может ли промокод использоваться для любого продукта
        if ($this->promocode->any_product) {
            return true;
        }

        $canUseByCategories = false;
        $canUseByProduct = false;

        // Проверка, связаны ли с промокодом какие-либо категории продукта
        if ($this->promocode->categories) {
            // Проверка, присутствует ли хотя бы одна категория из продукта в списке разрешенных для промокода
            $canUseByCategories = (bool)$this->promocode
                ->categories
                ->whereIn('id', $categoriesId)
                ->count();
        }

        // Проверка, присутствует ли конкретный продукт в списке разрешенных для промокода
        if ($this->promocode->products->count() > 0) {
            $canUseByProduct = (bool)$this->promocode->products->find($product->id);
        }

        // Возвращаем true, если промокод может быть использован для данного продукта
        return $canUseByCategories || $canUseByProduct;
    }
}
