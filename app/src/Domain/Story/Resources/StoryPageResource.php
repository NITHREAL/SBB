<?php

declare(strict_types=1);

namespace Domain\Story\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryPageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'position'      => $this->position,
            'label'         => $this->label,
            'text'          => $this->text,
            'type'          => $this->type,
            'image'         => $this->image_original,
            'imageBlurHash' => $this->image_blur_hash,
            'storyId'       => $this->story_id,
            'targetId'      => $this->target_id,
            'timer'         => $this->timer,
            'targetUrl'     => $this->target_url,
            'createdAt'     => $this->created_at,
        ];
    }
}
