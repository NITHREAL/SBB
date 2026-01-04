<?php

namespace App\Orchid\Helpers\Sight;

class IdSight extends BaseCustomSight
{
    public static function make(string $name = 'id', string $title = null): static
    {
        $title = $title ?? __('admin.id');

        return self::makeInstance($name, $title);
    }
}
