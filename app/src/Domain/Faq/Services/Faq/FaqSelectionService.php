<?php

namespace Domain\Faq\Services\Faq;

use Domain\Faq\Helpers\FaqCategoryHelper;
use Domain\Faq\Models\Faq;
use Domain\Faq\Models\FaqCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FaqSelectionService
{
    private int $faqCacheTtl = 10800;
    private string $faqCacheKey = 'faq';

    public function getFaqData(): array
    {
        return Cache::remember(
            $this->faqCacheKey,
            $this->faqCacheTtl,
            fn() => $this->getFaq(),
        );
    }

    public function getFaqBySlug(string $slug): Collection
    {
        $faq = collect();

        $faqCategory = FaqCategory::query()
            ->whereSlug($slug)
            ->whereActive()
            ->first();

        if ($faqCategory) {
            $faq = Faq::query()
                ->whereActive()
                ->whereCategory($faqCategory->id)
                ->orderBy('sort')
                ->get();
        }

        return $faq;
    }

    private function getFaq(): array
    {
        $faqCategories = FaqCategory::query()
            ->whereActive()
            ->orderBy('sort')
            ->get();

        $faq = Faq::query()
            ->whereActive()
            ->whereCategories($faqCategories->pluck('id')->toArray())
            ->orderBy('sort')
            ->get();

        return [
            'categories'    => $this->getPreparedFaqCategories($faqCategories, $faq),
            'mainQuestions' => $this->getMainQuestions($faqCategories),
        ];
    }

    private function getPreparedFaqCategories(Collection $faqCategories, Collection $faq): Collection
    {
        return $faqCategories
            ->map(function ($category) use ($faq) {
                $category->questionsData = $faq->where('faq_category_id', $category->id);

                return $category;
            })
            ->filter(fn($item) => $item->questionsData->isNotEmpty());
    }

    private function getMainQuestions(Collection $faqCategories): Collection
    {
        $mainQuestions = collect();

        foreach ($faqCategories as $faqCategory) {
            $mainQuestions->push($faqCategory->questionsData->first());
        }

        return $mainQuestions;
    }
}
