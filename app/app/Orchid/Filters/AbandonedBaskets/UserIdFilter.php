<?php

namespace App\Orchid\Filters\AbandonedBaskets;

use App\Orchid\Filters\Basic\NumberFilter;

class UserIdFilter extends NumberFilter
{
    /**
     * @var array
     */
    public $parameters = [
        'user_id'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('admin.id');
    }
}
