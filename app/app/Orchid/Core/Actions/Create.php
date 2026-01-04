<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Link;

class Create extends LinkAction
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
            ->route($this->route)
            ->icon($this->icon);
    }
}
