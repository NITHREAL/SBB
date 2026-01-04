<?php

namespace Domain\Order\Builders;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Order\Enums\OrderSourceEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Models\Order;
use Domain\Order\Services\Sbermarket\SbermarketOrderProductsService;
use Domain\Product\Models\Product;
use Domain\Store\Models\Store;
use Domain\User\DTO\Address\UserAddressDTO;
use Domain\User\Models\User;
use Domain\User\Services\Addresses\AddressesService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class SbermarketOrderBuilder
{
    private User $user;

    private Store $store;

    private array $paymentData;

    private array $productsData;

    private array $addressData;

    private array $contactsData;

    private string $deliverySubType;

    private string $comment;

    private string $receiveDate;

    private string $receiveInterval;

    private string $sberOrderId;

    private readonly SbermarketOrderProductsService $sbermarketOrderProductsService;

    private readonly AddressesService $addressesService;

    public function __construct() {
        $this->sbermarketOrderProductsService = app()->make(SbermarketOrderProductsService::class);
        $this->addressesService = app()->make(AddressesService::class);
    }


    public function create(): Order
    {
        $data = $this->prepareOrderData();

        $order = new Order();

        $order->fill($data);

        $order->user()->associate($this->user);

        $order->store()->associate($this->store);

        $order->save();

        $order->products()->sync($this->productsData);

        if (!empty($this->addressData)) {
            $order->contacts()->create($this->contactsData);

            $this->createUserAddress();
        }

        if (!empty($this->paymentData)) {
            $order->payments()->create(
                $this->paymentData,
                ['amount' => Arr::get($this->paymentData, 'amount', 0)],
            );
        }

        return $order;
    }

    public function setUser(array $customerData): self
    {
        $this->user = $this->getCustomer($customerData);

        return $this;
    }

    public function setStore(int $storeId): self
    {
        $this->store = Store::findOrFail($storeId);

        return $this;
    }

    public function setDeliverySubType(array $deliveryIntervalData): self
    {
        $date = Carbon::parse(
            Arr::get($deliveryIntervalData, 'expectedTo')
        );

        $this->deliverySubType = $date->isToday()
            ? PickupTypeEnum::today()->value
            : PolygonDeliveryTypeEnum::extended()->value;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setReceiveDateTime(array $deliveryIntervalData): self
    {
        $this->receiveDate = Arr::get($deliveryIntervalData, 'expectedTo');
        $this->receiveInterval = $this->getDeliveryTimeInterval($deliveryIntervalData);

        return $this;
    }

    public function setComment(?string $comment, string $sberOrderId): self
    {
        $sbermarketComment = sprintf('СМ № %s', $sberOrderId);

        $this->comment = $comment
            ? sprintf('%s / %s', $sbermarketComment, $comment)
            : $sbermarketComment;

        return $this;
    }

    public function setSberOrderId(string $sberOrderId): self
    {
        $this->sberOrderId = $sberOrderId;

        return $this;
    }


    public function setProductsData(array $orderProducts): self
    {
        $productsData = [];

        foreach ($orderProducts as $orderProduct) {
            /** @var Product $product */
            $product = Product::find((int) Arr::get($orderProduct, 'id', 0));

            if ($product) {
                $productsData[$product->getAttribute('system_id')] = $this->sbermarketOrderProductsService
                    ->getPreparedProductDataForOrderCreating($product, $orderProduct);
            }
        }

        $this->productsData = $productsData;

        return $this;
    }

    public function setPaymentData(array $totalData): self
    {
        $paymentData = [];

        if (!empty($totalData)) {
            $totalPrice = Arr::get($totalData, 'totalPrice', 0);

            $paymentData = [
                'status'    => PaymentStatusEnum::hold()->value,
                'payed'     => true,
                'amount'    => $totalPrice,
                'value'     => $totalPrice,
            ];
        }

        $this->paymentData = $paymentData;

        return $this;
    }

    public function setAddressData(array $addressData): self
    {
        if (!empty($addressData)) {
            $this->contactsData = $this->getContactsData($addressData);
        }

        $this->addressData = $addressData;

        return $this;
    }

    private function prepareOrderData(): array
    {
        return [
            'payment_type'          => PaymentTypeEnum::byOnline()->value,
            'delivery_type'         => DeliveryTypeEnum::delivery()->value,
            'delivery_sub_type'     => $this->deliverySubType,
            'status'                => OrderStatusEnum::payed()->value,
            'comment'               => $this->comment,
            'receive_date'          => $this->receiveDate,
            'receive_interval'      => $this->receiveInterval,
            'need_exchange'         => true,
            'need_receipt'          => true,
            'delivery_cost'         => 0,
            'store_system_id'           => $this->store->getAttribute('system_id'),
            'uuid'                  => OrderHelper::makeUuid(),
            'sm_original_order_id'  => $this->sberOrderId,
            'request_from'          => OrderSourceEnum::sbermarket()->value,
        ];
    }

    private function getCustomer(array $customerData): User
    {
        $phone = $this->clearPhone(
            Arr::get($customerData, 'phone')
        );

        $user = User::query()->wherePhone($phone)->first();

        if (empty($user)) {
            $user = $this->createCustomer($customerData, $phone);
        }

        return $user;
    }

    private function getContactsData(array $addressData): array
    {
        $user = $this->user;

        return [
            'name'          => sprintf(
                '%s %s',
                $user->getAttribute('first_name'),
                $user->getAttribute('last_name'),
            ),
            'phone'         => $user->getAttribute('phone'),
            'address'       => Arr::get($addressData, 'full_address'),
            'email'         => '',
            'send_email'    => false,
            'apartment'     => Arr::get($addressData, 'apartment'),
            'floor'         => Arr::get($addressData, 'floor'),
            'entrance'      => Arr::get($addressData, 'entrance'),
            'intercom'      => Arr::get($addressData, 'door_phone'),
            'has_elevator'  => false,
        ];
    }

    private function createCustomer(array $customerData, string $phone): User
    {
        $customerName = Arr::get($customerData, 'name');

        if (empty($customerName)) {
            $nameData = ['No', 'Name'];
        } else {
            $nameData = explode(' ', $customerName);
        }

        $user = new User();
        $user->first_name = Arr::get($nameData, 0);
        $user->last_name = Arr::get($nameData, 1);
        $user->phone = $phone;
        $user->save();

        return $user;
    }

    private function createUserAddress(): void
    {
        $addressData = $this->addressData;
        $user = $this->user;

        $fullAddress = Arr::get($addressData, 'full_address', '');
        $cityId = $this->store->getAttribute('city_id');

        if (
            $fullAddress
            && $cityId
            && $this->addressesService->isUserAddressExists($user->id, $fullAddress) === false
        ) {
            $addressData['cityId'] = $cityId;
            $addressData['address'] = $fullAddress;

            $addressDTO = UserAddressDTO::make($addressData, $user);

            $this->addressesService->createUserAddress($addressDTO);
        }
    }

    private function clearPhone(string $phone): string
    {
        if (strlen($phone) > 10) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    private function getDeliveryTimeInterval(array $deliveryIntervalData): string
    {
        $expectedFromDate = Arr::get($deliveryIntervalData, 'expectedFrom', Carbon::now());
        $expectedToDate = Arr::get($deliveryIntervalData, 'expectedTo', Carbon::now());

        $expectedFrom = Carbon::parse($expectedFromDate)->addHours(4);
        $expectedTo = Carbon::parse($expectedToDate)->addHours(4);

        return sprintf('%s_%s', $expectedFrom->format('H'), $expectedTo->format('H'));
    }
}
