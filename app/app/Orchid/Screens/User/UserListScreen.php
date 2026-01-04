<?php

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\AdminListLayout;
use Domain\User\Services\UserService;
use Domain\User\Models\User;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Пользователи';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все зарегистрированные пользователи';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'users' => User::with('roles')
                ->baseQuery()
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->isNotAdmin()
                ->defaultSort('users.id', 'desc')
                ->paginate(),
            'admins' => User::with('roles')
                ->baseQuery()
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->isAdmin()
                ->defaultSort('users.id', 'desc')
                ->paginate(),
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
                ->href(route('platform.systems.users.create')),
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
            UserFiltersLayout::class,
            Layout::tabs([
                'Пользователи' => [UserListLayout::class],
                'Администраторы' => [AdminListLayout::class],
            ]),
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetUser(User $user): array
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     */
    public function saveUser(User $user, Request $request): void
    {
        $request->validate([
            'user.email' => 'required|unique:users,email,'.$user->id,
        ]);

        $user->fill($request->input('user'))
            ->save();

        Toast::info(__('User was saved.'));
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        $user = User::findOrFail($request->get('id'));

        $this->userService->deleteUser($user);

        Toast::info(__('User was removed'));
    }

    public function activate(Request $request): void
    {
        $user = User::findOrFail($request->get('id'));

        $user->active = !$user->active;

        $user->save();
    }
}
