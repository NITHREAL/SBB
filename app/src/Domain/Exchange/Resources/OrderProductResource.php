<?php

namespace Domain\Exchange\Resources;

use Domain\Product\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'system_id' => (string)$this->resource['system_id'],
            'unit_system_id' => (string)$this->pivot->unit_system_id,
            'price' => $this->pivot->price,
            'price_buy' => $this->pivot->price_buy,
            'count' => $this->calcCount($this->resource),
            'is_promotion' => (boolean)$this->pivot->is_discount,
            'total' => $this->pivot->total,
            'total_without_discount' => $this->pivot->total_without_discount
        ];
    }

    private function calcCount(Product $product): float
    {
        $count = $product->pivot->count;
        $weight = (float)$product->pivot->weight;

        if ($weight) {
            $count *= $weight;
        }

        return $count;
    }
}
