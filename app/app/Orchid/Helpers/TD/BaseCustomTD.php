<?php

namespace App\Orchid\Helpers\TD;

use Orchid\Screen\TD;

abstract class BaseCustomTD extends TD
{
    protected static function makeInstance(string $name, string $title): static
    {
        $td = new static($name);
        $td->column = $name;
        $td->title = $title;

        return $td;
    }
}
