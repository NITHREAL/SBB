<?php

namespace Domain\Faq\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqQuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->resource->title,
            'slug'  => $this->resource->slug,
            'text'  => $this->resource->text,
        ];
    }
}
