<?php

namespace Domain\Support\Services;

use Domain\Support\DTO\SupportMessageDTO;
use Domain\Support\Exceptions\SupportException;
use Domain\Support\Models\SupportMessage;
use Domain\User\Models\User;

class SupportMessageService
{
    /**
     * @throws SupportException
     */
    public function storeMessage(SupportMessageDTO $supportMessageDTO): object
    {
        $supportMessage = $this->getFilledSupportMessage($supportMessageDTO);

        $user = User::find($supportMessageDTO->getUserId());

        if (empty($user)) {
            throw new SupportException(
                sprintf(
                    '%s %s',
                    'Ошибка при создании сообщения. Не найден пользователь с ID =',
                    "[{$supportMessageDTO->getUserId()}].}]",
                ),
            );
        }

        $supportMessage->user()->associate($user);

        $supportMessage->save();

        return $supportMessage;
    }

    public function updateMessage(int $supportMessageId): object
    {
        $supportMessage = SupportMessage::findOrFail($supportMessageId);

        $supportMessage->viewed = true;

        $supportMessage->save();

        return $supportMessage;
    }

    public function readMessages(array $messageIds): void
    {
        SupportMessage::query()->whereIn('id', $messageIds)->update(['viewed' => true]);
    }

    private function getFilledSupportMessage(
        SupportMessageDTO $supportMessageDTO,
        SupportMessage $supportMessage = null,
    ): SupportMessage {
        $supportMessage = $supportMessage ?? new SupportMessage();

        return $supportMessage->fill([
            'text'          => $supportMessageDTO->getText(),
            'stuff_only'    => $supportMessageDTO->isStuffOnly(),
            'author'        => $supportMessageDTO->getAuthor(),
        ]);
    }
}
