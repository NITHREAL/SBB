<?php

namespace Domain\Lottery\DTO\Catalog;

use Domain\Lottery\Models\Lottery;
use Domain\Lottery\Services\LotterySelection;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseCatalogParamsDTO;

class LotteryCatalogParamsDTO extends BaseCatalogParamsDTO
{
    private Lottery $lottery;

    public function __construct(
        ?int $limit,
        array $sortBy,
        Lottery $lottery,
    ) {
        parent::__construct($limit, $sortBy);

        $this->lottery = $lottery;
    }

    public static function make(array $data, string $slug): self
    {
        $lottery = LotterySelection::getLotteryBySlug($slug);

        return new self(
            Arr::get($data, 'limit'),
            Arr::get($data, 'sort', []),
            $lottery,
        );
    }

    public function getLottery(): object
    {
        return $this->lottery;
    }
}
