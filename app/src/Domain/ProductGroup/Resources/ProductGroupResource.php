<?php

declare(strict_types=1);

namespace Domain\ProductGroup\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductGroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'image'             => $this->image_original,
            'imageBlurHash'     => $this->image_blur_hash,
            'sort'              => $this->sort,
        ];
    }
}
