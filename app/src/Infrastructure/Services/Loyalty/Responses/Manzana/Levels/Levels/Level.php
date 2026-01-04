<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Levels\Levels;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class Level implements ManzanaResponseInterface
{
    public function __construct(
        private string  $id,
        private ?string  $name,
        private ?string  $description,
        private ?string  $externalId,
        private string  $minValue,
        private ?string $maxValue,
        private ?string $levelInfo1,
        private ?string $levelInfo2,
        private ?string $levelInfo3,
        private ?string $levelInfo4,
    ) {
    }

    public static function make(array $data): self
    {
        $minValue = (int) Arr::get($data, 'AccumulationForChange');
        $maxValue = Arr::get($data, 'MaxValue') ? (int) Arr::get($data, 'MaxValue') : null;

        return new self(
            Arr::get($data, 'Id'),
            Arr::get($data, 'Name'),
            Arr::get($data, 'Description'),
            Arr::get($data, 'ExternalId'),
            $minValue,
            $maxValue,
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function getMaxValue(): ?int
    {
        return $this->maxValue;
    }

    public function getLevelInfo1(): ?string
    {
        return $this->levelInfo1;
    }

    public function getLevelInfo2(): ?string
    {
        return $this->levelInfo2;
    }

    public function getLevelInfo3(): ?string
    {
        return $this->levelInfo3;
    }

    public function getLevelInfo4(): ?string
    {
        return $this->levelInfo4;
    }
}
