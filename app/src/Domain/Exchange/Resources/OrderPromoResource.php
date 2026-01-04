<?php

namespace Domain\Exchange\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderPromoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code' => (string)$this->code,
            'discount' => (float)$this->discount,
            'percentage' => (bool)$this->percentage,
            'min_amount' => $this->min_amount
        ];
    }
}
