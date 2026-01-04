<?php

namespace Domain\Faq\Services\Faq;

use Domain\Faq\DTO\FaqDTO;
use Domain\Faq\Models\Faq;
use Domain\Faq\Models\FaqCategory;

class FaqModifyService
{
    public function createFaq(FaqDTO $faqDTO): object
    {
        $faqCategory = FaqCategory::findOrFail($faqDTO->getFaqCategoryId());

        $faq = $this->getFilledFaq($faqDTO);

        $faq->category()->associate($faqCategory);

        $faq->save();

        return $faq;
    }

    public function updateFaq(int $faqId, FaqDTO $faqDTO): object
    {
        $faq = Faq::findOrFail($faqId);

        $faq = $this->getFilledFaq($faqDTO, $faq);

        $faq->save();

        return $faq;
    }

    public function deleteFaq(int $faqId): bool
    {
        return Faq::destroy($faqId);
    }

    private function getFilledFaq(FaqDTO $faqDTO, Faq $faq = null): object
    {
        $faq = $faq ?? new Faq();

        return $faq->fill([
            'title'     => $faqDTO->getTitle(),
            'slug'      => $faqDTO->getSlug(),
            'text'      => $faqDTO->getText(),
            'sort'      => $faqDTO->getSort(),
            'active'    => $faqDTO->isActive(),
        ]);
    }
}
