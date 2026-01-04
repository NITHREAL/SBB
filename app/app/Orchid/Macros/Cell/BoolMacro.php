<?php

namespace App\Orchid\Macros\Cell;

use App\Orchid\Macros\MacroInterface;
use App\View\Components\BoolField;
use Orchid\Support\Blade;

class BoolMacro implements MacroInterface
{
    public static function macro(): \Closure
    {
        $closure = function () {
            $column = $this->column;

            $this->render(function ($datum) use ($column) {
                return Blade::renderComponent(BoolField::class, [
                    'bool' => $datum->$column
                ]);
            });

            return $this;
        };

        return $closure;
    }
}
