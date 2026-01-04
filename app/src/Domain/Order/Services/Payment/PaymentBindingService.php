<?php

namespace Domain\Order\Services\Payment;

use Domain\Order\DTO\Payment\OnlinePaymentBindingDTO;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Payment\OnlinePaymentBinding;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PaymentBindingService
{
    public function createBinding(OnlinePaymentBindingDTO $dto): OnlinePaymentBinding
    {
        $binding = new OnlinePaymentBinding();

        $cardData = $dto->getCardData();

        $binding->fill([
            'acquiring_binding_id'  => $dto->getBindingId(),
            'expiry_date'           => $dto->getExpiryDate(),
            'card_description'      => $dto->getCardDescription(),
            'acquiring_type'        => $dto->getAcquiringType(),
            'first_chars'           => $cardData->getFirstChars(),
            'last_chars'            => $cardData->getLastChars(),
            'card_type'             => $cardData->getCardType(),
        ]);

        $binding->user()->associate($dto->getUser());

        $binding->save();

        return $binding;
    }

    public function getBindingOrCreate(OnlinePaymentBindingDTO $dto): OnlinePaymentBinding
    {
        $userId = $dto->getUser()->id;

        $binding = OnlinePaymentBinding::query()
            ->whereAcquiringCardData($userId, $dto->getCardData())
            ->whereAcquiringType($dto->getAcquiringType())
            ->first();

        if (empty($binding)) {
            $binding = $this->createBinding($dto);
            Log::channel('payment')->info('Binding создан: ' . json_encode($binding, JSON_THROW_ON_ERROR));
        }

        return $binding;
    }

    public function getUserBindings(int $userId): Collection
    {
        return OnlinePaymentBinding::query()
            ->whereUser($userId)
            ->whereAcquiringType(OnlinePaymentHelper::getCurrentAcquiringType())
            ->get();
    }

    public function removeBinding(int $bindingId): bool
    {
        return OnlinePaymentBinding::query()->whereId($bindingId)->delete();
    }

    public function setUserBindingIsDefault(int $userId, int $bindingId): bool
    {
        // Реализовано через получение объекта и его изменение чтобы задействовать события eloquent
        // для обновления остальных связок пользователя

        $binding = OnlinePaymentBinding::query()
            ->whereUser($userId)
            ->whereId($bindingId)
            ->firstOrFail();

        $binding->is_default = true;

        return $binding->save();
    }

    public function resetUserBindingsIsDefault(int $userId): bool
    {
        return OnlinePaymentBinding::query()
            ->whereUser($userId)
            ->whereAcquiringType(OnlinePaymentHelper::getCurrentAcquiringType())
            ->update(['is_default' => false]);
    }
}
