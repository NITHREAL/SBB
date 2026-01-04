<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Levels\Levels;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetLevelsResponse implements ManzanaResponseInterface
{
    public function __construct(
        private array $levels,
    ) {
    }

    public static function make(array $data): self
    {
        $levels = self::prepareLevels(Arr::get($data, 'value'));

        return new self(
            $levels,
        );
    }

    public function getLevels(): array
    {
        return $this->levels;
    }

    private static function prepareLevels(array $levelsData): array
    {
        $levels = [];

        $levelsData = array_values($levelsData);

        for ($k = 0; $k < count($levelsData); $k++) {
            $levelData = Arr::get($levelsData, $k);
            $nextLevelData = Arr::get($levelsData, $k + 1);

            $levelData['MaxValue'] = Arr::get($nextLevelData, 'AccumulationForChange');

            $levels[] = Level::make($levelData);
        }

        return $levels;
    }
}
