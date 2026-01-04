<?php

namespace Domain\Story\Services;

use Domain\Story\DTO\StoryMetadataDTO;
use Domain\Story\Exceptions\StoryException;
use Domain\Story\Models\Story;
use Domain\Story\Models\StoryMetadata;

class StoryMetadataService
{
    /**
     * @throws StoryException
     */
    public function storeMetadata(StoryMetadataDTO $dto): void
    {
        $story = Story::query()->whereId($dto->getStoryId())->first();
        $user = $dto->getUser();

        if (empty($story)) {
            throw new StoryException("История с ID [{$dto->getStoryId()}] не найдена");
        }

        $metadata = new StoryMetadata();
        $metadata = $this->getFilledStoryMetadata($metadata, $dto);

        $metadata->story()->associate($story);

        if ($user) {
            $metadata->user()->associate($user);
        }

        $metadata->save();
    }

    private function getFilledStoryMetadata(StoryMetadata $storyMetadata, StoryMetadataDTO $dto): StoryMetadata
    {
        $user = $dto->getUser();

        return $storyMetadata->fill([
            'phone'         => $user->phone,
            'user_name'     => sprintf('%s %s', $user->first_name, $user->last_name),
            'view_date'     => $dto->getViewDate(),
            'view_duration' => $dto->getViewDuration(),
            'was_clicked'   => $dto->getWasClicked(),
            'moved_to_next' => $dto->getMovedToNext(),
        ]);
    }
}
