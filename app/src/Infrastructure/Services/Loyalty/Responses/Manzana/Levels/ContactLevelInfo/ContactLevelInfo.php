<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevelInfo;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class ContactLevelInfo implements ManzanaResponseInterface
{
    public function __construct(
        private string  $levelId,
        private string  $levelName,
        private string  $levelDescription,
        private string  $externalId,
        private float   $valueForChange,
        private float   $valueForKeep,
        private float   $value,
        private ?string $nextLevelName,
        private ?string $levelInfo1,
        private ?string $levelInfo2,
        private ?string $levelInfo3,
        private ?string $levelInfo4,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'LevelId'),
            Arr::get($data, 'LevelName'),
            Arr::get($data, 'Description'),
            Arr::get($data, 'ExternalId'),
            Arr::get($data, 'AccumulationForChange'),
            Arr::get($data, 'AccumulationForKeep'),
            Arr::get($data, 'Value'),
            Arr::get($data, 'NextLevelName'),
            Arr::get($data, 'LevelInfo1'),
            Arr::get($data, 'LevelInfo2'),
            Arr::get($data, 'LevelInfo3'),
            Arr::get($data, 'LevelInfo4'),
        );
    }

    public function getLevelId(): string
    {
        return $this->levelId;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function getLevelDescription(): string
    {
        return $this->levelDescription;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getValueForChange(): float
    {
        return $this->valueForChange;
    }

    public function getValueForKeep(): float
    {
        return $this->valueForKeep;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getNextLevelName(): string
    {
        return $this->nextLevelName;
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
