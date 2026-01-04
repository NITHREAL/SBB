<?php

namespace Domain\BonusLevel\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BonusLevelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title'                 => Arr::get($this->resource, 'title'),
            'description'           => Arr::get($this->resource, 'description'),
            'minBonusPoints'        => Arr::get($this->resource, 'minBonusPoints'),
            'maxBonusPoints'        => Arr::get($this->resource, 'maxBonusPoints'),
            'currentBonusPoints'    => Arr::get($this->resource, 'levelBonusPoints'),
        ];
    }
}
