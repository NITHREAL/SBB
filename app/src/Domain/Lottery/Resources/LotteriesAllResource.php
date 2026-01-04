<?php

namespace Domain\Lottery\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Http\Resources\PaginationResource;

class LotteriesAllResource extends JsonResource
{
    public function toArray($request): array
    {
        $lotteries = $this->resource;

        return [
            'lotteries'     => LotteryResource::collection($lotteries),
            'pagination'    => PaginationResource::make($lotteries),
        ];
    }
}
