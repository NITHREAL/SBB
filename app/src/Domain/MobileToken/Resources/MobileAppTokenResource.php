<?php

namespace Domain\MobileToken\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MobileAppTokenResource extends JsonResource
{
    public function toArray($request): array
    {
        $token = $this->resource;

        return [
            'id'        => $token->id,
            'token'     => $token->token,
            'service'   => $token->service,
            'device'    => $token->device,
            'createdAt' => $token->created_at,
            'updatedAt' => $token->updated_at,
        ];
    }
}
