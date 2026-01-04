<?php

namespace App\Orchid\Filters\UtmLabels;

use App\Orchid\Filters\Basic\BasicFilter;
use Domain\UtmLabel\Enums\UtmLabelEnum;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class UtmSourceFilter extends BasicFilter
{
    public $parameters = [
        'utm_source',
    ];

    public function name(): string
    {
        return __('admin.utm.utm_source');
    }

    public function run(Builder $builder): Builder
    {
        $value = $this->request->get('utm_source');

        return $builder->whereHas(
            'utm',
            function (Builder $query) use ($value) {
                return $query
                    ->where('utm_labels.type', '=', UtmLabelEnum::utmSource()->value)
                    ->where('utm_labels.value', 'like', '%' . $value . '%');
            }
        );
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('utm_source')
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $value = $this->request->get('utm_source');

        return $this->name() . ': ' . $value;
    }
}
