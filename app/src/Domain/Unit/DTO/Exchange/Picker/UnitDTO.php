<?php

namespace Domain\Unit\DTO\Exchange\Picker;

use Domain\Unit\Models\Unit;
use Infrastructure\DTO\BaseDTO;

class UnitDTO extends BaseDTO
{
    public function __construct(
        public readonly string $systemId,
        public readonly string $title,
    ) {
    }

    public static function fromModel(Unit $unit): self
    {
        return new self(
            $unit->system_id,
            $unit->title,
        );
    }
}
