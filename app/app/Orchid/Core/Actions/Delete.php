<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Button;

class Delete extends ButtonAction
{
    public function __construct(string $method = 'delete')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.delete'));
        $this->setIcon('trash');
    }

    public function render(): Button
    {
        return Button::make($this->title)
            ->novalidate()
            ->icon($this->icon)
            ->confirm(__('admin.confirm.delete'))
            ->method($this->method, ['id' => $this->model->id]);
    }
}
