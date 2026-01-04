<?php

namespace Domain\User\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class BuyerTokenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token' => (string) $this->resource->token,
        ];
    }
}
