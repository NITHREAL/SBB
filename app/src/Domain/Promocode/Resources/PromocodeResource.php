<?php

namespace Domain\Promocode\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromocodeResource extends JsonResource
{
    public function toArray($request): array
    {
        $promocode = $this->resource;

        return [
            'id'                => $promocode->id,
            'code'              => $promocode->code,
            'discount'          => $promocode->discount,
            'percentage'        => $promocode->percentage,
            'min_amount'        => $promocode->min_amount,
            'order_type'        => $promocode->order_type,
            'delivery_type'     => $promocode->delivery_type,
            'any_product'       => $promocode->any_product,
            'any_user'          => $promocode->any_user,
            'free_delivery'     => $promocode->free_delivery,
            'title'             => $promocode->title,
            'description'       => $promocode->description,
            'one_use_per_phone' => $promocode->one_use_per_phone,
            'only_one_use'      => $promocode->only_one_use,
            'use_excluded'      => $promocode->use_excluded,
        ];
    }
}
