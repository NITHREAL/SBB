<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Orchid\Layouts\Role\RoleListLayout;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class RoleListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Роли пользователя';

    /**
     *
     * @var string
     */
    public $description = 'В системе используются роли, которые управляют доступом и правами пользователей.
    Благодаря этому каждый участник получает доступ только к тем частям проекта,
    которые необходимы для выполнения его задач.';

    /**
     * @var string
     */
    public $permission = 'platform.systems.roles';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'roles' => Role::filters()->defaultSort('id', 'desc')->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->href(route('platform.systems.roles.create')),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            RoleListLayout::class,
        ];
    }
}
