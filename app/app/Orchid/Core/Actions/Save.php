<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Button;

class Save extends ButtonAction
{
    public function __construct(string $method = 'save')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.save'));
        $this->setIcon('check');
    }

    public function render(): Button
    {
        return Button::make($this->title)
            ->method($this->method)
            ->icon($this->icon);
    }
}
