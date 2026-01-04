<?php

namespace Domain\Support\Events;

use Domain\Support\Models\SupportMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportMessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $channel = 'support_messages';

    private string $alias = 'support_message_created';

    public function __construct(
        public readonly SupportMessage $message,
    ) {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel(sprintf('%s.%s', $this->channel, $this->message->user_id));
    }

    public function broadcastWith(): array
    {
        return [
            'id'        => $this->message->id,
            'text'      => $this->message->text,
            'createdAt' => $this->message->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function broadcastAs(): string
    {
        return $this->alias;
    }
}
