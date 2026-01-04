<?php

namespace App\Orchid\Macros\Cell;

use App\Orchid\Macros\MacroInterface;
use Illuminate\Database\Eloquent\Model;

class EnumMacro implements MacroInterface
{
    public static function macro(): \Closure
    {
        $closure = function (string $class) {
            $name = $this->name;

            $this->render(function (Model $model) use ($class, $name) {
                return $class::from($model->$name)->label;
            });

            return $this;
        };

        return $closure;
    }
}
