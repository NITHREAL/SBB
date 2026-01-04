<?php

namespace App\Orchid\Helpers\Sight;

use App\View\Components\Images;
use Illuminate\Database\Eloquent\Model;
use Orchid\Support\Blade;

class ImagesSight extends BaseCustomSight
{
    public static function make(string $name = 'active', string $title = null): static
    {
        $title = $title ?? __('admin.images');

        return self::makeInstance($name, $title)
            ->render(function (Model $model) use ($name) {
                return Blade::renderComponent(Images::class, [
                    'attached' => $model->$name
                ]);
            });
    }
}
