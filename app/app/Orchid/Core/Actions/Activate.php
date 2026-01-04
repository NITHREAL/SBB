<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Button;

class Activate extends ButtonAction
{
    public function __construct(string $method = 'activate')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.activate'));
        $this->setIcon('check');
    }

    public function render(): Button
    {
        if ($this->model->active) {
            $this->setTitle(__('admin.deactivate'));
            $this->setIcon('close');
        } else {
            $this->setTitle(__('admin.activate'));
            $this->setIcon('check');
        }

        return Button::make($this->title)
                ->icon($this->icon)
                ->method($this->method, [
                    'id' => $this->model->id,
                    'activate' => !$this->model->active
                ])
                ->confirm('Вы действительно хотите изменить активность?');
    }
}
