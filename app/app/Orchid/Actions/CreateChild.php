<?php

namespace App\Orchid\Actions;

use Orchid\Screen\Actions\Link;
use App\Orchid\Core\Actions\LinkAction;

class CreateChild extends LinkAction
{
    public function __construct(string $route)
    {
        parent::__construct($route);

        $this->setTitle(__('admin.create'));
        $this->setIcon('plus');
    }

    public function render(): Link
    {
        return Link::make($this->title)
            ->route($this->route, ['parent_id' => $this->model->id])
            ->icon($this->icon);
    }
}
