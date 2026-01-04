<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class SetUserFavoriteCategoryResponse implements ManzanaResponseInterface
{
    public function __construct(
        private array $value,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'value', []),
        );
    }

    public function getValue(): array
    {
        return $this->value;
    }
}
