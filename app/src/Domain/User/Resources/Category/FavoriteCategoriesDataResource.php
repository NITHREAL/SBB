<?php

namespace Domain\User\Resources\Category;

use Domain\Faq\Resource\FaqQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class FavoriteCategoriesDataResource extends JsonResource
{
    public function toArray($request): array
    {
        $faq = Arr::get($this->resource, 'faq', []);

        $categoriesData = Arr::map(
            Arr::get($this->resource, 'categoriesData', []),
            function ($item) {
                return [
                    'period'        => Arr::get($item, 'period'),
                    'categories'    => FavoriteCategoryResource::collection(Arr::get($item, 'categories', collect())),
                ];
            }
        );

        return [
            'availablePeriod'   => Arr::get($this->resource, 'availablePeriod'),
            'categories'        => $categoriesData,
            'faq'               => FaqQuestionResource::collection($faq),
        ];
    }
}
