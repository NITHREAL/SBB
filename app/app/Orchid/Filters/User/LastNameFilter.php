<?php

namespace App\Orchid\Filters\User;

use App\Orchid\Fields\User\LastNameInput;
use App\Orchid\Filters\Basic\BasicFilter;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Field;

class LastNameFilter extends BasicFilter
{
    public $parameters = [
        'last_name',
    ];

    public function name(): string
    {
        return __('admin.user.last_name');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $lastName = $this->request->get('last_name');

        return $builder->where('last_name', 'like', '%' . $lastName . '%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            LastNameInput::make('last_name')
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $lastName = $this->request->get('last_name');

        return $this->name() . ': ' . $lastName;
    }
}
