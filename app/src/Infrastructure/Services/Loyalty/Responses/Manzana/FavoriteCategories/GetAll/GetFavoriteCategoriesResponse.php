<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetAll;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetFavoriteCategoriesResponse implements ManzanaResponseInterface
{
    public function __construct(
        private array $categories
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
            // Если пришел идентификатор детальной информации о категории
            if (Arr::get($category, 'ProductListGroupId')) {
                $preparedCategories[] = FavoriteCategory::make($category);
            }
        }

        return $preparedCategories;
    }
}
