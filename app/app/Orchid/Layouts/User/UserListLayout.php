<?php

namespace App\Orchid\Layouts\User;

use App\Orchid\Helpers\TD\Active;
use Domain\User\Models\User;
use App\Orchid\Core\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('first_name', __('admin.user.full_name'))
                ->sort()
                ->cantHide()
                ->render(function (User $user) {
                    return new Persona($user->presenter());
                }),

            Active::make()
                ->sort(),

            TD::make('phone', __('admin.user.phone'))
                ->sort()
                ->cantHide()
                ->render(function (User $user) {
                    return $user->presenter()->phoneNumber();
                }),

            TD::make('email', __('Email'))
                ->sort()
                ->cantHide()
                ->render(function (User $user) {
                    return ModalToggle::make($user->email)
                        ->modal('oneAsyncModal')
                        ->modalTitle($user->presenter()->title())
                        ->method('saveUser')
                        ->asyncParameters([
                            'user' => $user->id,
                        ]);
                }),

            TD::make('created_at', __('admin.user.created_at'))
                ->sort()
                ->render(function (User $user) {
                    return $user->created_at?->format('d-m-Y H:i:s');
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (User $user) {
                    return $user->updated_at?->format('d-m-Y H:i:s');
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (User $user) {
                    $buttonText = $user->active ? __('admin.deactivate') : __('admin.activate');
                    $buttonIcon = $user->active ? 'ban' : 'check';
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.users.edit', $user->id)
                                ->icon('pencil'),

                            Button::make(__($buttonText))
                                ->icon($buttonIcon)
                                ->method('activate', [
                                    'id' => $user->id,
                                    'active' => $user->active,
                                ]),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Once the account is deleted, all of its resources and data will be ' .
                                    'permanently deleted. Before deleting your account, please download any data or ' .
                                    'information that you wish to retain.'))
                                ->method('remove', [
                                    'id' => $user->id,
                                ]),
                        ]);
                }),
        ];
    }
}
