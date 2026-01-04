<?php

namespace App\Orchid\Core\Actions;

use Orchid\Screen\Actions\Link;

class Export extends LinkAction
{
    private string $type;
    private array $filter;
    private bool $forOne;

    public function __construct(string $type, array $filter = [], bool $forOne = false, string $route = 'export')
    {
        parent::__construct($route);

        $this->type = $type;
        $this->forOne = $forOne;
        $this->filter = $filter;

        $this->setTitle(__('admin.exports.create_export'));
        $this->setIcon('doc');
    }

    public function render(): Link
    {
        $params = [
            'type' => $this->type,
            'filter' => $this->filter
        ];

        if ($this->forOne) {
            $params['model'] = $this->model;
        }

        return Link::make($this->title)
            ->route('export', $params)
            ->target('_blank')
            ->icon($this->icon);
    }
}
