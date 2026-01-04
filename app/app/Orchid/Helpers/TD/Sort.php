<?php

namespace App\Orchid\Helpers\TD;

use Illuminate\Support\Str;

class Sort extends BaseCustomTD
{
    public static function make(string $name = 'sort', string $title = null): static
    {
        $title = $title ?? __('admin.sort') ?? Str::title($name);

        return self::makeInstance($name, $title)
            ->alignCenter()
            ->sort()
            ->width(75);
    }
}
