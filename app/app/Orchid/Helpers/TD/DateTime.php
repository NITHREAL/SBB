<?php

namespace App\Orchid\Helpers\TD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DateTime extends BaseCustomTD
{
    public static function make(string $name = '', string $title = null): static
    {
        $title = $title ?? __('admin.active') ?? Str::title($name);

        return self::makeInstance($name, $title)
            ->width(140)
            ->sort();
    }

    public static function createdAt(): self
    {
        $name = 'created_at';
        $title = __('admin.created_at');

        return self::make($name, $title)->datetime();
    }

    public static function updatedAt(): DateTime
    {
        $name = 'updated_at';
        $title = __('admin.updated_at');

        return self::make($name, $title)->datetime();
    }

    public static function deletedAt(): DateTime
    {
        $name = 'deleted_at';
        $title = __('admin.deleted_at');

        return self::make($name, $title)->datetime();
    }

    public function datetime(): DateTime
    {
        $format = config('platform.datetime_format');

        $this->renderData($format);

        return $this;
    }

    private function renderData(string $format): void
    {
        $prop = $this->name;

        $this->render(function (Model $model) use ($prop, $format) {
            return $model->$prop?->format($format);
        });
    }
}
