<?php

namespace Domain\Faq\Services\FaqCategory;

use Domain\Faq\DTO\FaqCategoryDTO;
use Domain\Faq\Models\FaqCategory;

class FaqCategoryModifyService
{
    public function createFaqCategory(FaqCategoryDTO $faqCategoryDTO): object
    {
        $faqCategory = $this->getFilledFaqCategory($faqCategoryDTO);

        $faqCategory->save();

        return $faqCategory;
    }

    public function updateFaqCategory(int $faqCategoryId, FaqCategoryDTO $faqCategoryDTO): object
    {
        $faqCategory = FaqCategory::findOrFail($faqCategoryId);

        $faqCategory = $this->getFilledFaqCategory($faqCategoryDTO, $faqCategory);

        $faqCategory->save();

        return $faqCategory;
    }

    public function deleteFaqCategory(int $faqCategoryId): bool
    {
        return FaqCategory::destroy($faqCategoryId);
    }

    private function getFilledFaqCategory(FaqCategoryDTO $faqCategoryDTO, FaqCategory $faqCategory = null): object
    {
        $faqCategory = $faqCategory ?? new FaqCategory();

        return $faqCategory->fill([
            'title'     => $faqCategoryDTO->getTitle(),
            'slug'      => $faqCategoryDTO->getSlug(),
            'active'    => $faqCategoryDTO->isActive(),
            'sort'      => $faqCategoryDTO->getSort(),
        ]);
    }
}
