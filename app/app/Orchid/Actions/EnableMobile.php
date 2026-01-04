<?php

namespace App\Orchid\Actions;

use App\Orchid\Core\Actions\ButtonAction;
use Orchid\Screen\Actions\Button;

class EnableMobile extends ButtonAction
{
    public function __construct(string $method = 'mobile')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.enableMobile'));
        $this->setIcon('check');
    }

    public function render(): Button
    {
        if ($this->model->mobile) {
            $this->setTitle(__('admin.disableMobile'));
            $this->setIcon('close');
        } else {
            $this->setTitle(__('admin.enableMobile'));
            $this->setIcon('check');
        }

        return Button::make($this->title)
            ->icon($this->icon)
            ->method($this->method, [
                'id' => $this->model->id,
                'activate' => !$this->model->mobile
            ]);
    }
}
