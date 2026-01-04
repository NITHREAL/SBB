<?php

namespace App\Orchid\Helpers\TD;

use Illuminate\Support\Str;

class ID extends BaseCustomTD
{
    public static function make(string $name = 'id', string $title = null): static
    {
        $title = $title ?? __('admin.id') ?? Str::title($name);

        return self::makeInstance($name, $title)
            ->alignCenter()
            ->sort()
            ->width(80);
    }
}
