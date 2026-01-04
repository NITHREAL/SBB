<?php

namespace Domain\Store\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreContactResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'type' => $this->type,
            'value' => $this->value
        ];
    }
}
