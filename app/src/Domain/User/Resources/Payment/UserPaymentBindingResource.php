<?php

namespace Domain\User\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserPaymentBindingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => Arr::get($this->resource, 'id'),
            'description'   => Arr::get($this->resource, 'description'),
            'isDefault'     => (bool) Arr::get($this->resource, 'is_default'),
        ];
    }
}
