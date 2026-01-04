<?php

namespace Domain\Support\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class SupportMessageDTO extends BaseDTO
{
    public function __construct(
        private readonly string $text,
        private readonly bool $stuffOnly,
        private readonly string $author,
        private readonly int $userId,
    ) {
    }

    public static function make(array $data, string $author, int $userId): self
    {
        return new self(
            Arr::get($data, 'text'),
            Arr::get($data, 'stuffOnly', false),
            $author,
            $userId,
        );
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isStuffOnly(): bool
    {
        return $this->stuffOnly;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
