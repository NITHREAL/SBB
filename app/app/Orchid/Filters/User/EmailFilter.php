<?php

namespace App\Orchid\Filters\User;

use App\Orchid\Fields\User\EmailInput;
use App\Orchid\Filters\Basic\BasicFilter;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class EmailFilter extends BasicFilter
{
    public $parameters = [
        'email',
    ];

    public function name(): string
    {
        return __('admin.user.email');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $email = $this->request->get('email');

        return $builder->where('email', 'like', '%' . $email . '%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            EmailInput::make('email')
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $email = $this->request->get('email');

        return $this->name() . ': ' . $email;
    }
}
