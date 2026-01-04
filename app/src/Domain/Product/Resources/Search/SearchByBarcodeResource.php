<?php

namespace Domain\Product\Resources\Search;

use Domain\Product\Resources\Catalog\ProductDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class SearchByBarcodeResource extends JsonResource
{
    public function toArray($request): array
    {
        $product = Arr::get($this->resource, 'product');

        return [
            'product'      => ProductDetailResource::make($product),
            'barcode'      => Arr::get($this->resource, 'barcode'),
        ];
    }
}
