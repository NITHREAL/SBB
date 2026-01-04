<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Button;

class Dublicate extends ButtonAction
{
    public function __construct(string $method = 'dublicate', $model = null)
    {
        parent::__construct($method);

        $this->model = $model;
        $this->setTitle(__('admin.dublicate'));
        $this->setIcon('docs');
    }

    public function render(): Button
    {
        $this->setTitle(__('admin.dublicate'));
        $this->setIcon('docs');

        return Button::make($this->title)
            ->icon($this->icon)
            ->method($this->method, [
                'id' => $this->model->id,
            ]);
    }
}
