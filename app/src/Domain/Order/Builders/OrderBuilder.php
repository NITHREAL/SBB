<?php

namespace Domain\Order\Builders;

use Domain\Order\DTO\OrderDeliveryDTO;
use Domain\Order\Enums\OrderSetting\CheckTypeProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\UnavailableProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\WeightProductOrderSettingEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Helpers\Payment\PaymentHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePaymentBinding;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Order\Services\Delivery\Types\DeliveryService;
use Domain\Promocode\Exceptions\PromocodeException;
use Domain\Promocode\Models\Promocode;
use Domain\Promocode\Service\PromocodeCheck;
use Domain\CouponCategory\Models\CouponCategory;
use Domain\CouponCategory\Services\CouponCheck;
use Domain\CouponCategory\Services\Exceptions\CouponException;
use Domain\Store\Models\Store;
use Domain\User\Models\User;
use Domain\User\Services\Addresses\AddressesService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class OrderBuilder
{
    private User $user;

    private Store $store;

    private ?Promocode $promocode = null;

    private ?CouponCategory $coupon = null;

    private ?OnlinePaymentBinding $binding = null;

    private string $address;

    private int $cityId;

    private string $paymentType;

    private string $deliveryType;

    private string $deliverySubType;

    private string $deliveryDate;

    private string $deliveryTime;

    private ?string $comment = null;

    private string $uuid;

    private string $source;

    private int $batchNumber;

    private ?string $payerIp;

    private ?string $courierService = null;

    private DeliveryService $deliveryService;

    private AddressesService $addressesService;

    private array $basketData = [];

    private array $productsData = [];

    private array $contactsData = [];

    private Order $order;

    private bool $electronicChecks;
    private array $settingData;
    private array $orderSettings;

    public function __construct()
    {
        $this->uuid = OrderHelper::makeUuid();

        $this->deliveryService = app()->make(DeliveryService::class);
        $this->addressesService = app()->make(AddressesService::class);
    }

    /**
     * @throws OrderException
     */
    public function create(): Order
    {
        $data = $this->prepareOrderData();

        $this->setContactsData();
        $this->setProductsData();
        $this->setUpSettingsData();

        try {
            DB::beginTransaction();

            /** @var Order $order */
            $this->order = new Order();
            $this->order->fill($data);

            $this->order->user()->associate($this->user);

            $this->order->save();

            $this->order->contacts()->create($this->contactsData);

            $this->order->settings()->create($this->settingData);

            if ($this->binding) {
                $this->order->binding()->associate($this->binding);
            }

            if ($this->promocode && empty($this->coupon)) {
                $this->applyPromocode();
            }

            if ($this->coupon) {
                $this->applyCoupon();
            }

            $this->setDeliveryPrice();

            $this->order->products()->sync($this->productsData);

            $this->order->save();

            $this->order->total_price = OrderHelper::getTotal($this->order, true, true);
            $this->order->batch = OrderHelper::getBatchNumber();

            $this->order->saveQuietly();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw new OrderException('Ошибка при создании заказа. ' . $exception->getMessage());
        }

        return $this->order;
    }

    public function setDeliveryType(string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function setDeliverySubType(string $deliverySubType): self
    {
        $this->deliverySubType = $deliverySubType;

        return $this;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setCityId(int $cityId): self
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function setUser(): self
    {
        /** @var User $user */
        $user = Auth::user();

        $this->user = $user;

        return $this;
    }

    /**
     * @throws OrderException
     */
    public function setDeliveryDateAndTime(
        string $deliveryDate,
        string $deliveryTime,
        string $deliveryType,
    ): self {
        if (!ReceiveInterval::validate($deliveryTime)) {
            throw new OrderException("Некорректное время доставки. [$deliveryTime]");
        }

        if (!ReceiveInterval::isAvailableDate($deliveryDate)) {
            throw new OrderException("Заказ на выбранную дату недоступен. [$deliveryDate]");
        }

        if (
            OrderDeliveryHelper::isDelivery($deliveryType)
            && $this->isDeliveryDateTimeNotValid($deliveryDate, $deliveryTime)
        ) {
            $intervalData = (new ReceiveInterval($this->store, $this->deliverySubType))
                ->getNearestTimeInterval();

            $deliveryDate = $intervalData['date'];
            $deliveryTime = $intervalData['interval'];
        }

        $this->deliveryDate = $deliveryDate;
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * @throws DeliveryTypeException
     */
    public function setStoreAndPolygon(OrderDeliveryDTO $orderDeliveryDTO): self
    {
        $deliveryType = $orderDeliveryDTO->getDeliveryType();

        if (OrderDeliveryHelper::isPickup($deliveryType)) {
            $store1cId = $orderDeliveryDTO->getStore1cId();

            $store = Store::query()->whereStore1cId($store1cId)->first() ?? BuyerStore::getSelectedStore();
        } else {
            $cityId = $orderDeliveryDTO->getCityId();
            $address = $orderDeliveryDTO->getAddress();

            $store = $this->deliveryService->getNearestStoreByAddress($cityId, $address);
        }

        $this->store = $store;

        return $this;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @throws OrderException
     */
    public function setBinding(?string $bindingId): self
    {
        if ($bindingId) {
            $binding = OnlinePaymentBinding::find($bindingId);

            if(empty($binding)) {
                throw new OrderException('Выбрана некоректная карта пользователя');
            }

            $this->binding = $binding;
        }

        return $this;
    }

    public function setUtm(array $utm): self
    {
        $this->utmLabels = $utm;

        return $this;
    }

    public function setCourierService(?string $courierService): self
    {
        if ($courierService) {
            $this->courierService = $courierService;
        }

        return $this;
    }

    public function setBasketData(array $basketData): self
    {
        $this->basketData = $basketData;

        return $this;
    }

    public function setOrderSettings(array $orderSettings): self
    {
        $this->orderSettings = $orderSettings;

        return $this;
    }

    public function setPromocode(?Promocode $promocode): self
    {
        $this->promocode = $promocode;

        return $this;
    }

    public function setCoupon(?CouponCategory $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function setElectronicChecks(bool $electronicChecks): self
    {
        $this->electronicChecks = $electronicChecks;

        return $this;
    }

    public function setPayerIp(?string $payerIp): self
    {
        $this->payerIp = $payerIp;

        return $this;
    }

    /**
     * @throws OrderException
     */
    private function setContactsData(): void
    {
        $data = [
            'name'      => $this->user->fullName,
            'phone'     => $this->user->phone,
            'email'     => $this->user->email,
            'address'   => $this->address,
            'city_id'   => $this->cityId,
        ];

        if (OrderDeliveryHelper::isDelivery($this->deliveryType)) {
            $userAddress = $this->addressesService->getOneByAddress($this->user->id, $this->address);

            if (empty($userAddress)) {
                throw new OrderException('Такого адреса у пользователя не существует');
            }

            $data = array_merge(
                $data,
                [
                    'name'      => $userAddress->other_customer
                        ? $userAddress->other_customer_name
                        : $this->user->fullName,
                    'phone'     => $userAddress->other_customer
                        ? $userAddress->other_customer_phone
                        : $this->user->phone,
                    'city_id'   => $userAddress->city_id,
                    'address'   => $userAddress->address,
                    'latitude'  => $userAddress->latitude,
                    'longitude' => $userAddress->longitude,
                    'apartment' => $userAddress->apartment,
                    'floor'     => $userAddress->floor,
                    'entrance'  => $userAddress->entrance,
                ],
            );
        }

        $this->contactsData = $data;
    }

    /**
     * @throws OrderException
     */
    private function setProductsData(): void
    {
        $products = Arr::get($this->basketData, 'products');

        if (empty($products)) {
            throw new OrderException('Корзина пуста');
        }

        $productsData = [];

        foreach ($products as $product) {
            $productsData[$product->getAttribute('id1C')] = [
                'unit_system_id'            => $product->unit1cId,
                'price'                     => $product->prices['original']['price'],
                'price_discount'            => $product->prices['original']['price_discount'],
                'price_promo'               => null,
                'price_buy'                 => $product->prices['original']['price_discount']
                    ?? $product->prices['original']['price'],
                'count'                     => $product->count,
                'weight'                    => $product->basketWeight,
                'is_discount'               => !empty($product->price_discount),
                'total'                     => $product->sum,
                'total_without_discount'    => $product->sum_prev,
            ];
        }

        $this->productsData = $productsData;
    }

    private function prepareOrderData(): array
    {
        $deliveryType = $this->deliveryType;
        $isPaymentOnline = PaymentHelper::isPaymentCashlessOnline($this->paymentType);

        $status = $isPaymentOnline
            ? OrderStatusEnum::waitingPayment()->value
            : OrderStatusEnum::created()->value;

        $data = [
            'uuid'                  => $this->uuid,
            'status'                => $status,
            'store_system_id'       => $this->store->getAttribute('system_id'),
            'payment_type'          => $this->paymentType,
            'delivery_type'         => $deliveryType,
            'delivery_sub_type'     => $this->deliverySubType,
            'comment'               => $this->comment,
            'need_exchange'         => true,
            'need_receipt'          => $this->electronicChecks,
            'receive_date'          => $this->deliveryDate,
            'receive_interval'      => $this->deliveryTime,
            'request_from'          => $this->source,
            'payer_ip'              => $this->payerIp,
        ];

        return $data;
    }

    /**
     * @throws PromocodeException
     */
    private function applyPromocode(): void
    {
        $promocode = $this->promocode;

        $phone = Arr::get($this->contactsData, 'phone');

        $promocodeCheck = new PromocodeCheck($promocode);

        $promocodeCheck->createPhoneUsed($phone);

        $this->addPromocodeToOrder($this->promocode);
    }

    /**
     * @throws OrderException
     * @throws CouponException
     */
    private function applyCoupon(): void
    {
        $coupon = $this->coupon;

        $couponDiscount = $coupon->amount_discount;

        $couponCheck = new CouponCheck($coupon);

        if ($couponDiscount) {
            $couponCheck->createCouponUse($this->order->id);
        }

        // TODO скрываем на коробке. в реальном проекте вернуть
//        /** @var Promocode $promocode */
//        $promocode = Promocode::query()->whereCode($coupon->category_coupon_guid)->first();
//
//        if (empty($promocode)) {
//            throw new OrderException('Ошибка при создании заказа. Попытка применить некоректный купон!');
//        }
//
//        $this->addPromocodeToOrder($promocode);
    }

    private function setDeliveryPrice(): void
    {
        // Если способ получения товара доставка и не был применен промокод с бесплатной доставкой,
        // то стоимость доставки берем из корзины. На этапе корзины стоимость была просчитана в зависимости от адреса и полигона
        if (
            OrderDeliveryHelper::isDelivery($this->order->delivery_type)
            && empty($this->promocode?->free_delivery)
        ) {
            $deliveryCost = Arr::get($this->basketData, 'delivery_price');
        } else {
            $deliveryCost = 0;
        }

        $this->order->delivery_cost = $deliveryCost;
    }

    public function setUpSettingsData(): void
    {
        $this->settingData = [
            'unavailable_settings' => Arr::get(
                $this->orderSettings,
                'unavailable_settings',
                UnavailableProductOrderSettingEnum::requestAndChange()->value,
            ),
            'weight_settings' => Arr::get(
                $this->orderSettings,
                'weight_settings',
                WeightProductOrderSettingEnum::callAndAsk()->value,
            ),
            'order_for_other_person_settings' => Arr::get(
                $this->orderSettings,
                'order_for_other_person_settings',
                false
            ),
            'other_person_phone' => Arr::get(
                $this->orderSettings,
                'other_person_name'
            ),
            'other_person_name' => Arr::get(
                $this->orderSettings,
                'other_person_phone'
            ),
            'check_type' => Arr::get(
                $this->orderSettings,
                'check_type',
                CheckTypeProductOrderSettingEnum::electronicCheck()->value
            ),
        ];
    }

    private function isDeliveryDateTimeNotValid(string $date, string $interval): bool
    {
        $timeFrom = Arr::first(explode('_', $interval));

        $deliveryDateTime = Carbon::parse($date)->addHours($timeFrom);
        $now = Carbon::now();

        return $deliveryDateTime->isPast()
            || ($deliveryDateTime->isToday() && (($timeFrom - $now->hour) < 1));
    }

    private function addPromocodeToOrder(Promocode $promocode): void
    {
        $this->order->promocode()->associate($promocode);

        $this->order->discount = Arr::get($this->basketData, 'discount');

        $this->order->save();
    }
}
