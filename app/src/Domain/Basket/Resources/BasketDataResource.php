<?php

namespace Domain\Basket\Resources;

use Domain\Product\Helpers\ProductHelper;
use Domain\Product\Resources\Catalog\CatalogProductResource;
use Domain\Promocode\Models\Promocode;
use Domain\CouponCategory\Models\CouponCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BasketDataResource extends JsonResource
{
    public function toArray($request): array
    {
        $basketData = $this->resource;

        /** @var Promocode $promocode */
        $promocode = Arr::get($basketData, 'promocode');
        /** @var CouponCategory $coupon */
        $coupon = Arr::get($basketData, 'coupon');

        return [
            'token'                         => Arr::get($basketData, 'token'),
            'promocode'                     => $promocode?->code,
            'coupon'                        => $coupon?->category_coupon_guid,
            'couponTitle'                   => $coupon?->name,
            'total'                         => ProductHelper::getPreparedProductPrice(Arr::get($basketData, 'total')),
            'totalWithoutDiscount'          => ProductHelper::getPreparedProductPrice(Arr::get($basketData, 'totalWithoutDiscount')),
            'productsTotal'                 => ProductHelper::getPreparedProductPrice(Arr::get($basketData, 'productsTotal')),
            'productsTotalWithoutDiscount'  => ProductHelper::getPreparedProductPrice(Arr::get($basketData, 'productsTotalWithoutDiscount')),
            'discount'                      => ProductHelper::getPreparedProductPrice(Arr::get($basketData, 'discount')),
            'delivery'                      => ProductHelper::getPreparedProductPrice(Arr::get($basketData, 'delivery')),
            'bonuses'                       => Arr::get($basketData, 'bonuses'),
            'baskets'                       => BasketResource::collection(
                Arr::get($basketData, 'baskets'),
            ),
            'proposedProducts'              => CatalogProductResource::collection(
                Arr::get($basketData, 'proposedProducts'),
            ),
        ];
    }
}
