<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevelInfo;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetContactLevelsInfoResponse implements ManzanaResponseInterface
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

    public function getContactLevelInfo(): ?ContactLevelInfo
    {
        return Arr::first($this->levels);
    }


    private static function getPreparedLevels(array $levels): array
    {
        $data = [];

        foreach ($levels as $level) {
            $data[] = ContactLevelInfo::make($level);
        }

        return $data;
    }
}
