<?php

namespace app\Orchid\Screens\User;

use App\Orchid\Layouts\User\Notification\MassNotificationListLayout;
use Domain\Audience\Models\Audience;
use Domain\Notification\DTO\MassNotificationDTO;
use Domain\Notification\Enum\NotificationRecipientTypeEnum;
use Domain\Notification\Enum\NotificationSendMethodEnum;
use Domain\Notification\Services\MassNotificationService;
use Domain\Notifycation\Email\MassMailNotification;
use Domain\User\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\Notifications\CustomInternalNotification;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MassNotificationScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string|null
     */
    public ?string $name = 'Внутренние уведомления';
    public ?string $description = 'Внутренние уведомления отправляются пользователям внутри мобильного приложения.
    Они отображаются в специальной области уведомлений (иконка колокольчика) и позволяют пользователям получать важные
    сообщения и обновления, даже когда приложение активно используется.'
    ;

    public function __construct(
        protected MassNotificationService $massNotificationService
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

        $notifications = DatabaseNotification::where('type', CustomInternalNotification::class)
            ->orderBy($column, $direction)
            ->get()
            ->filter()
            ->unique('data.mass_notification_id')
            ->values()
            ->all();

        return [
            'notifications' => $notifications
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
            ModalToggle::make('Создать массовое уведомление')
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
        $notificationSendMethods = Arr::except(NotificationSendMethodEnum::toArray(), [NotificationSendMethodEnum::sms()->value]);

        return [
            MassNotificationListLayout::class,
            Layout::modal('createModal', [
                Layout::rows([
                    Select::make('notification_recipient_type')
                        ->options(NotificationRecipientTypeEnum::toArray())
                        ->onchange('updateVisibility()')
                        ->title('Тип получения уведомления'),
                    Relation::make('audienceId')
                        ->id('audience-relation')
                        ->fromModel(Audience::class, 'title')
                        ->title('Аудитория'),
                    Relation::make('userId')
                        ->id('user-relation')
                        ->fromModel(User::class, 'first_name')
                        ->displayAppend('name_with_phone')
                        ->title('Пользователь'),
                    Relation::make('userIds')
                        ->id('users-relation')
                        ->fromModel(User::class, 'first_name')
                        ->displayAppend('name_with_phone')
                        ->title('Пользователи')
                        ->multiple(),
                    Select::make('notification_send_method')
                        ->options($notificationSendMethods)
                        ->title('Способ отправки'),
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

    public function action(Request $request): RedirectResponse
    {
        $data = $request->only([
            'text',
            'title',
            'deeplink',
            'url',
            'audienceId',
            'userId',
            'userIds',
            'notification_recipient_type',
            'notification_send_method',
        ]);

        $massNotificationDTO = new MassNotificationDTO(
            Arr::get($data, 'title'),
            Arr::get($data, 'text'),
            Arr::get($data, 'url'),
            Arr::get($data, 'deeplink'),
        );

        $massNotificationId = (string) Str::uuid();

        $sendMethod = Arr::get($data, 'notification_send_method');

        match($sendMethod) {
            NotificationSendMethodEnum::app()->value    => $this->sendAppNotification($data, $massNotificationDTO, $massNotificationId),
            NotificationSendMethodEnum::email()->value  => $this->sendEmailNotification($data, $massNotificationDTO, $massNotificationId),
            NotificationSendMethodEnum::sms()->value    => $this->sendSmsNotification($data, $massNotificationDTO, $massNotificationId),
        };

        Toast::info(__('admin.notifications.job_created'));

        return back();
    }

    private function sendAppNotification(array $data, MassNotificationDTO $massNotificationDTO, string $massNotificationId): void
    {
        $recipientType = Arr::get($data, 'notification_recipient_type');

        switch ($recipientType) {
            case NotificationRecipientTypeEnum::audience()->value:
                $audience = Audience::findOrFail(Arr::get($data, 'audienceId', 0));
                $this->massNotificationService->sendAudienceMassNotification($audience, $massNotificationDTO, $massNotificationId);
                break;
            case NotificationRecipientTypeEnum::personalized()->value:
                $user = User::find(Arr::get($data, 'userId'));
                if ($user) {
                    $this->massNotificationService->sendUsersMassNotification([$user], $massNotificationDTO, $massNotificationId);
                }
                break;
            case NotificationRecipientTypeEnum::custom()->value:
                $users = User::whereIn('id', Arr::get($data, 'userIds'))->get();
                $this->massNotificationService->sendUsersMassNotification($users, $massNotificationDTO, $massNotificationId);
                break;
        }
    }

    private function sendEmailNotification(array $data, MassNotificationDTO $massNotificationDTO): void
    {
        $recipientType = Arr::get($data, 'notification_recipient_type');

        switch ($recipientType) {
            case NotificationRecipientTypeEnum::audience()->value:
                $audience = Audience::findOrFail(Arr::get($data, 'audienceId', 0));
                foreach ($audience->users as $user) {
                    $user->notify(new MassMailNotification($massNotificationDTO));
                }
                break;
            case NotificationRecipientTypeEnum::personalized()->value:
                $user = User::find(Arr::get($data, 'userId'));
                if ($user) {
                    $user->notify(new MassMailNotification($massNotificationDTO));
                }
                break;
            case NotificationRecipientTypeEnum::custom()->value:
                $users = User::whereIn('id', Arr::get($data, 'userIds'))->get();
                foreach ($users as $user) {
                    $user->notify(new MassMailNotification($massNotificationDTO));
                }
                break;
        }
    }
}
