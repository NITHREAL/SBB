<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class GetFavoriteCategoriesDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'sessionId'),
        );
    }

    public function sessionId(): string
    {
        return $this->sessionId;
    }
}
