<?php

namespace App\Orchid\Helpers\Sight;

class Id1CSight extends BaseCustomSight
{
    public static function make(string $name = 'active', string $title = null): static
    {
        $title = $title ?? __('admin.system_id');

        return self::makeInstance($name, $title);
    }
}
