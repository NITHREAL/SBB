<?php

namespace Domain\Order\Resources\Delivery;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DeliveryDataResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token'                     => Arr::get($this, 'token'),
            'regionId'                  => Arr::get($this, 'regionId'),
            'regionTitle'               => Arr::get($this, 'regionTitle'),
            'cityMarketsTitle'                 => trans('admin.city.title', [
                'title' => mb_substr(Arr::get($this, 'cityTitle'), 0, -1, 'utf-8')
            ]),
            'cityTitleIn'               => mb_substr(Arr::get($this, 'cityTitle'), 0, -1, 'utf-8'),
            'cityTitle'                 => Arr::get($this, 'cityTitle'),
            'cityId'                    => (int) Arr::get($this, 'cityId'),
            'storeId'                   => Arr::get($this, 'storeId'),
            'storeName'                 => Arr::get($this, 'storeName'),
            'storeOneCId'               => Arr::get($this, 'store1cId'),
            'deliveryType'              => Arr::get($this, 'deliveryType'),
            'deliverySubType'           => Arr::get($this, 'deliverySubType'),
            'deliveryIntervalDate'      => Arr::get($this, 'deliveryIntervalDate'),
            'deliveryIntervalTime'      => Arr::get($this, 'deliveryIntervalTime'),
            'deliveryPolygonTypes'      => (array) Arr::get($this, 'deliveryPolygonTypes'),
            'address'                   => Arr::get($this, 'address'),
            'latitude'                  => (string) Arr::get($this, 'latitude'),
            'longitude'                 => (string) Arr::get($this, 'longitude'),
            'sumForFreeDelivery'        => Arr::get($this, 'sumForFreeDelivery'),
        ];
    }
}
