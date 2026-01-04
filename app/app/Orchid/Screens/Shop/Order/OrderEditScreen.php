<?php

namespace App\Orchid\Screens\Shop\Order;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Order\Edit\OrderContactsLayout;
use App\Orchid\Layouts\Shop\Order\Edit\OrderDeliveryLayout;
use App\Orchid\Layouts\Shop\Order\Edit\OrderInfoLayout;
use App\Orchid\Layouts\Shop\Order\Edit\OrderPaymentLayout;
use App\Orchid\Layouts\Shop\Order\OrderProductsLayout;
use Carbon\Carbon;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Jobs\Payment\CheckPaymentStatusJob;
use Domain\Order\Jobs\Payment\OrderAdditionalPaymentJob;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Requests\Admin\Order\OrderRequest;
use Domain\Order\Services\OrderPaymentsService;
use Domain\Product\Models\Product;
use Illuminate\Support\Arr;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class OrderEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Добавить заказ';

    private Order $order;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Order $order): array
    {
        if ($order->exists) {
            $order->load('store', 'products.images', 'payment', 'utm', 'reviews');

            $this->name = 'Информация о заказе №' . $order->id;
        }

        $this->order = $order;

        $this->showNotificationWhenCompleted();

        return [
            'order' => $order
        ];
    }

    private function showNotificationWhenCompleted(): void
    {
        if ($this->order->completed) {
            Alert::info(
                'Заказ ' . OrderStatusEnum::from($this->order->status)->label . ' и его нельзя изменить'
            );
        }
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->order),
        ]);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                'Основная информация' => [OrderInfoLayout::class],
                'Информация о доставке' => Layout::columns([
                    OrderDeliveryLayout::class,
                    OrderContactsLayout::class
                ]),
                'Список товаров' => OrderProductsLayout::class,
                'Оплата' => OrderPaymentLayout::class,
            ])
        ];
    }

    public function save(Order $order, OrderRequest $request)
    {
        $data = $request->validated()['order'];

        // Преобразование даты receive_date из d-m-Y в Y-m-d
        if (isset($data['receive_date'])) {
            $data['receive_date'] = Carbon::createFromFormat('d-m-Y', $data['receive_date'])->format('Y-m-d');
        }

        $products = Arr::get($data, 'products', []);

        if (Arr::get($data, 'status') === OrderStatusEnum::completed()->value) {
            $data['completed_at'] = now();
        }

        $order->fill($data)->save();

        if (!$order->isCompleted) {
            $syncProductData = $this->prepareProductSyncData($products);

            $order->products()->sync($syncProductData);
        }

        Alert::success(__('admin.toasts.updated'));

        return response()->redirectToRoute('platform.orders.edit', $order);
    }

    private function prepareProductSyncData(array $products)
    {
        $productsData = [];

        foreach ($products as $product) {
            $productsData[$product['system_id']] = Arr::except($product['pivot'], 'system_id');
        }

        $productCollection = Product::whereIn('system_id', array_keys($productsData))->get();

        foreach ($productsData as $id => $productData) {
            $product = $productCollection->where('system_id', $id)->first();

            $totalWithoutDiscount = Arr::get($product, 'price', 0) * Arr::get($product, 'count', 1);

            $productsData[$id] = array_merge([
                'unit_system_id'                => $product->unit_system_id,
                'total_without_discount'    => $totalWithoutDiscount,
            ], $productData);
        }

        return $productsData;
    }

    public function check(int $id)
    {
        $payment = OnlinePayment::findOrFail($id);

        if ($payment->status === PaymentStatusEnum::hold()->value || !$payment->payed) {
            CheckPaymentStatusJob::dispatch($payment);
            Toast::success('Задача на подтверждение поставлена');
        } else {
            Toast::info('Платеж уже подтвержден');
        }
    }

    public function retryFailedPayment(OnlinePayment $payment): void
    {
        if ($payment->status === PaymentStatusEnum::error()->value) {
            OrderAdditionalPaymentJob::dispatch($this->order, $payment->amount)->afterResponse();
            Toast::success('Задача на повторную попытку платежа поставлена');
        } else {
            Toast::success('Заказ уже оплачен');
        }
    }

    public function checkOrder(Order $order): void
    {
        if ($order->status !== OrderStatusEnum::waitingPayment()->value
            && $order->status !== OrderStatusEnum::surcharge()->value
        ) {
            Toast::warning('Проверка платежей происходит только в статусе заказа “Ожидание оплаты” или “Ожидание доплаты”');

            return;
        }

        $result = OrderPaymentsService::checkPayments($order);

        if ($result['total'] <= $result['payed']) {
            Toast::success('Заказ переведен в статус Оплачен. Ожидайте синхронизацию с 1С');
        } else {
            Toast::warning('Статус заказа не изменён, так как сумма платежей клиента меньше стоимости заказа');
        }
    }
}
