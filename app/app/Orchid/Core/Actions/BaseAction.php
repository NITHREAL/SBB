<?php

namespace App\Orchid\Core\Actions;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Contracts\Actionable;

abstract class BaseAction
{
    protected Model $model;

    protected ?string $title;

    protected ?string $icon;

    public static function for(Model $model, string $param = null): self
    {
        $instance = $param ? new static($param) : new static();

        return $instance->setModel($model);
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    abstract public function render(): Actionable;
}
