<?php

namespace Domain\Unit\Services\Exchange;

use Domain\Unit\DTO\Exchange\OneC\UnitDTO;
use Domain\Unit\Models\Unit;

readonly class UnitExchangeService
{
    public function exchangeUnit(UnitDTO $unitDTO): object
    {
        $unit = Unit::query()->where('system_id', $unitDTO->getSystemId())->first() ?? new Unit();

        $unit->fill([
            'system_id' => $unitDTO->getSystemId(),
            'title'     => $unitDTO->getTitle(),
        ]);

        $unit->save();

        return $unit;
    }
}
