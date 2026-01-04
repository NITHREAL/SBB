<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Button;

class SoftDelete extends ButtonAction
{
    public function __construct(string $method = 'softDelete')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.soft_delete'));
        $this->setIcon('drawer');
    }

    public function render(): Button
    {
        return Button::make($this->title)
            ->icon($this->icon)
            ->confirm(__('admin.confirm.softDelete'))
            ->method($this->method, ['id' => $this->model->id]);
    }
}
