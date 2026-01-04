<?php

namespace App\Orchid\Screens\Reports\Analytics;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\Concerns\CreateExportAction;
use App\Orchid\Layouts\Reports\Analytics\JournalBonusFilterLayout;
use App\Orchid\Layouts\Reports\Analytics\JournalBonusListLayout;
use Domain\Order\Jobs\Analytics\JournalBonusExportJob;
use Domain\Order\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Orchid\Screen\Screen;

/** Отчет журнал начисления бонусов */
class JournalBonusListScreen extends Screen
{
    use CreateExportAction;

    /**
     * Display header name.
     *
     * @var string
     */
    public Application|Translator|string|array|null $name;

    public function __construct()
    {
        $this->name = __('admin.analytic.journal');
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'analytic_journal' => Order::query()
                ->with('user', 'store', 'externalCheck', 'products')
                ->where('orders.amount_bonus', '!=', 0)
                ->filtersApplySelection(JournalBonusFilterLayout::class)
                ->filters()
                ->defaultSort('completed_at', 'asc')
                ->paginate()

        ];
    }

    public function commandBar(): array
    {
        $filter = request()->all();

        return Actions::make([
            $this->actionsExportTable(JournalBonusExportJob::class, $filter, $this->name),
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
            JournalBonusFilterLayout::class,
            JournalBonusListLayout::class
        ];
    }
}
