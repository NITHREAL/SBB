<?php

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\Notification\PushNotificationListLayout;
use Domain\Audience\Models\Audience;
use Domain\User\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Infrastructure\Notifications\PushNotification;
use Infrastructure\Services\Firebase\DTO\FirebaseNotificationDTO;
use Infrastructure\Services\Firebase\FirebaseService;
use JsonException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PushNotificationScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string|null
     */
    public ?string $name = 'Push уведомления';
    public ?string $description = 'Push уведомления отправляются пользователям и отображаются на экране заблокированного
     устройства либо в виде всплывающего окна, даже если приложение закрыто.
     Это позволяет быстро донести важную информацию до пользователей.'
    ;
    public function __construct(
        protected FirebaseService $firebaseService
    ) {
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $sort = request('sort', 'created_at');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';

        $column = ltrim($sort, '-');
        return [
            'notifications' => DatabaseNotification::where('type', PushNotification::class)
                ->orderBy($column, $direction)
                ->get()
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
            ModalToggle::make('Создать push уведомление')
                ->modal('createModal')
                ->method('action')
                ->modalTitle(__('admin.notifications.text'))
                ->icon('bell'),
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
            PushNotificationListLayout::class,
            Layout::modal('createModal', [
                Layout::rows([
                    Relation::make('audienceId')
                        ->fromModel(Audience::class, 'title')
                        ->title('Аудитория')
                        ->required(),
                    Input::make('title')
                        ->type('text')
                        ->title('Заголовок')
                        ->placeholder('Введите заголовок уведомления')
                        ->required(),
                    Input::make('deeplink')
                        ->type('text')
                        ->title('Deeplink')
                        ->placeholder('Укажите deeplink для уведомления'),
                    Input::make('url')
                        ->type('text')
                        ->title('URL')
                        ->placeholder('Укажите ссылку на внешний сайт'),
                    TextArea::make('text')
                        ->rows(3)
                        ->title('Содержимое')
                        ->placeholder('Введите текст уведомления')
                        ->required()
                ])
            ])
        ];
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     * @throws JsonException
     */
    public function action(Request $request): RedirectResponse
    {
        $data = $request->only([
            'text',
            'title',
            'deeplink',
            'url',
            'audienceId',
        ]);

        $audience = Audience::findOrFail(Arr::get($data, 'audienceId', 0));

        $deviceTokens = $audience->users
            ->flatMap(function ($user) {
                return $user->mobileTokens->pluck('token');
            })
            ->toArray();

        $firebaseNotificationDTO = new FirebaseNotificationDTO(
            Arr::get($data, 'title'),
            Arr::get($data, 'text'),
            [
                'deeplink' => Arr::get($data, 'deeplink'),
                'url' => Arr::get($data, 'url'),
            ],
            $deviceTokens
        );

        Log::channel('message')->info(
            sprintf(
                'Подготовка к отправке push уведомлений. Уведомления будут отправлены: [%s].',
                json_encode($firebaseNotificationDTO, JSON_THROW_ON_ERROR),
            ),
        );

        $this->firebaseService->sendMulticastNotifications($firebaseNotificationDTO);

        Toast::info(__('admin.notifications.job_created'));

        return back();
    }
}
