<?php

namespace App\Orchid\Filters\User;

use App\Orchid\Filters\Basic\BasicFilter;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class RoleFilter extends BasicFilter
{
    public $parameters = [
        'role',
    ];

    public function name(): string
    {
        return __('Roles');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('roles', function (Builder $query) {
            $query->where('slug', $this->request->get('role'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('role')
                ->fromModel(Role::class, 'name', 'slug')
                ->empty()
                ->value($this->request->get('role'))
                ->title(__('Roles')),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->name() . ': ' . Role::where('slug', $this->request->get('role'))->first()->name;
    }
}
