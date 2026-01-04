<?php

declare(strict_types=1);

namespace Domain\Exchange\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;
use Spatie\DataTransferObject\Attributes\MapFrom;

class LeftoverDTO extends BaseDTO
{
    public function __construct(
        #[MapFrom('system_id')]
        public string $systemId,
        #[MapFrom('products')]
        public array $products
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'products', [])
        );
    }
}
