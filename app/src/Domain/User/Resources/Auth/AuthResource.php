<?php

namespace Domain\User\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AuthResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'accessToken'      => (string) Arr::get($this->resource, 'access_token'),
            'refreshToken'     => (string) Arr::get($this->resource, 'refresh_token'),
            'expiresIn'        => (int) Arr::get($this->resource, 'expires_in'),
            'isUserNew'        => (bool) Arr::get($this->resource, 'isUserNew'),
        ];
    }
}
