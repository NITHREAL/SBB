<?php

namespace Domain\Story\DTO;

use Illuminate\Support\Collection;
use Infrastructure\DTO\BaseDTO;

class PreparedStoryDTO extends BaseDTO
{
    public function __construct(
        private readonly Collection     $storyPages,
        private readonly Collection     $images,
        private readonly Collection     $metaData,
        private readonly Collection     $products,
        private readonly ?int           $userId,
    ) {
    }

    public static function make(
        Collection $storyPages,
        Collection $images,
        Collection $metaData,
        Collection $products,
        ?int $userId,
    ): self {
        return new self(
            $storyPages,
            $images,
            $metaData,
            $products,
            $userId,
        );
    }

    public function getStoryPages(): Collection
    {
        return $this->storyPages;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getMetaData(): Collection
    {
        return $this->metaData;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }
}
