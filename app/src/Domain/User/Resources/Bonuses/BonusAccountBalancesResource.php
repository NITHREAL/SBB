<?php

namespace Domain\User\Resources\Bonuses;

use Domain\BonusLevel\Resources\BonusLevelResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BonusAccountBalancesResource extends JsonResource
{
    public function toArray($request): array
    {
        $bonusLevel = Arr::get($this->resource, 'bonusLevel');

        return [
            'bonusAccountId'        => Arr::get($this->resource, 'bonusAccountId'),
            'bonusAccountQrCode'    => Arr::get($this->resource, 'bonusAccountQrCode'),
            'bonusInfo'             => Arr::get($this->resource, 'bonusInfo'),
            'currentBonuses'        => Arr::get($this->resource, 'currentBonuses'),
            'bonusLevel'            => BonusLevelResource::make($bonusLevel),
        ];
    }
}
