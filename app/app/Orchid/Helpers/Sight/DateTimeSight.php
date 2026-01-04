<?php

namespace App\Orchid\Helpers\Sight;

use Illuminate\Database\Eloquent\Model;

class DateTimeSight extends BaseCustomSight
{
    public static function createdAt(): self
    {
        $sight = self::make('created_at', __('admin.created_at'));

        return $sight->datetime();
    }

    public static function updatedAt(): self
    {
        $sight = self::make('updated_at', __('admin.updated_at'));

        return $sight->datetime();
    }

    public static function deletedAt(): self
    {
        $sight = self::make('deleted_at', __('admin.deleted_at'));

        return $sight->datetime();
    }

    public function date(): self
    {
        $format = config('platform.date_format');

        return $this->renderDate($format);
    }

    public function time(): self
    {
        $format = config('platform.time_format');

        return $this->renderDate($format);
    }

    public function datetime(): self
    {
        $format = config('platform.datetime_format');

        return $this->renderDate($format);
    }

    private function renderDate(string $format): self
    {
        $prop = $this->name;

        return $this->render(function (Model $model) use ($prop, $format) {
            return $model->$prop->format($format);
        });
    }
}
