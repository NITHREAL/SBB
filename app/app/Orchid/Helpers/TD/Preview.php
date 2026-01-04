<?php

namespace App\Orchid\Helpers\TD;

use Illuminate\Support\Str;

class Preview extends BaseCustomTD
{
    public static function make(string $name = 'image', string $title = null): static
    {
        $title = $title ?? __('admin.image') ?? Str::title($name);

        return self::makeInstance($name, $title)
            ->width(100)
            ->preview($name);
    }
}
