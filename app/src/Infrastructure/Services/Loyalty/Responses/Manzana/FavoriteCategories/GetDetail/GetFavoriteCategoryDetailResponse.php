<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetDetail;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetFavoriteCategoryDetailResponse implements ManzanaResponseInterface
{
    public function __construct(
        private string  $name,
        private ?string $description,
        private ?string  $image,
    ) {
    }

    public static function make(array $data): self
    {
        $detailData = Arr::first(Arr::get($data, 'value', []));

        return new self(
            Arr::get($detailData, 'Name'),
            Arr::get($detailData, 'Description'),
            Arr::get($detailData, 'IconUrl'),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
