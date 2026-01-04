<?php

namespace App\Orchid\Screens\Support;

use App\Orchid\Layouts\Support\SupportDetailLayout;
use Domain\Support\Models\SupportMessage;
use Domain\User\Models\User;
use Illuminate\Http\Request;
use Infrastructure\Notifications\PushNotification;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\Support\ChatInputLayout;
use Orchid\Support\Facades\Toast;

class SupportChatScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Чат с покупателем';
    private ?User $user;


    public function query(User $user): array
    {
        $this->user = $user;
        $notViewedMessages = SupportMessage::query()
            ->where('user_id', $user->id)
            ->where('author', 'user')
            ->orderBy('created_at', 'desc')
            ->where('viewed', false)->get();
        foreach ($notViewedMessages as $model){ // Todo: сделать одним запросом
            $model->viewed = true; // Метим как прочитанные все сообщения в чате
            $model->save();
        }

        return [
            'messages' => SupportMessage::query()
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get(),
            'user' => $user,
            'interval' => 600, //интервал автообновления
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
            Link::make($this->user->first_name ?? $this->user->phone)
                ->route('platform.systems.users.edit', ['user' => $this->user->id])
                ->target('_blank')
                ->icon('user')
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
            ChatInputLayout::class,
            SupportDetailLayout::class,
        ];
    }

    public function send(Request $request)
    {
        $supportMessages = SupportMessage::create([
            'user_id' => $request->post('user_id'),
            'text' => $request->post('answer'),
            'author' => 'administrator',
        ]);
        Toast::success(__('Сообщение отправлено'));

        $notify = new PushNotification('Служба сервиса', $supportMessages->text, null, 'klnmln://support/message');

        $supportMessages->user->notify($notify);

        return redirect()->route('platform.support.detail', ['user' => $request->get('user_id')]);
    }
}
