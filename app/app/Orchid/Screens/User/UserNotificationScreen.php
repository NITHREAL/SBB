<?php

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\Notification\NotificationListLayout;
use Domain\User\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Infrastructure\Notifications\CustomInternalNotification;
use Infrastructure\Notifications\PushNotification;
use Infrastructure\Services\Firebase\DTO\FirebaseNotificationDTO;
use Infrastructure\Services\Firebase\FirebaseService;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserNotificationScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Уведомления для пользователя';

    public ?User $user = null;

    public function __construct(
        protected FirebaseService $firebaseService
    ) {
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(int $user): array
    {
        $user = User::findOrFail($user);

        $user->load('notifications');

        $this->user = $user;
        $this->name = 'Уведомления для пользователя '.$this->user->full_name;

        return [
            'user' => $user,
            'notifications' => $user->notifications,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make(__('admin.notifications.notify'))
                ->modal('notifyModal')
                ->modalTitle($this->user->presenter()->title())
                ->method('notify')
                ->icon('pencil')
                ->asyncParameters([
                    'user' => $this->user->id,
                ])
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            // NotificationFiltersLayout::class, TODO:
            NotificationListLayout::class,
            Layout::modal('notifyModal', Layout::rows([
                Select::make('type')
                    ->title('Тип')
                    ->options([
                        'CustomInternalNotification' => 'Пользовательское внутреннее уведомление',
                        'PushNotification' => 'Пуш уведомление',
                    ])
                    ->value( 'CustomInternalNotification' )
                    ->required(true)
                ,
                Input::make('title')
                    ->title('Заголовок')
                    ->min(0)
                    ->required(true)
                ,
                TextArea::make('notification')
                    ->title(__('admin.notifications.body'))
                    ->min(0)
                    ->required(true)
            ]))
        ];
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function notify(User $user, Request $request): RedirectResponse
    {
        $title = $request->input('title');
        $notification = $request->input('notification');

        if ($request->input('type') === 'PushNotification') {
            $mobileToken = $user->mobileTokens->pluck('token');

            if (empty($mobileToken)) {
                Toast::info(__('admin.notifications.user_doesnt_have_token'));

                return redirect()->route('platform.systems.user.notify', $user->id);
            }

            $firebaseNotificationDTO = new FirebaseNotificationDTO(
                $title,
                $notification,
                [],
                [$mobileToken]
            );

            $this->firebaseService->sendMulticastNotifications($firebaseNotificationDTO);
        } else {
            $notify = new CustomInternalNotification($title, $notification);

            $user->notify($notify);
        }


        Toast::info(__('admin.notifications.user_was_notified'));

        return redirect()->route('platform.systems.user.notify', $user->id);
    }
}
