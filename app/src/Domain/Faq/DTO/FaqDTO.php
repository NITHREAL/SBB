<?php

namespace Domain\Faq\DTO;

use Domain\Faq\Helpers\FaqHelper;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class FaqDTO extends BaseDTO
{
    public function __construct(
        private readonly string  $title,
        private readonly string  $text,
        private readonly ?string $slug,
        private readonly bool    $isActive,
        private readonly ?int    $sort,
        private readonly int     $faqCategoryId,
    ) {
    }

    public static function make(array $data, int $faqCategoryId, ?int $faqId): self
    {
        $title = Arr::get($data, 'title');
        $slug = Arr::get($data, 'slug') ?? FaqHelper::generateUniqueSlug($title, $faqId);

        return new self(
            Arr::get($data, 'title'),
            Arr::get($data, 'text'),
            $slug,
            Arr::get($data, 'active', false),
            Arr::get($data, 'sort', self::DEFAULT_SORT),
            $faqCategoryId,
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getSort(): int
    {
        return $this->sort ?? self::DEFAULT_SORT;
    }

    public function getFaqCategoryId(): int
    {
        return $this->faqCategoryId;
    }
}
