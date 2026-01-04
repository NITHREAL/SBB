<?php

namespace Domain\Store\Jobs;

use Domain\Store\Services\AnalyticActivityStoreService;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Infrastructure\Export\BaseExportJob;

class ActivityStoreExportJob extends BaseExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private AnalyticActivityStoreService $activityStoreService;

    public function __construct($filter = [], ?User $user = null, $attr = [])
    {
        parent::__construct($filter, $user, $attr);

        $this->activityStoreService = app()->make(AnalyticActivityStoreService::class);
    }

    public function collection($resource): void
    {
        $dateFilter = $this->filter['created_at'] ?? [];

        $data = $this->activityStoreService->getAnalyticActivityStoreData($dateFilter);

        $data->each(function ($row) use ($resource) {
            fputcsv($resource, $this->map($row), "\t");
        });
    }

    public function headings(): array
    {
        return [
            'store_id'          => 'ID',
            'store.title'       => 'Торговая точка',
            'count_sales'       => 'Продажи',
            'sum_sales'         => 'Сумма продаж',
            'average_check'     => 'Средний чек',
            'accrued_points'    => 'Начисленные баллы',
            'deducted_points'   => 'Списанные баллы',
            'amount_gifts'      => 'Выдано подарков',
            'new_users'         => 'Новые клиенты',

        ];
    }

    public function map($row): array
    {
        return [
            'store_id'          => $row->store_id,
            'store.title'       => $row->store->title ?? null,
            'count_sales'       => $row->count_sales,
            'sum_sales'         => $row->sum_sales,
            'average_check'     => $row->average_check,
            'accrued_points'    => $row->accrued_points,
            'deducted_points'   => $row->deducted_points,
            'amount_gifts'      => $row->amount_gifts,
            'new_users'         => $row->new_users,
        ];
    }
}
