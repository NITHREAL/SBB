<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\Chart\MainChart;
use App\Orchid\Layouts\Metric\Order\MetricOrderFilterLayout;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Domain\Order\Models\Order;
use Domain\Support\Models\SupportMessage;
use Domain\User\Models\User;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    private const NO_DATA = 'Нет данных';
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Панель администрирования приложения';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'По умолчанию данные выводятся за месяц. Для изменения периода выбора даты, используйте фильтры в верхнем меню';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $request = request();

        $start = $request->get('created_at_from')
            ? Carbon::createFromFormat('Y-m-d', $request->get('created_at_from'))->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $end = $request->get('created_at_to')
            ? Carbon::createFromFormat('Y-m-d', $request->get('created_at_to'))->endOfDay()
            : Carbon::now()->endOfDay();

        $mobileUsersQuery = User::whereHas('mobileTokens')
            ->whereBetween('created_at', [$start, $end]);

        $mobileUsersIds = $mobileUsersQuery->pluck('id')->toArray();

        $mobileOrdersQuery = Order::filtersApplySelection(MetricOrderFilterLayout::class)
            ->whereIn('user_id', $mobileUsersIds)
            ->whereBetween('created_at', [$start, $end]);

        $mobileMetrics = $this->getMetrics($mobileUsersIds, $mobileOrdersQuery);
        $ordersCharts = $this->getOrdersChart($mobileOrdersQuery->get(), $mobileUsersIds);
        $usersCharts = $this->getUserCharts($mobileUsersQuery->get());

        return [
            "mobileMetrics" => $mobileMetrics,
            "orderCharts"   => $ordersCharts,
            "userCharts"    => $usersCharts,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            MetricOrderFilterLayout::class,
            Layout::columns([
                Layout::metrics([
                    "Количество заказов в МП" => 'mobileMetrics.all_orders',
                ]),
            ]),
            Layout::columns([
                Layout::metrics([
                    'Минимальная сумма заказа' => 'mobileMetrics.min_price',
                    'Максимальная сумма заказа' => 'mobileMetrics.max_price',
                    'Средний чек заказа' => 'mobileMetrics.average_check',
                ]),
            ]),
            Layout::columns([
                MainChart::make('orderCharts', 'График заказов')
                    ->description('Данный график отображает показатели заказов за выбранный период'),
            ]),
            Layout::columns([
                Layout::metrics([
                    'Количество пользователей из МП' => 'mobileMetrics.all_users',
                    'Количество новых сообщений в поддержке из МП' => 'mobileMetrics.new_support_messages',
                ]),
            ]),
            Layout::columns([
                MainChart::make('userCharts', 'График новых пользователей')
                    ->description('Данный график отображает количество новых пользователей за выбранный период'),
            ]),
        ];
    }

    private function getMetrics($usersPluckId, $ordersQuery): array
    {
        return [
            "all_orders" => [
                'value' => $ordersQuery->count() ?: self::NO_DATA
            ],
            "all_users" => [
                'value' => count($usersPluckId) ?: self::NO_DATA
            ],
            "min_price" => [
                'value' => Order::filtersApplySelection(MetricOrderFilterLayout::class)
                        ->orderBy('total_price')
                        ->where('total_price', '>', 0)
                        ->whereIn('user_id', $usersPluckId)
                        ->first()?->total_price ?? self::NO_DATA
            ],
            "max_price" => [
                'value' => Order::filtersApplySelection(MetricOrderFilterLayout::class)
                        ->orderBy('total_price', 'desc')
                        ->whereIn('user_id', $usersPluckId)
                        ->first()?->total_price ?? self::NO_DATA
            ],
            "average_check" => [
                'value' => Order::filtersApplySelection(MetricOrderFilterLayout::class)
                    ->whereIn('user_id', $usersPluckId)
                    ->avg('total_price')
                    ? round((float)Order::filtersApplySelection(MetricOrderFilterLayout::class)
                        ->whereIn('user_id', $usersPluckId)
                        ->avg('total_price'), 2)
                    : self::NO_DATA
            ],
            "new_support_messages" => [
                'value' => SupportMessage::where("viewed", "=", false)
                    ->whereIn('user_id', $usersPluckId)
                    ->count() ?: self::NO_DATA
            ],
        ];
    }

    private function getOrdersChart($orders, $mobileUsers): array
    {
        $request = request();

        $start = $request->get('created_at_from')
            ? Carbon::createFromFormat('Y-m-d', $request->get('created_at_from'))
            : Carbon::now()->subDays(29)->startOfDay();

        $end = $request->get('created_at_to')
            ? Carbon::createFromFormat('Y-m-d', $request->get('created_at_to'))
            : Carbon::now()->endOfDay();

        $period = CarbonPeriod::create($start, $end);
        $labels = [];
        $valuesTemplate = [];

        foreach ($period as $date) {
            $labels[] = $date->translatedFormat('j M'); // "11 мар", "1 апр"
            $valuesTemplate[$date->format('Y-m-d')] = 0;
        }

        $values = ['mpOrders' => array_values($valuesTemplate)];

        $groupedOrders = $orders->groupBy(function($order) {
            return Carbon::parse($order->created_at)->format('Y-m-d');
        });

        foreach ($groupedOrders as $date => $dayOrders) {
            if (isset($valuesTemplate[$date])) {
                $index = array_search($date, array_keys($valuesTemplate));
                $values['mpOrders'][$index] = $dayOrders->count();
            }
        }

        return [
            [
                'name' => 'Заказов в МП',
                'values' => $values['mpOrders'],
                'labels' => $labels,
            ]
        ];
    }

    private function getUserCharts($mobileUsers): array
    {
        $request = request();

        $start = $request->get('created_at_from')
            ? Carbon::createFromFormat('Y-m-d', $request->get('created_at_from'))
            : Carbon::now()->subDays(29)->startOfDay();

        $end = $request->get('created_at_to')
            ? Carbon::createFromFormat('Y-m-d', $request->get('created_at_to'))
            : Carbon::now()->endOfDay();

        $period = CarbonPeriod::create($start, $end);
        $labels = [];
        $valuesTemplate = [];

        foreach ($period as $date) {
            $labels[] = $date->translatedFormat('j M'); // "11 мар", "1 апр"
            $valuesTemplate[$date->format('Y-m-d')] = 0;
        }

        $values = ['mpUsers' => array_values($valuesTemplate)];

        $groupedUsers = $mobileUsers->groupBy(function($user) {
            return Carbon::parse($user->created_at)->format('Y-m-d');
        });

        foreach ($groupedUsers as $date => $users) {
            if (isset($valuesTemplate[$date])) {
                $index = array_search($date, array_keys($valuesTemplate));
                $values['mpUsers'][$index] = $users->count();
            }
        }

        return [
            [
                'name' => 'Пользователей в МП',
                'values' => $values['mpUsers'],
                'labels' => $labels,
            ]
        ];
    }
}
