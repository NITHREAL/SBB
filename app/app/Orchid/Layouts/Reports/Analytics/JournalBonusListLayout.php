<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use App\Orchid\Helpers\TD\ID;
use Domain\Order\Models\Order;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class JournalBonusListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'analytic_journal';

    /**
     * @inheritDoc
     */
    protected function columns(): array
    {
        return array_map(function ($row) {
            if ($row instanceof TD) {
                /** @var \Orchid\Screen\TD $row */
                $row->width('');
            }
            return $row;
        }, $this->columnsRaw());
    }

    /**
     * @inheritDoc
     */
    protected function columnsRaw(): array
    {
        return [
            ID::make('completed_at', __('admin.journal.date'))
                ->render(function (Order $order) {
                    return $order->completed_at->toDateTimeString();
                }),
            ID::make('total', __('admin.journal.sum_purchase')),
            ID::make('amount_bonus', __('admin.journal.amount_bonus')),
            TD::make('number_check', __('admin.journal.number_check'))
                ->render(function (Order $order) {
                    return $order->externalCheck->data_check['@attributes']['number'] ?? null;
                }),
            // В таблицу необходимо добавить столбцы - "Номер смены", "Номер кассы", "Номер магазина" (без них выгрузка теряет смысл)
            TD::make('shift_number', 'Номер смены')
                ->render(function (Order $order) {
                    return $order->externalCheck->data_check['@attributes']['shift'] ?? null;
                }),
            TD::make('cash_number', 'Номер кассы')
                ->render(function (Order $order) {
                    return $order->externalCheck->data_check['@attributes']['cache'] ?? null;
                }),
            TD::make('shop_number', 'Номер магазина')
                ->render(function (Order $order) {
                    return $order->externalCheck->data_check['@attributes']['shop'] ?? null;
                }),
            ID::make('description_check', __('admin.journal.description_check')),
            ID::make('gift',  __('admin.journal.gift'))
                ->render(function (Order $order) {
                    return $order->externalCheck->data_check['coupons']['coupon']['@attributes']['typeGuid'] ?? null;
                }),
            TD::make('user.phone', __('admin.journal.phone'))
                ->render(function (Order $order) {
                    $user = $order->user;
                    if (empty($user?->phone)) {
                        return "Не авторизован";
                    }
                    return Link::make("+7".$user?->phone)
                        ->route('platform.systems.users.edit', $user?->id);
                }),
            TD::make('store.title', __('admin.journal.store_title'))
                ->render(function (Order $order) {
                    return $order->store?->title;
                }),
            TD::make('user.age', __('admin.user.age'))
                ->render(function (Order $order) {
                    if (is_null($order->user?->birthday)) {
                        return '';
                    } else {
                        return date_diff(date_create($order->user->birthday), date_create('today'))->y;
                    }
                }),
            ID::make('name_operation', __('admin.journal.name_operation'))
                ->render(function (Order $order) {
                    if (0 < $order->amount_bonus) {
                        return 'начисление';
                    } elseif ($order->amount_bonus < 0) {
                        return 'списание';
                    } else {
                        return null;
                    }
                }),
        ];
    }
}
