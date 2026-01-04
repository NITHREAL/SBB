<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Orchid\Layouts\Role\RoleEditLayout;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class RoleEditScreen extends Screen
{
    public function name(): ?string
    {
        return $this->exist ? __('platform.role.Edit role') : __('platform.role.Create role');
    }

    public function description(): ?string
    {
        return __('platform.role.Modify the privileges and permissions associated with a specific role');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.roles',
        ];
    }

    /**
     * @var bool
     */
    private $exist = false;

    /**
     * Query data.
     *
     * @param int|null $roles
     * @return array
     */
    public function query(?int $roles = null): array
    {
        $role = !empty($roles) ? Role::findOrFail($roles) : new Role();

        $this->exist = $role->exists;

        return [
            'role'       => $role,
            'permission' => $role->getStatusPermission(),
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
            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exist),
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
            Layout::block([
                RoleEditLayout::class,
            ])
                ->title('Role')
                ->description('
                    Роль — это набор привилегий,
                    который предоставляет пользователям с данной ролью возможность
                    выполнять определённые задачи или операции.'
                ),

            Layout::block([
                RolePermissionLayout::class,
            ])
                ->title('Permission/Privilege')
                ->description('A privilege is necessary to perform certain tasks and operations in an area.'),
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Request $request): RedirectResponse
    {
        $roleId = (int) Arr::get($request->route()->parameters(), 'roles', 0);

        $role = !empty($roleId) ? Role::findOrFail($roleId) : new Role();

        $request->validate([
            'role.slug' => [
                'required',
                Rule::unique(Role::class, 'slug')->ignore($role),
            ],
        ]);

        $role->fill($request->get('role'));

        $role->permissions = collect($request->get('permissions'))
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();

        $role->save();

        Toast::info(__('Role was saved'));

        return redirect()->route('platform.systems.roles');
    }

    /**
     * @param int $roles
     * @return RedirectResponse
     */
    public function remove(int $roles): RedirectResponse
    {
        $role = Role::findOrFail($roles);

        $role->delete();

        Toast::info(__('Role was removed'));

        return redirect()->route('platform.systems.roles');
    }
}
