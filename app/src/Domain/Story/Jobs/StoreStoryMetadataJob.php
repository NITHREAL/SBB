<?php

namespace Domain\Story\Jobs;

use Domain\Story\DTO\StoryMetadataDTO;
use Domain\Story\Exceptions\StoryException;
use Domain\Story\Services\StoryMetadataService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreStoryMetadataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected StoryMetadataDTO $storyMetadataDTO,
    ) {
    }

    /**
     * @throws StoryException
     */
    public function handle(StoryMetadataService $storyMetadataService): void
    {
        $storyMetadataService->storeMetadata($this->storyMetadataDTO);
    }
}
