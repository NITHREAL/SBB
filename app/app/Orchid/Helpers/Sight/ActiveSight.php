<?php

namespace App\Orchid\Helpers\Sight;

use Illuminate\Support\Str;

class ActiveSight extends BaseCustomSight
{
    public static function make(string $name = 'active', string $title = null): static
    {
        $title = $title ?? __('admin.active');

        return self::makeInstance($name, $title)->bool();
    }
}
