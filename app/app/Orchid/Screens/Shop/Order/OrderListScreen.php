<?php

namespace App\Orchid\Screens\Shop\Order;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\Concerns\CreateExportAction;
use App\Orchid\Layouts\Shop\Order\OrderFilterLayout;
use App\Orchid\Layouts\Shop\Order\OrderListLayout;
use Domain\Order\Jobs\OrderExportJob;
use Domain\Order\Models\Order;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class OrderListScreen extends Screen
{
    use CreateExportAction;

    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список заказов';

    public function name(): string
    {
        return 'Список заказов';
    }

    public function commandBar(): array
    {
        $filter = request()->all();

        return Actions::make([
            $this->actionsExportTable(
                OrderExportJob::class,
                $filter,
                $this->name
            ),
        ]);
    }

    public function createExport(Request $request): void
    {
        $filter = $request->get('filter', []);

        $user = auth()->user();

        OrderExportJob::dispatch($user, $filter);

        Alert::info(
            'Формирование отчета началось. Вы получите оповещение по завершению'
        );
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $sort = request('sort', 'id');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $query = Order::filtersApplySelection(OrderFilterLayout::class)
            ->select('orders.*')
            ->with('user', 'contacts')
            ->whereNotOffline()
            ->filters();

        if ($column === 'contacts.phone') {
            $query->leftJoin('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                ->orderBy('order_contacts.phone', $direction);
        }

        elseif ($column === 'user.full_name') {
            $orders = $query->paginate();

            $sorted = $orders->getCollection()->sortBy(function ($order) {
                return $order->user->full_name ?? '';
            }, SORT_REGULAR, $direction === 'desc');

            $orders->setCollection($sorted);
            return ['orders' => $orders];
        }

        else {
            $query->orderBy($column, $direction);
        }

        return ['orders' => $query->paginate()];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            OrderFilterLayout::class,
            OrderListLayout::class
        ];
    }
}
