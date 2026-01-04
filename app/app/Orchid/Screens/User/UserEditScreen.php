<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserNotificationsLayout;
use App\Orchid\Layouts\User\UserRegistrationInfoLayout;
use Domain\User\DTO\UserCreateDTO;
use Domain\User\Enums\RegistrationTypeEnum;
use Domain\User\Models\User;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;
use Orchid\Access\UserSwitch;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\RedirectResponse;

class UserEditScreen extends Screen
{
    private $user;

    private bool $isAdmin = false;

    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function name(): ?string
    {
        return $this->user->exists ? __('platform.user.Edit profile') : __('platform.user.Create profile');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    public function description(): string
    {
        return __('platform.user.User profile and privileges, including their associated role');
    }

    public function query(User $user): array
    {
        $this->user = $user;

        $user->load(['roles']);

        $isAdmin = $user->roles->contains(function ($role) {
            return $role->slug === 'Administrator';
        });

        $this->isAdmin = $isAdmin;

        return [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'permission' => $user->getStatusPermission(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted.'
                    . ' Before deleting your account, please download any data or '
                    . 'information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::modal('bonusModal', Layout::rows([
                Input::make('bonus')
                    ->type('number')
                    ->required(true)
            ])),

            Layout::block(UserEditLayout::class)
                ->title(__('Profile Information'))
                ->description(__('Update your account\'s profile information and email address.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserRegistrationInfoLayout::class)
                ->title(__('admin.user.registration_info'))
                ->canSee($this->user->exists),

            Layout::block(UserNotificationsLayout::class)
                ->title(__('admin.user.notifications'))
                ->canSee($this->user->exists),

            Layout::block(UserPasswordLayout::class)
                ->title(__('Password'))
                ->description(__('Ensure your account is using a long, random password to stay secure.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                )
                ->canSee(!$this->user->exists || $this->isAdmin),

            Layout::block(UserRoleLayout::class)
                ->title(__('Roles'))
                ->description(__('A Role defines a set of tasks a user assigned the role is allowed to perform.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                )
                ->canSee(!$this->user->exists || $this->isAdmin),

            Layout::block(RolePermissionLayout::class)
                ->title(__('Permissions'))
                ->description(__('Allow the user to perform some actions that are not provided for by his roles'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                )
                ->canSee(!$this->user->exists || $this->isAdmin),
        ];
    }

    public function save(
        User $user,
        Request $request
    ): RedirectResponse {
        $userExists = $user->exists;

        $userData = $request->get('user');

        $userData['permissions'] = $request->get('permissions');
        $userData['registration_type'] = RegistrationTypeEnum::admin()->value;

        $userDTO = UserCreateDTO::make($userData, $this->isAdmin, $userExists);

        $phone = $userDTO->getPhone();

        if (
            (!$userExists && User::query()->wherePhone($phone)->exists())
            || ($userExists && User::query()->wherePhone($phone)->whereNot('id', $user->id)->exists())
        ) {
            Alert::warning(sprintf('Пользователь с таким номером уже существует %s', $phone));

            return $userExists
                ? redirect()->route('platform.systems.users.edit', $user)
                : redirect()->route('platform.systems.users.create');
        }

        $user = $userExists
            ? $this->userService->updateUserFromAdmin($userDTO, $user)
            : $this->userService->createUserFromAdmin($userDTO);

        if ($this->isAdmin || !$userExists) {
            $user->replaceRoles($request->input('user.roles'));
        }

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }

    public function remove(User $user): RedirectResponse
    {
        $user->delete();

        Toast::info(__('User was removed'));

        return redirect()->route('platform.systems.users');
    }

    public function loginAs(User $user): RedirectResponse
    {
        UserSwitch::loginAs($user);

        Toast::info(__('You are now impersonating this user'));

        return redirect()->route(config('platform.index'));
    }
}
