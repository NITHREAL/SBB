<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevels;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetContactLevelsResponse implements ManzanaResponseInterface
{
    public function __construct(
        private array $levels,
    ) {
    }

    public static function make(array $data): self
    {
        $preparedLevels = self::getPreparedLevels(Arr::get($data, 'value', []));

        return new self(
            $preparedLevels,
        );
    }

    public function getLevels(): array
    {
        return $this->levels;
    }

    public function getLevelInfo(): ?LevelInfo
    {
        return Arr::get($this->levels, 0);
    }

    private static function getPreparedLevels(array $levels): array
    {
        $data = [];;

        foreach ($levels as $level) {
            $data[] = LevelInfo::make($level);
        }

        return $data;
    }
}
