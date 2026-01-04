<?php

namespace App\Orchid\Actions;

use App\Orchid\Core\Actions\ButtonAction;
use Orchid\Screen\Actions\Button;

class EnableSite extends ButtonAction
{
    public function __construct(string $method = 'site')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.enableSite'));
        $this->setIcon('check');
    }

    public function render(): Button
    {
        if ($this->model->site) {
            $this->setTitle(__('admin.disableSite'));
            $this->setIcon('close');
        } else {
            $this->setTitle(__('admin.enableSite'));
            $this->setIcon('check');
        }

        return Button::make($this->title)
            ->icon($this->icon)
            ->method($this->method, [
                'id'        => $this->model->id,
                'activate'  => !$this->model->site
            ]);
    }
}
