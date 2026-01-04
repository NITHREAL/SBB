<?php

namespace Domain\User\Services\Payment;

use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Helpers\Payment\PaymentHelper;
use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\PaymentBindingService;
use Domain\Store\Models\Store;
use Domain\User\DTO\Payment\UserPaymentMethodDTO;
use Domain\User\Models\User;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class UserPaymentService
{
    public function __construct(
        private readonly PaymentBindingService $paymentBindingService,
    ) {
    }

    public function getPaymentMethods(User $user): array
    {
        return [
            'bindings'  => $this->getBindings($user),
            'types'     => $this->getPaymentTypes($user),
        ];

    }

    public function updateDefaultPaymentMethod(UserPaymentMethodDTO $dto): array
    {
        $this->setDefaultPaymentMethod($dto);

        return $this->getPaymentMethods($dto->getUser());
    }

    public function updateDefaultPaymentMethodByOrder(Order $order): void
    {
        $userPaymentMethodDTO = UserPaymentMethodDTO::make(
            [
                'paymentType'   => $order->payment_type,
                'bindingId'     => $order->binding_id,
            ],
            $order->user,
        );

        $this->setDefaultPaymentMethod($userPaymentMethodDTO);
    }

    private function getPaymentTypes(User $user): array
    {
        $result = [];

        /** @var Store $store */
        $store = BuyerStore::getSelectedStore();
        $deliveryType = BuyerDeliveryType::getValue();

        $types = PaymentTypeEnum::toArray();

        foreach ($types as $value => $label) {
            if ($this->isPaymentTypeAvailableInStore($value, $store->payments, $deliveryType)) {
                $result[] = [
                    'title'         => $value !== PaymentTypeEnum::byOnline()->value
                        ? $label
                        : __('payments.title_for_online_payment'),
                    'value'         => $value,
                    'is_default'    => $user->default_payment_type === $value,
                ];
            }
        }

        return $result;
    }

    private function getBindings(User $user): array
    {
        $bindings = $this->paymentBindingService->getUserBindings($user->id);

        return $bindings
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'description'   => $item->card_description,
                    'is_default'    => $item->is_default,
                ];
            })
            ->toArray();
    }

    private function setDefaultPaymentMethod(UserPaymentMethodDTO $dto): void
    {
        $paymentType = $dto->getPaymentType();
        $user = $dto->getUser();
        $bindingId = $dto->getBindingId();

        $user->default_payment_type = $paymentType;
        $user->save();

        if (PaymentHelper::isPaymentOnline($paymentType) && !empty($bindingId)) {
            $this->paymentBindingService->setUserBindingIsDefault($user->id, $bindingId);
        } else {
            $this->paymentBindingService->resetUserBindingsIsDefault($user->id);
        }
    }

    private function isPaymentTypeAvailableInStore(
        string $paymentType,
        Collection $storePaymentTypes,
        string $deliveryType,
    ): bool {
        $paymentTypeInStore = $storePaymentTypes->where('code', $paymentType)->first();

        if (PaymentHelper::isPermanentAvailableType($paymentType)) {
            // Если способ доставки "Самовывоз", то доступны все постоянные способы отплаты (онлайн, в магазине)
            // Если способ доставки "Доставка", то оплата "в магазине" недоступна
            $result = OrderDeliveryHelper::isPickup($deliveryType)
                || PaymentHelper::isAvailableOnlinePaymentType($paymentType);
        } else {
            $result = $paymentTypeInStore && in_array($deliveryType, $paymentTypeInStore->delivery_type);
        }

        return $result;
    }
}
