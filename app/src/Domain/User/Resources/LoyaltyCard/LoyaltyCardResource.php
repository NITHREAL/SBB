<?php

namespace Domain\User\Resources\LoyaltyCard;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class LoyaltyCardResource extends JsonResource
{
    public function toArray($request): array
    {
        $cardData = $this->resource;

        return [
            'cardNumber'    => Arr::get($cardData, 'cardNumber'),
            'cardType'      => Arr::get($cardData, 'cardType'),
        ];
    }
}
