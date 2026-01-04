<?php

namespace Domain\User\Resources\Bonuses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BonusAccountHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $bonusesHistoryItem = $this->resource;

        return [
            'description'   => Arr::get($bonusesHistoryItem, 'title'),
            'points'        => Arr::get($bonusesHistoryItem, 'bonusesCount'),
        ];
    }
}
