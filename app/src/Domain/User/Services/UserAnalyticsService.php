<?php

namespace Domain\User\Services;

use Domain\User\Models\User;
use Illuminate\Support\Collection;

class UserAnalyticsService
{
    public function getUsersData(array $userIds): Collection
    {
        $data = User::query()
            ->selectRaw('users.id,
                    COALESCE(SUM(op.total), 0) + COALESCE(SUM(o.delivery_cost), 0) - COALESCE(SUM(o.discount), 0) as sum_purchase,
                    COUNT(o.id) as count_purchases,
                    SUM(o.amount_bonus) as amount_bonus,
                    MIN(o.completed_at) as date_first
                ')
            ->leftJoin('orders as o', 'users.id',  '=', 'o.user_id')
            ->leftJoin('order_product as op', 'o.id', '=', 'op.order_id')
            ->groupBy('users.id')
            ->whereIn('users.id', $userIds)
            ->get()
            ->keyBy('id');

        $users = $data->fresh();
        $data = $data->toArray();

        $users->map(function ($user) use ($data) {
            $row = $data[$user->id] ?? [];
            $user->setRawAttributes(array_merge($user->getAttributes(), $row));
        });

        return $users;
    }
}
