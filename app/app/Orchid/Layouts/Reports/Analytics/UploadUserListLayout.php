<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use App\Orchid\Helpers\TD\ID;
use Domain\User\Models\User;
use Illuminate\Support\Carbon;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UploadUserListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'analytic_unload';

    protected function columns(): array
    {
        return [
            ID::make('id', __('admin.user.id'))->width(''),
            TD::make('phone', __('admin.user.login'))
                ->sort()
                ->cantHide()
                ->render(function (User $user) {
                    return $user->presenter()->phoneNumber();
                })->width(''),
            ID::make('email', __('admin.user.email'))->width(''),
            ID::make('full_name', __('admin.user.full_name'))->width(''),
            ID::make('birthday', __('admin.user.birthday'))->width(''),
            TD::make('age', __('admin.user.age'))
                ->render(function (User $user) {
                    return !is_null($user->birtday)
                        ? date_diff(date_create($user->birthday), date_create('today'))->y
                        : null;
                }),
            ID::make('', __('admin.user.sex')),
            TD::make('', __('admin.user.frequency_visits'))
                ->render(function (User $user) {
                    //получаем количество месяцев жизни пользователя в системе лояльности
                    $createdAt = Carbon::parse($user->created_at?->toDateString())->floorMonth();
                    $currentMonth = Carbon::now()->floorMonth();
                    $countMonths = $createdAt->diffInMonths($currentMonth) + 1;

                    return round($user->count_purchases / $countMonths, 2);
                }),
            TD::make('average_check', __('admin.activity.average_check'))
                ->render(function (User $user) {
                    $result = 0;

                    if(0 < $user->count_purchases) {
                        $result = round($user->sum_purchase / $user->count_purchases, 2);
                    }

                    return $result;
                }),
            ID::make('sum_purchase', __('admin.user.sum_purchase')),
            ID::make('amount_bonus', __('admin.journal.amount_bonus')),
            ID::make('', __('admin.user.group')),
            TD::make('percent', __('admin.user.percent'))
                ->render(function () {
                    return 2;
                }),
            TD::make('created_at', __('admin.user.created_at'))
                ->sort()
                ->render(function (User $user) {
                    return $user->created_at?->toDateTimeString();
                }),
            ID::make('date_first', __('admin.user.date_first')),
        ];
    }
}
