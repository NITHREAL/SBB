<?php

namespace Domain\Story\DTO;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class StoryMetadataDTO extends BaseDTO
{
    public function __construct(
        private readonly string $viewDate,
        private readonly string $viewDuration,
        private readonly bool   $wasClicked,
        private readonly bool   $movedToNext,
        private readonly int    $storyId,
        private readonly ?User   $user,
    ) {
    }

    public static function make(array $data, int $storyId, ?User $user): self
    {
        return new self(
            Arr::get($data, 'viewDate'),
            Arr::get($data, 'viewDuration'),
            Arr::get($data, 'wasClicked', false),
            Arr::get($data, 'movedToNext', false),
            $storyId,
            $user,
        );
    }

    public function getViewDate(): string
    {
        return $this->viewDate;
    }

    public function getViewDuration(): string
    {
        return $this->viewDuration;
    }

    public function getWasClicked(): bool
    {
        return $this->wasClicked;
    }

    public function getMovedToNext(): bool
    {
        return $this->movedToNext;
    }

    public function getStoryId(): int
    {
        return $this->storyId;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
