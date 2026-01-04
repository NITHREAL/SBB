<?php

namespace App\Orchid\Filters\User;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class UserTypeFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'user_type'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('admin.slide.user_type');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('user_type', $this->request->get('user_type'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('user_type')
                ->title(__('admin.slide.user_type'))
                ->options(SlideUserTypesEnum::toArray())
                ->value($this->request->get('user_type'))
        ];
    }
}
