<?php

namespace Domain\Tag\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TagResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'text'  => Arr::get($this->resource, 'text'),
            'color' => Arr::get($this->resource, 'color'),
            'slug'  => Arr::get($this->resource, 'slug'),
        ];
    }
}
