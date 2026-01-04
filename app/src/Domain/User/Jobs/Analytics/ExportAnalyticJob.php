<?php

namespace Domain\User\Jobs\Analytics;

use App\Orchid\Layouts\Reports\Analytics\UploadUserFilterLayout;
use Domain\User\Models\User;
use Domain\User\Services\UserAnalyticsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Infrastructure\Export\BaseExportJob;
use Infrastructure\Helpers\PhoneFormatterHelper;

class ExportAnalyticJob extends BaseExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800;

    protected int $chunk = 300;

    private UserAnalyticsService $userAnalyticsService;

    public function __construct($filter = [], ?User $user = null)
    {
        parent::__construct($filter, $user, []);
        $this->queue = 'low';
        $this->userAnalyticsService = app()->make(UserAnalyticsService::class);
    }

    public function collection($resource): void
    {
        User::query()
            ->selectRaw('users.id')
            ->groupBy('users.id')
            ->filtersApplySelection(UploadUserFilterLayout::class)
            ->filters()
            ->defaultSort('id', 'asc')
            ->chunk($this->chunk, function (Collection $rows) use ($resource) {
                $preparedUsers = $this->userAnalyticsService->getUsersData(
                    $rows->pluck('id')->toArray()
                );

                foreach ($preparedUsers as $user) {
                    fputcsv($resource, $this->map($user), "\t");
                }
            });
    }

    public function headings(): array
    {
        return [
            'id'                => __('admin.user.id'),
            'phone'             => __('admin.user.login'),
            'email'             => __('admin.user.email'),
            'full_name'         => __('admin.user.full_name'),
            'birthday'          => __('admin.user.birthday'),
            'age'               => __('admin.user.age'),
            'sex'               => __('admin.user.sex'),
            'frequency_visits'  => __('admin.user.frequency_visits'),
            'average_check'     => __('admin.activity.average_check'),
            'sum_purchase'      => __('admin.user.sum_purchase'),
            'amount_bonus'      => __('admin.journal.amount_bonus'),
            'group'             => __('admin.user.group'),
            'percent'           => __('admin.user.percent'),
            'created_at'        => __('admin.user.created_at'),
            'date_first'        => __('admin.user.date_first'),
        ];
    }

    public function map($row): array
    {
        /** @var User $user */
        $user = $row;

        $age = $user->birthday
            ? date_diff(date_create($user->birthday), date_create('today'))->y
            : null;

        //получаем количество месяцев жизни пользователя в системе лояльности
        $createdAt = $user->created_at->floorMonth();
        $currentMonth = Carbon::now()->floorMonth();
        $countMonths = $createdAt->diffInMonths($currentMonth) + 1;

        $frequencyVisits = round($user->count_purchases / $countMonths, 2);

        $averageCheck = $user->count_purchases
            ? round($user->sum_purchase / $user->count_purchases, 2)
            : 0;

        $percent = 2;

        return [
            $user->id,
            PhoneFormatterHelper::format($user?->phone),
            $user->email,
            $user->full_name,
            $user->birthday,
            $age,
            $user->sex,
            $frequencyVisits,
            $averageCheck,
            $user->sum_purchase,
            $user->amount_bonus,
            $user->group,
            $percent,
            $user->created_at,
            $user->date_first,
        ];
    }
}
