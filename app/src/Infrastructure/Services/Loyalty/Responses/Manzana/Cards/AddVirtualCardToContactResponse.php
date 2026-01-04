<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Cards;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class AddVirtualCardToContactResponse implements ManzanaResponseInterface
{
    public function __construct(
        private ?string $value,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'value'),
        );
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
