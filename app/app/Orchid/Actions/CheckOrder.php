<?php

namespace App\Orchid\Actions;

use App\Orchid\Core\Actions\ButtonAction;
use Orchid\Screen\Actions\Button;

class CheckOrder extends ButtonAction
{
    public function __construct(string $method = 'checkOrder')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.check_payments'));
        $this->setIcon('check');
    }

    public function render(): Button
    {
        return Button::make($this->title)
            ->method($this->method)
            ->icon($this->icon);
    }
}
