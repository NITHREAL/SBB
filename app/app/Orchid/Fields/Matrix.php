<?php

namespace App\Orchid\Fields;

class Matrix extends \Orchid\Screen\Fields\Matrix
{
    /**
     * Blade template
     *
     * @var string
     */
    protected $view = 'fields.matrix';

    protected $attributes = [
        'index'             => 0,
        'removableRows'     => true,
        'idPrefix'          => null,
        'maxRows'           => 0,
        'keyValue'          => false,
        'fields'            => [],
        'addRowLabel'       => 'Add row',
        'columns'           => [
            'key',
            'value',
        ],
    ];

    public function setFirstCol(bool $value = false): self
    {
        return $this->set('firstCol', $value);
    }
}
