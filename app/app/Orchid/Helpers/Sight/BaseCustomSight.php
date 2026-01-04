<?php

namespace App\Orchid\Helpers\Sight;

use Orchid\Screen\Sight;

abstract class BaseCustomSight extends Sight
{
    protected static function makeInstance(string $name, string $title): static
    {
        $td = new static($name);
        $td->column = $name;
        $td->title = $title;

        return $td;
    }
}
