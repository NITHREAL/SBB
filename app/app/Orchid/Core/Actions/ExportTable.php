<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Button;

class ExportTable extends ButtonAction
{
    private string $type;
    private array $filter;
    private array $attr = [];

    public function __construct(string $type, array $filter = [], string $method = 'createExport')
    {
        parent::__construct($method);

        $this->setTitle(__('admin.exports.create_export'));
        $this->setIcon('doc');

        $this->type = $type;
        $this->filter = $filter;
    }

    public function attr($key, $value = null)
    {
        $this->attr[$key] = $value;

        return $this;
    }

    public function render(): Button
    {
        $button = Button::make($this->title)
            ->method($this->method)
            ->parameters([
                'filter' => $this->filter
            ])
            ->icon($this->icon);
        $parameters = $button->get('parameters', []);
        $button->parameters(array_merge($parameters, [
            'type' => $this->type,
            'filter' => $this->filter,
            'attr' => $this->attr,
        ]));

        return $button;
    }
}
