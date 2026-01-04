<?php

namespace Domain\Store\Services;

use App\Orchid\Layouts\Reports\Analytics\ActivityFilterLayout;
use Domain\Store\Models\AnalyticActivityStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AnalyticActivityStoreService
{
    public function getAnalyticActivityStoreData(array $dateFilter): Collection
    {
        return $this->getAnalyticActivityStoreDataQuery($dateFilter)->get();
    }

    public function getAnalyticActivityStorePaginatedData(array $dateFilter): LengthAwarePaginator
    {
        return $this->getAnalyticActivityStoreDataQuery($dateFilter)->paginate();
    }

    private function getAnalyticActivityStoreDataQuery(array $dateFilter): Builder
    {
        $dateReportStart = $dateFilter['start'] ?? '1970-01-01';
        $dateReportEnd = $dateFilter['end'] ?? Carbon::now()->format('Y-m-d');
        $dateReportEndPlusOne = Carbon::createFromFormat('Y-m-d', $dateReportEnd)
            ->addDay()
            ->format('Y-m-d');

        return AnalyticActivityStore::query()
            ->with('store')
            ->selectRaw(
                '
                    store_id,
                    COALESCE(SUM(number_sales), 0) as count_sales,
                    COALESCE(SUM(sum_sales), 0) as sum_sales,
                    COALESCE(SUM(average_check), 0) as average_check,
                    COALESCE(SUM(accrued_points), 0) as accrued_points,
                    COALESCE(SUM(deducted_points), 0) as deducted_points,
                    COALESCE(SUM(amount_gifts), 0) as amount_gifts,
                    COALESCE(SUM(new_users), 0) as new_users
                '
            )
            ->where('date_activity', '>=', $dateReportStart)
            ->where('date_activity', '<', $dateReportEndPlusOne)
            ->groupBy('store_id')
            ->filtersApplySelection(ActivityFilterLayout::class)
            ->filters();
    }
}
