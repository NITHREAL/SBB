<?php

declare(strict_types=1);

namespace Domain\Story\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'image'             => $this->image_original,
            'imageBlurHash'     => $this->image_blur_hash,
            'sort'              => $this->sort,
            'active'            => $this->active,
            'autoOpen'          => $this->auto_open,
            'createdAt'         => $this->created_at,
            'isWatched'         => $this->isWatched,
            'pages'             => $this->pages
                ? StoryPageResource::collection(($this->pages))
                : [],
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->addMeta([
            'includes' => [
                'pages' => StoryPageResource::class,
            ],
        ]);
    }
}
