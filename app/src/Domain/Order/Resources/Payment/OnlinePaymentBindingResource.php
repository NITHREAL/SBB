<?php

namespace Domain\Order\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class OnlinePaymentBindingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'description'   => $this->card_description,
            'expiryDate'   => $this->expiry_date,
        ];
    }
}
