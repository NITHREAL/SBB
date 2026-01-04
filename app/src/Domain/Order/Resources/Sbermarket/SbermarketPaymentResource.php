<?php

namespace Domain\Order\Resources\Sbermarket;

use Illuminate\Http\Resources\Json\JsonResource;

class SbermarketPaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'payed'         => (bool)$this->resource->payed,
            'amount'        => (float)$this->resource->amount,
            'error_code'    => $this->when((bool)$this->resource->error_code, (int)$this->resource->error_code),
            'error_message' => $this->when((bool)$this->resource->error_code, $this->resource->error_message)
        ];
    }
}
