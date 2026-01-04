<?php

namespace App\Orchid\Filters\Region;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\City\Models\Region;

class RegionFilter extends RelationFilter
{
    public $parameters = [
        'regions'
    ];

    protected ?string $dbColumn = 'region_id';

    protected string $modelClassName = Region::class;

    protected string $modelColumnName = 'title';

    protected bool $multiple = true;

    public function name(): string
    {
        return __('admin.city.region');
    }
}
