<?php

namespace Domain\User\Resources\Bonuses;

use Domain\BonusLevel\Resources\BonusLevelResource;
use Domain\Faq\Resource\FaqCategoryResource;
use Domain\Faq\Resource\FaqQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BonusAccountOverviewResource extends JsonResource
{
    public function toArray($request): array
    {
        $bonusLevel = Arr::get($this->resource, 'bonusLevel');
        $faq = Arr::get($this->resource, 'faq');

        return [
            'bonusAccountId'        => Arr::get($this->resource, 'bonusAccountId'),
            'currentBonuses'        => Arr::get($this->resource, 'currentBonuses'),
            'bonusLevel'            => BonusLevelResource::make($bonusLevel),
            'faq'                   => FaqQuestionResource::collection($faq),
        ];
    }
}
