<?php

namespace Domain\Faq\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self whereCategory(int $categoryId)
 * @method static self whereCategories(array $categoryIds)
 * @method static self whereActive()
 */
class FaqQueryBuilder extends BaseQueryBuilder
{
    public function whereCategory(int $categoryId): self
    {
        return $this->where('faq.faq_category_id', $categoryId);
    }

    public function whereCategories(array $categoryIds): self
    {
        return $this->whereIn('faq.faq_category_id', $categoryIds);
    }

    public function whereActive(): self
    {
        return $this->where('faq.active', true);
    }
}
