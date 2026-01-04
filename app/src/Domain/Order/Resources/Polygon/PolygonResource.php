<?php

namespace Domain\Order\Resources\Polygon;

use Illuminate\Http\Resources\Json\JsonResource;

class PolygonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'coordinates'   => $this->coordinates,
            'fillColor'     => $this->fill_color ?? '#0066ff',
            'strokeColor'   => $this->fill_color ?? '#0066ff',
            'prices'        => $this->deliveryPrices ? PolygonPriceResource::collection($this->deliveryPrices) : [],
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->addMeta([
            'includes' => [
                'prices' => PolygonPriceResource::class,
            ],
        ]);
    }
}
