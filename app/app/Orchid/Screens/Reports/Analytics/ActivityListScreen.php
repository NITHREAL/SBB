<?php

namespace App\Orchid\Screens\Reports\Analytics;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\Concerns\CreateExportAction;
use App\Orchid\Layouts\Reports\Analytics\ActivityFilterLayout;
use App\Orchid\Layouts\Reports\Analytics\ActivityListLayout;
use Domain\Store\Jobs\ActivityStoreExportJob;
use Domain\Store\Services\AnalyticActivityStoreService;
use Orchid\Screen\Screen;

/** Отчет активность торговых точек */
class ActivityListScreen extends Screen
{
    use CreateExportAction;

    /**
     * Display header name.
     *
     * @var string
     */
    public string $name;

    public function __construct(
        private readonly AnalyticActivityStoreService $analyticActivityStoreService,
    ) {
        $this->name = __('admin.analytic.activity');
    }

    /**
     * Query data.
     */
    public function query(): array
    {
        $dateFilter = request()->get('created_at', []);

        return [
            'analytic_activity' => $this->analyticActivityStoreService->getAnalyticActivityStorePaginatedData($dateFilter)
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        $filter = request()->all();

        return Actions::make([
            $this->actionsExportTable(ActivityStoreExportJob::class, $filter, $this->name),
        ]);
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ActivityFilterLayout::class,
            ActivityListLayout::class,
        ];
    }
}
