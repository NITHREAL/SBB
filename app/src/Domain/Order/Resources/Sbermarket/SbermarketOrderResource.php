<?php

namespace Domain\Order\Resources\Sbermarket;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class SbermarketOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status'    => Arr::get($this->resource, 'status'),
            'number'    => Arr::get($this->resource, 'number'),
        ];
    }
}
