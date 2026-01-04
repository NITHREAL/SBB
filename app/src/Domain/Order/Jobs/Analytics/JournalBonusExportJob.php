<?php

namespace Domain\Order\Jobs\Analytics;

use App\Orchid\Layouts\Reports\Analytics\JournalBonusFilterLayout;
use Domain\Order\Models\Order;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Infrastructure\Export\BaseExportJob;
use Infrastructure\Notifications\Admin\ExportJobCompletedNotification;

class JournalBonusExportJob extends BaseExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800;

    public function __construct($filter = [], ?User $user = null, $attr = [])
    {
        parent::__construct($filter, $user, $attr);
        $this->queue = 'low';
    }

    public function collection($resource): void
    {
        $count = Order::query()
            ->select('id')
            ->where('orders.amount_bonus', '!=', 0)
            ->filtersApplySelection(JournalBonusFilterLayout::class)
            ->filters()
            ->count();

        if ($count > 50000) {
            $msg = sprintf(
                'Выгрузка %s записей. Приблизителбно сформируется через %d минут',
                $count,
                (int)$count / 50000,
            );
            $this->user?->notify(new ExportJobCompletedNotification($msg));
        }

        Order::query()
            ->select('id')
            ->where('orders.amount_bonus', '!=', 0)
            ->filtersApplySelection(JournalBonusFilterLayout::class)
            ->filters()
            ->defaultSort('id', 'asc')
            ->chunk($this->chunk, function (Collection $rows) use ($resource) {
                $data = Order::query()
                    ->whereIn('id', $rows->pluck('id'))
                    ->with('user', 'store', 'externalCheck', 'products')
                    ->get()
                    ->keyBy('id');

                $data->each(function ($order) use ($resource) {
                    fputcsv($resource, $this->map($order), "\t");
                });
            });
    }

    public function headings(): array
    {
        return [
            'completed_at'      => 'Дата',
            'total'             => 'Сумма покупки',
            'amount_bonus'      => 'Количество бонусов',
            'number_check'      => 'Номер чека',
            'shift_number'      => 'Номер смены',
            'cash_number'       => 'Номер кассы',
            'shop_number'       => 'Номер магазина',
            'description_check' => 'Примечание к чеку',
            'gift'              => 'Подарок',
            'user_phone'        => 'Телефон клиента',
            'store_title'       => 'Название филиала',
            'user_age'          => 'Возраст',
            'name_operation'    => 'Операция',

        ];
    }

    public function map($row): array
    {
        /** @var Order $order */
        $order = $row;
        $phone = $order->user?->phone;
        $phone = $phone ? '+7' . $phone : "Не авторизован";
        $age = $order->user?->birthday ? date_diff(date_create($order->user->birthday), date_create('today'))->y : '';
        $name_operation = (0 < $order->amount_bonus) ? 'начисление' : (($order->amount_bonus < 0) ? 'списание' : '');
        return [
            'completed_at'      => $order->completed_at->toDateTimeString(),
            'total'             => $order->total,
            'amount_bonus'      => $order->amount_bonus,
            'number_check'      => $order->externalCheck->data_check['@attributes']['number'] ?? null,
            'shift_number'      => $order->externalCheck->data_check['@attributes']['shift'] ?? null,
            'cash_number'       => $order->externalCheck->data_check['@attributes']['cache'] ?? null,
            'shop_number'       => $order->externalCheck->data_check['@attributes']['shop'] ?? null,
            'description_check' => $order->description_check ?? null,
            'gift'              => $order->externalCheck->data_check['coupons']['coupon']['@attributes']['typeGuid'] ?? null,
            'user_phone'        => $phone,
            'store_title'       => $order->store?->title,
            'user_age'          => $age,
            'name_operation'    => $name_operation,

        ];
    }
}
