<?php

namespace Domain\User\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserAutoBrightnessParamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'autoBrightness'  => (bool) Arr::get($this->resource, 'autoBrightness'),
        ];
    }
}
