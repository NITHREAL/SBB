<?php

namespace Domain\Image\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class ImageMinDTO extends BaseDTO
{
    public function __construct(
        private readonly string $url,
        private readonly string $newUrl,
        private readonly int $width,
        private readonly int $height,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'url'),
            Arr::get($data, 'newUrl'),
            Arr::get($data, 'width'),
            Arr::get($data, 'height'),
        );
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getNewUrl(): string
    {
        return $this->newUrl;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
