<?php

namespace Domain\Order\Jobs;

use App\Orchid\Filters\Order\UtmTypeFilter;
use App\Orchid\Filters\Order\UtmValueFilter;
use App\Orchid\Layouts\Shop\Order\OrderFilterLayout;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\OrderSourceEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderProduct;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Domain\UtmLabel\Enums\UtmLabelEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Infrastructure\Helpers\PhoneFormatterHelper;
use Infrastructure\Notifications\Admin\ExportJobCompletedNotification;
use Symfony\Component\HttpFoundation\InputBag;

class OrderExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800;

    protected int $chunk = 300;

    protected int $count;

    protected array $filters;

    protected User $user;

    protected string $notificationTitle;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, array $filters = [])
    {
        $this->filters = $filters;
        $this->user = $user;
        $this->notificationTitle = __('admin.order.export_notification_title');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $file = tempnam(storage_path('app/public/laravel-excel'), 'export_') . '.csv';
        $h = fopen($file, 'w');
        //
        fputcsv($h, $this->headings(), "\t");
        $this->collection($h);
        fclose($h);

        $path = Storage::drive('public')->putFileAs('exports', new File($file), basename($file));
        $url = Storage::url($path);
        unlink($file);

        $notification = new ExportJobCompletedNotification($this->notificationTitle);
        $notification->downloadUrl = $url;

        $this->user->notify($notification);
    }

    public function collection($resource): void
    {
        $this->count = $this->query()->count();

        $this->query()
            ->chunk($this->chunk, function ($rows) use ($resource) {
                $products = new Collection();

                $rows->each(function ($order) use (&$products) {
                    /** @var Order $order  */
                    $order->products->each(function ($product) use ($order, &$products) {
                        /** @var OrderProduct $product */
                        $product->order = $order;
                        $products->add($product);
                    });
                });

                $products->each(function ($row) use ($resource) {
                    fputcsv($resource, $this->map($row), "\t");
                });
            });
    }

    public function query()
    {
        request()->query = new InputBag([]);
        request()->query->replace($this->filters);

        $query =  Order::query()
            ->with('user', 'payment', 'contacts', 'store', 'utm', 'products')
            ->whereNotOffline()
            ->orderBy('id', 'desc')
            ->filtersApplySelection(OrderFilterLayout::class)
            ->filters();

        $request = Request::createFromBase(request());
        $request->query->replace(request()->get('filter', []));
        $request->query->replace($this->filters);

        $query = tap(new UtmTypeFilter(), static function ($filter) use ($request) {
            $filter->request = $request;
        })->run($query);
        $query = tap(new UtmValueFilter(), static function ($filter) use ($request) {
            $filter->request = $request;
        })->run($query);

        return $query;
    }

    public function headings(): array
    {
        return [
            'order_id'                  => 'ID',
            'user_name'                 => 'Имя пользователя',
            'product_sum'               => 'Сумма заказа',
            'order_product_name'        => 'Номенклатура (наименование)',
            'order_product_count'       => 'Номенклатура (количество)',
            'order_product_sum'         => 'Номенклатура (сумма)',
            'order_product_discount'    => 'Номенклатура (скидка)',
            'order_product_proc'        => 'Номенклатура (процент)',
            'order_product_total'       => 'Номенклатура Итого',
            'promo'                     => 'Промокод/Купон',
            'status'                    => 'Статус заказа',
            'delivery_type'             => 'Способ доставки',
            'delivery_sub_type'         => 'Тип доставки',
            'payment_type'              => 'Способ оплаты',
            'user_phone'                => 'Номер телефона',
            'user_cart'                 => 'Номер карты',
            'created_at'                => 'Дата создания',
            'utm_campaign'              => 'UTM',
            'utm_info'                  => 'UTM (детализация)',
            'source'                    => 'Источник',
        ];
    }

    public function map($row): array
    {
        $product = $row;
        $order = $product->order;
        $orderItem = $product->pivot;
        /** @var Product $product */
        /** @var Order $order */

        $status = Arr::get(OrderStatusEnum::toArray(), $order->status, $order->status);
        $source = Arr::get(OrderSourceEnum::preparedForExportValues(), $order->request_from, $order->request_from);
        $deliveryType = Arr::get(DeliveryTypeEnum::toArray(), $order->delivery_type, $order->delivery_type);
        $deliverySubType = $order->preparedDeliverySubType;

        $contactPhone = $order->contacts->phone ?? ($order->user->phone ?? '');
        $contactName = $order->contacts->name ?? ($order->user->full_name ?? '');
        $userCartNumber = $order->user?->getCartNumber();

        $utmSource = $order->utm?->where('type', UtmLabelEnum::utmSource()->value)->first();
        $utmInfo = $order->utm?->pluck('value')->join('|');

        $productCount = $orderItem->count;
        $orderItemTotalWithoutDiscount = $orderItem->price * $productCount;
        $orderItemTotal = ($orderItem->price_discount ?? $orderItem->price_buy) * $productCount;

        $discountValue = $orderItemTotalWithoutDiscount - $orderItemTotal;
        $discountPercent = $discountValue > 0
            ? (int) (($discountValue / $orderItemTotalWithoutDiscount) * 100)
            : 0;

        return [
            $order->id,
            $contactName,
            $order->total_price,
            $product->title,
            $productCount,
            $orderItemTotalWithoutDiscount,
            $discountValue > 0 ? $discountValue : '-' , // Номенклатура (скидка)
            $discountPercent ? "{$discountPercent}%" : '-', // Номенклатура (процент)
            $orderItemTotal,
            $order->promo?->code, // Промокод
            $status,
            $deliveryType,
            $deliverySubType,
            $order->payment->title ?? '',
            PhoneFormatterHelper::format($contactPhone),
            $userCartNumber,
            $order->created_at,
            $utmSource?->value ?? '',
            $utmInfo,
            $source,
        ];
    }
}
