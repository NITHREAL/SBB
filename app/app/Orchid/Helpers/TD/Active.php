<?php

namespace App\Orchid\Helpers\TD;

use Illuminate\Support\Str;

class Active extends BaseCustomTD
{
    public static function make(string $name = 'active', string $title = null): static
    {
        $title = $title ?? __('admin.active') ?? Str::title($name);

        return self::makeInstance($name, $title)
            ->sort()
            ->alignCenter()
            ->width(75)
            ->bool();
    }
}
