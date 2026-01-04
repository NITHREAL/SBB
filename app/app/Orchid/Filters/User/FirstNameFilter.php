<?php

namespace App\Orchid\Filters\User;

use App\Orchid\Fields\User\FirstNameInput;
use App\Orchid\Filters\Basic\BasicFilter;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Field;

class FirstNameFilter extends BasicFilter
{
    public $parameters = [
        'first_name',
    ];

    public function name(): string
    {
        return __('admin.user.first_name');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $firstName = $this->request->get('first_name');

        return $builder->where('first_name', 'like', '%' . $firstName . '%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            FirstNameInput::make('first_name')
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $firstName = $this->request->get('first_name');

        return $this->name() . ': ' . $firstName;
    }
}
