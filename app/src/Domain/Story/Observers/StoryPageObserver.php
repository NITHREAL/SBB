<?php

namespace Domain\Story\Observers;

use Domain\Story\Models\StoryPage;
use Domain\Story\Services\StoryImageService;

class StoryPageObserver
{
    public function __construct(
        protected StoryImageService $storyImageService,
    ) {
    }

    public function saved(StoryPage $storyPage): void
    {
        $this->storyImageService->convertStoryPageImage($storyPage);
    }
}
