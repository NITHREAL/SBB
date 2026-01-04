<?php

namespace Domain\Order\Resources\Polygon;

use Illuminate\Http\Resources\Json\JsonResource;

class PolygonPriceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'price' => $this->price,
        ];
    }
}
