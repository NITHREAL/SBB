<?php

namespace Infrastructure\DTO\Catalog;

interface CatalogParamsDTOInterface
{
    public function getLimit(): int;

    public function getSortBy(): array;

    public function getStoreSystemId(): string;
}
