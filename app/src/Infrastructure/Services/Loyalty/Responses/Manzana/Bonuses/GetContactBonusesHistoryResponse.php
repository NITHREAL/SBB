<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Bonuses;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetContactBonusesHistoryResponse implements ManzanaResponseInterface
{
    public function __construct(
        private Collection $bonusesHistory,
    ) {
    }

    public static function make(array $data): self
    {
        $value = Arr::get($data, 'value') ?? [];

        $bonusesHistoryRecords = self::getPreparedBonusesHistory($value);

        return new self(
            $bonusesHistoryRecords,
        );
    }

    public function getBonusesHistory(): Collection
    {
        return $this->bonusesHistory;
    }

    private static function getPreparedBonusesHistory(array $bonusesHistoryRecords): Collection
    {
        $result = collect();

        foreach ($bonusesHistoryRecords as $bonusesHistoryRecord) {
            $bonusesHistory = ContactBonusesHistoryRecord::make($bonusesHistoryRecord);

            $result->push($bonusesHistory);
        }

        return $result;
    }
}
