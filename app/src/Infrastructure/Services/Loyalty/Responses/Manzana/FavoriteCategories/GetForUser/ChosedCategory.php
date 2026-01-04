<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetForUser;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class ChosedCategory implements ManzanaResponseInterface
{
    public function __construct(
        private string $loyaltyId,
        private string $externalId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'Id'),
            Arr::get($data, 'ExternalId'),
        );
    }

    public function getId(): string
    {
        return $this->loyaltyId;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
