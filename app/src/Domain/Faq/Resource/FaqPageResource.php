<?php

namespace Domain\Faq\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class FaqPageResource extends JsonResource
{
    public function toArray($request): array
    {
        $categoriesData = Arr::get($this->resource, 'categories');
        $mainQuestionsData = Arr::get($this->resource, 'mainQuestions');

        return [
            'categories'    => $categoriesData
                ? FaqCategoryResource::collection($categoriesData)
                : [],
            'mainQuestions' => $mainQuestionsData
                ? FaqQuestionResource::collection($mainQuestionsData)
                : [],
        ];
    }
}
