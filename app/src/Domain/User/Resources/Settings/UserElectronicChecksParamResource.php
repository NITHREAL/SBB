<?php

namespace Domain\User\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserElectronicChecksParamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'electronicChecks'  => (bool) Arr::get($this->resource, 'electronicChecks'),
        ];
    }
}
