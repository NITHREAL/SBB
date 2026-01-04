<?php

namespace Domain\Order\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'orderId'  => $this->order_id,
            'rating'    => $this->rate,
            'text'      => $this->text,
        ];
    }
}
