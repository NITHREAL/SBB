<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetForUser;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetUserFavoriteCategoriesResponse implements ManzanaResponseInterface
{
    public function __construct(
        private array $categories,
    ) {
    }

    public static function make(array $data): self
    {
        $preparedCategories = self::getPreparedCategories(Arr::get($data, 'value', []));

        return new self(
            $preparedCategories,
        );
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    private static function getPreparedCategories(array $categories): array
    {
        $preparedCategories = [];

        foreach ($categories as $category) {
            $preparedCategories[] = ChosedCategory::make($category);
        }

        return $preparedCategories;
    }
}
