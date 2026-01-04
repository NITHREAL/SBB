<?php

namespace Domain\User\Resources\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class EntranceVariantResource extends JsonResource
{
    public function toArray($request): array
    {
        $variant = $this->resource;

        return [
            'label' => $variant->get('label'),
            'value' => $variant->get('value'),
        ];
    }
}
