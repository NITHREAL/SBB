<?php

namespace App\Orchid\Filters;

use App\Orchid\Filters\Basic\RangeFilter;
use Orchid\Screen\Fields\Input;

class SortFilter extends RangeFilter
{
    public $parameters = [
        'sorting'
    ];

    protected ?string $dbColumn = 'sort';

    public function name(): string
    {
        return __('admin.sort');
    }

    public function display(): array
    {
        $param = $this->getParam();

        return [
            Input::make($param . '[from]')
                ->title('Сортировка от')
                ->type('number')
                ->step(1),

            Input::make($param . '[to]')
                ->title('Сортировка до')
                ->type('number')
                ->step(1),
        ];
    }
}
