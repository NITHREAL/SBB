<?php

namespace Domain\Basket\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BasketResource extends JsonResource
{
    public function toArray($request): array
    {
        $basket = $this->resource;

        return [
            'date'                          => Arr::get($basket, 'date'),
            'time'                          => Arr::get($basket, 'time'),
            'deliveryType'                  => Arr::get($basket, 'delivery_type'),
            'deliverySubType'               => Arr::get($basket, 'delivery_sub_type'),
            'deliveryPrice'                 => Arr::get($basket, 'delivery_price'),
            'deliveryAddress'               => Arr::get($basket, 'address'),
            'forFreeDelivery'               => Arr::get($basket, 'for_free_delivery'),
            'storeOneCId'                   => Arr::get($basket, 'store_system_id'),
            'storeId'                       => Arr::get($basket, 'store_id'),
            'storeName'                     => Arr::get($basket, 'store_name'),
            'cityId'                        => Arr::get($basket, 'city_id'),
            'total'                         => Arr::get($basket, 'total'),
            'totalWithoutDiscount'          => Arr::get($basket, 'total_without_discount'),
            'productsTotal'                 => Arr::get($basket, 'products_total'),
            'weightTotal'                   => round(Arr::get($basket, 'weight_total'), 3),
            'productsTotalWithoutDiscount'  => Arr::get($basket, 'products_total_prev'),
            'discount'                      => Arr::get($basket, 'discount'),
            'products'                      => BasketProductResource::collection(
                Arr::get($basket, 'products'),
            ),
            'productsCount'                 => count(Arr::get($basket, 'products')),
            'unavailableProducts'           => BasketProductResource::collection(
                Arr::get($basket, 'unavailable_products'),
            ),
            'isAvailable'                   => Arr::get($basket, 'is_available'),
            'availableFrom'                 => Arr::get($basket, 'available_from'),
            'timeLabel'                     => Arr::get($basket, 'time_label'),
        ];
    }
}
