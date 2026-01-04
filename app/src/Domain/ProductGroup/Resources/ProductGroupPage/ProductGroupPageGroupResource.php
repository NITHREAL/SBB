<?php

namespace Domain\ProductGroup\Resources\ProductGroupPage;

use Domain\Story\Resources\StoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ProductGroupPageGroupResource extends JsonResource
{
    public function toArray($request): array
    {
        $storyData = Arr::get($this->resource, 'storyData');

        return [
            'id'                => Arr::get($this->resource, 'id'),
            'title'             => Arr::get($this->resource, 'title'),
            'slug'              => Arr::get($this->resource, 'slug'),
            'image'             => Arr::get($this->resource, 'imageOriginal'),
            'imageBlurHash'     => Arr::get($this->resource, 'imageBlurHash'),
            'backgroundImage'   => Arr::get($this->resource, 'backgroundImage'),
            'story'             => $storyData
                ? StoryResource::make($storyData)
                : null,
        ];
    }
}
