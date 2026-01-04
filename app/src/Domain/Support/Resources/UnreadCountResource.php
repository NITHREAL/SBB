<?php

namespace Domain\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UnreadCountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'count'  => Arr::get($this->resource, 'count'),
        ];
    }
}
