<?php

namespace Domain\User\Resources\Bonuses;

use Carbon\Carbon;
use Domain\CouponCategory\Resources\CouponCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;

class BonusAccountHistoryListResource extends JsonResource
{
    public function toArray($request): array
    {
        $bonusHistory = $this->resource;

        return [
            'bonusHistory'  => BonusAccountHistoryResource::collection($bonusHistory),
            'pagination'    => PaginationResource::make($bonusHistory),
        ];
    }
}
