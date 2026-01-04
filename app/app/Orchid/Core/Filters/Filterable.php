<?php

namespace App\Orchid\Core\Filters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    use \Orchid\Filters\Filterable;

    /**
     * @param Builder         $builder
     * @param HttpFilter|null $httpFilter
     *
     * @return Builder
     */
    public function scopeFilters(Builder $builder, HttpFilter $httpFilter = null): Builder
    {
        $filter = $httpFilter ?? new HttpFilter();
        $filter->build($builder);

        return $builder;
    }
}
