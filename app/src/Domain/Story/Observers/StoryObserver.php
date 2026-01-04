<?php

namespace Domain\Story\Observers;

use Domain\Story\Models\Story;
use Domain\Story\Services\StoryImageService;

class StoryObserver
{
    public function __construct(
        protected StoryImageService $storyImageService,
    ) {
    }

    public function saved(Story $story): void
    {
        $this->storyImageService->convertStoryImage($story);
    }
}
