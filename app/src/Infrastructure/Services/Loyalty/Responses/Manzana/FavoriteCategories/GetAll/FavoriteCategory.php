<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetAll;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class FavoriteCategory implements ManzanaResponseInterface
{
    public function __construct(
        private string $loyaltyId,
        private string $name,
        private string $externalId,
        private string $productListGroupId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'Id'),
            Arr::get($data, 'Name'),
            Arr::get($data, 'ExternalId'),
            Arr::get($data, 'ProductListGroupId'),
        );
    }

    public function getId(): string
    {
        return $this->loyaltyId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getPeriod(): string
    {
        $externalIdData = explode('/', $this->externalId);
        $month = Arr::get($externalIdData, 1);
        $year = Str::substr(Arr::get($externalIdData, 2), -2);

        return sprintf('%s-%s', $month, $year);
    }

    public function getProductListGroupId(): string
    {
        return $this->productListGroupId;
    }
}
