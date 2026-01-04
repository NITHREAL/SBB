<?php

namespace Domain\Order\Resources\Delivery;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DeliveryDateTimeDataResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'deliveryIntervalDate'      => Arr::get($this, 'deliveryIntervalDate'),
            'deliveryIntervalTime'      => Arr::get($this, 'deliveryIntervalTime'),
            'deliveryPolygonTypes'      => (array) Arr::get($this, 'deliveryPolygonTypes'),
        ];
    }
}
