<?php

namespace App\Orchid\Filters\UtmLabels;

use App\Orchid\Filters\Basic\SelectFilter;
use Domain\UtmLabel\Enums\UtmLabelEnum;

class UtmTypeFilter extends SelectFilter
{
    protected bool $multiple = true;

    public $parameters = [
        'type'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->options = UtmLabelEnum::toArrayWithValues();
    }

    public function name(): string
    {
        return __('admin.utm.type');
    }
}
