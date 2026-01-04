<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Link;

class Edit extends LinkAction
{
    public function __construct(string $route, array $parameters = [])
    {
        parent::__construct($route, $parameters);

        $this->setTitle(__('admin.update'));
        $this->setIcon('pencil');
    }

    public function render(): Link
    {
        $routeParams = count($this->parameters)
            ? array_merge($this->parameters, ['id' => $this->model->id])
            : $this->model;

        return Link::make($this->title)
            ->route($this->route, $routeParams)
            ->icon($this->icon);
    }
}
