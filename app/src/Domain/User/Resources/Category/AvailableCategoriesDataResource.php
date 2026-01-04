<?php

namespace Domain\User\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AvailableCategoriesDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $categories = Arr::get($this->resource, 'categories', []);

        return [
            'period'        => Arr::get($this->resource, 'period'),
            'categories'    => CategoryResource::collection($categories),
        ];
    }
}
