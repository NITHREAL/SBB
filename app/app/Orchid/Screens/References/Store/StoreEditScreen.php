<?php

namespace App\Orchid\Screens\References\Store;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\References\Store\StoreCoordsEditLayout;
use App\Orchid\Layouts\References\Store\StoreInfoEditLayout;
use App\Orchid\Layouts\References\Store\StorePaymentTypesEditLayout;
use App\Orchid\Layouts\References\Store\StorePolygonsEditLayout;
use App\Orchid\Layouts\References\Store\StoreScheduleEditLayout;
use App\Orchid\Screens\References\Store\Services\StoreOrchidService;
use Domain\Order\Models\Delivery\Polygon;
use Domain\Store\Models\Store;
use Domain\Store\Requests\Admin\StoreRequest;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class StoreEditScreen extends Screen
{
    private Store $store;

    public function __construct(
        private StoreOrchidService $service,
    ) {
    }

    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Добавление магазина';

    /**
     * Query data.
     *
     * @param Store $store
     * @return array
     */
    public function query(Store $store): array
    {
        if ($store->exists) {
            $store->load([
                'contacts',
                'polygons.deliveryPrices',
                'metaTagValues',
                'scheduleWeekdays',
                'scheduleDates',
                'payments'
            ]);

            $this->name = $store->title;
        }

        $this->otherPolygons = Polygon::query()
            ->with('store')
            ->whereHas('store', fn ($query) =>
            $query->where('city_id', $store->city_id))
            ->where('store_system_id', '!=', $store->getAttribute('system_id'))
            ->get();
        $this->store = $store;

        return [
            'store' => $store
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->store)
        ]);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                __('admin.store.info') => [
                    StoreInfoEditLayout::class,
                    StoreCoordsEditLayout::class,
                    StorePolygonsEditLayout::class
                ],
                __('admin.store.schedule') => StoreScheduleEditLayout::class,
                __('admin.payment_types') => StorePaymentTypesEditLayout::class,
            ])
        ];
    }

    /**
     * @param Store $store
     * @param StoreRequest $request
     * @return void
     */
    public function save(Store $store, StoreRequest $request): void
    {
        $this->service->saveStore($store, $request);
        Toast::success(__('admin.toasts.store.updated', ['store' => $store->title]));
    }
}
