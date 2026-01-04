<?php

namespace App\Orchid\Macros\Cell;

use App\Orchid\Macros\MacroInterface;
use App\View\Components\Preview;
use Orchid\Support\Blade;

class PreviewMacro implements MacroInterface
{
    public static function macro(): \Closure
    {
        $closure = function (string $column = 'image') {
            $this->render(function ($datum) use ($column) {
                return Blade::renderComponent(Preview::class, [
                    'image' => $datum->$column
                ]);
            });

            return $this;
        };

        return $closure;
    }
}
