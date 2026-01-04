<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Cards;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetContactCardsResponse implements ManzanaResponseInterface
{
    public function __construct(
        private array $cards,
    ) {
    }

    public static function make(array $data): self
    {
        $value = Arr::get($data, 'value') ?? [];

        return new self(
            self::prepareCards($value),
        );
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    private static function prepareCards(array $cards): array
    {
        $data = [];

        foreach ($cards as $card) {
            $data[] = ContactCard::make($card);
        }

        return $data;
    }
}
