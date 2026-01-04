<?php

namespace Domain\Order\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OnlinePaymentBindingInitResource extends JsonResource
{
    public function toArray($request): array
    {
        $acquiringData = $this->resource;

        return [
            'url'   => Arr::get($acquiringData, 'formUrl'),
        ];
    }
}
