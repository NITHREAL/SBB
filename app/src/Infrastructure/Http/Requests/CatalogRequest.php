<?php

namespace Infrastructure\Http\Requests;

use Domain\Product\Helpers\CatalogHelper;

abstract class CatalogRequest extends PaginatedRequest
{
    private array $availableSortDirValues = ['asc', 'desc'];

    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge($rules, [
            'sort'                      => 'array',
            'sort.column'               => [
                'required_with:sort',
                'string',
                'in:' . implode(',', CatalogHelper::SORT_COLUMN_VALUES),
            ],
            'sort.dir'                  => [
                'required_with:sort',
                'string',
                'in:' . implode(',', $this->availableSortDirValues)
            ],
            'filter'                    => 'array',
            'filter.available_today'    => 'boolean',
            'filter.for_vegan'          => 'boolean',
        ]);
    }
}
