<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevels;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class LevelInfo implements ManzanaResponseInterface
{
    public function __construct(
        private string  $id,
        private string  $name,
        private ?string  $description,
        private string  $ExternalId,
        private ?string $settingLevelId,
        private ?string $nextLevelId,
        private ?string $nextLevelName,
        private ?string $previousLevelId,
        private ?string $previousLevelName,
        private float   $valueForChange,
        private float   $valueForKeep,
        private ?string $levelInfo1,
        private ?string $levelInfo2,
        private ?string $levelInfo3,
        private ?string $levelInfo4,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'Id'),
            Arr::get($data, 'Name'),
            Arr::get($data, 'Description'),
            Arr::get($data, 'ExternalId'),
            Arr::get($data, 'SettingLevelId'),
            Arr::get($data, 'NextLevelId'),
            Arr::get($data, 'NextLevelName'),
            Arr::get($data, 'PreviousLevelId'),
            Arr::get($data, 'PreviousLevelName'),
            Arr::get($data, 'AccumulationForChange'),
            Arr::get($data, 'AccumulationForKeep'),
            Arr::get($data, 'LevelInfo1'),
            Arr::get($data, 'LevelInfo2'),
            Arr::get($data, 'LevelInfo3'),
            Arr::get($data, 'LevelInfo4'),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExternalId(): string
    {
        return $this->ExternalId;
    }

    public function getSettingLevelId(): ?string
    {
        return $this->settingLevelId;
    }

    public function getNextLevelId(): ?string
    {
        return $this->nextLevelId;
    }

    public function getNextLevelName(): ?string
    {
        return $this->nextLevelName;
    }

    public function getPreviousLevelId(): ?string
    {
        return $this->previousLevelId;
    }

    public function getPreviousLevelName(): ?string
    {
        return $this->previousLevelName;
    }

    public function getValueForChange(): float
    {
        return $this->valueForChange;
    }

    public function getValueForKeep(): float
    {
        return $this->valueForKeep;
    }

    public function getLevelInfo1(): string
    {
        return $this->levelInfo1;
    }

    public function getLevelInfo2(): string
    {
        return $this->levelInfo2;
    }

    public function getLevelInfo3(): string
    {
        return $this->levelInfo3;
    }

    public function getLevelInfo4(): string
    {
        return $this->levelInfo4;
    }
}
