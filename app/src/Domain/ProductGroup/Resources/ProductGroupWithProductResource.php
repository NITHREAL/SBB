<?php

declare(strict_types=1);

namespace Domain\ProductGroup\Resources;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductGroupWithProductResource extends JsonResource
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
            'products'          => $this->groupProducts
                ? CatalogProductResource::collection($this->groupProducts)
                : [],
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->addMeta([
            'includes' => [
                'products' => CatalogProductResource::class,
            ],
        ]);
    }
}
