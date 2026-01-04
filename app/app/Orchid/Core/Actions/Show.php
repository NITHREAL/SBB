<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Link;

class Show extends LinkAction
{
    public function __construct(string $route)
    {
        parent::__construct($route);

        $this->setTitle(__('admin.show'));
        $this->setIcon('eye');
    }

    public function render(): Link
    {
        return Link::make($this->title)
            ->route($this->route, $this->model)
            ->icon($this->icon);
    }
}
