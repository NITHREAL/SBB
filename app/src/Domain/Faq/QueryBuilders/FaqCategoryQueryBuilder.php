<?php

namespace Domain\Faq\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self whereActive()
 * @method static self whereSlug(string $slug)
 */
class FaqCategoryQueryBuilder extends BaseQueryBuilder
{
    public function whereActive(): self
    {
        return $this->where('faq_categories.active', true);
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('faq_categories.slug', $slug);
    }
}
