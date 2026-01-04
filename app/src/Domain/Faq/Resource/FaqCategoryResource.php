<?php

namespace Domain\Faq\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class FaqCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $questionsData = Arr::get($this->resource, 'questionsData');

        return [
            'title'     => $this->resource->title,
            'slug'      => $this->resource->slug,
            'questions' => $questionsData
                ? FaqQuestionResource::collection($questionsData)
                : [],
        ];
    }
}
