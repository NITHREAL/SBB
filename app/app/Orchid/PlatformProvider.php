<?php

namespace App\Orchid;

use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Users')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),

            Menu::make(__('admin.audience.title'))
                ->icon('book-open')
                ->permission(['content', 'platform.audience.list'])
                ->route('platform.audiences.list'),

            Menu::make(__('admin.notifications.internal_notifications'))
                ->icon('bell')
                ->route('platform.mass_notifications')
                ->permission(['content', 'platform.mass_notifications'])
                ->title(__('admin.notifications.user_notification_screen')),

            Menu::make(__('admin.notifications.push_notifications'))
                ->icon('bell')
                ->route('platform.push_notifications')
                ->permission(['content', 'platform.push_notifications']),

            Menu::make(__('admin.support'))
                ->icon('phone')
                ->route('platform.support')
                ->permission(['content', 'platform.support'])
                ->class('nav-link d-flex align-items-center collapsed support-menu')
                ->title(__('admin.support')),

            Menu::make(__('admin.products'))
                ->icon('docs')
                ->permission(['content', 'platform.products.list'])
                ->route('platform.products.list')
                ->title(__('admin.product_catalog')),

            Menu::make(__('admin.categories'))
                ->icon('folder-alt')
                ->permission(['content', 'platform.categories.list'])
                ->route('platform.categories.list'),

            Menu::make(__('admin.groups'))
                ->icon('grid')
                ->permission(['content', 'platform.groups.list'])
                ->route('platform.groups.list'),

            Menu::make(__('admin.popular_products'))
                ->icon('grid')
                ->permission(['content', 'platform.popular-products'])
                ->route('platform.popular-products'),

            Menu::make(__('admin.forgotten_products'))
                ->icon('grid')
                ->permission(['content', 'platform.forgotten-products'])
                ->route('platform.forgotten-products'),

            Menu::make(__('admin.week_products'))
               ->icon('grid')
                ->permission(['content', 'platform.week-products'])
               ->route('platform.week-products'),

            Menu::make(__('admin.recommended_products'))
                ->icon('grid')
                ->permission(['content', 'platform.recommended-products'])
                ->route('platform.recommended-products'),

//            Menu::make(__('admin.expected_products'))
//                ->icon('docs')
//                ->permission(['content', 'platform.expected-products.list'])
//                ->route('platform.expected-products.list'),

            Menu::make(__('admin.stores'))
                ->icon('building')
                ->route('platform.stores.list')
                ->permission(['content', 'platform.stores.list'])
                ->title(__('admin.stores_and_geography')),

            Menu::make(__('admin.regions'))
                ->icon('map')
                ->permission(['content', 'platform.regions.list'])
                ->route('platform.regions.list'),

            Menu::make(__('admin.cities'))
                ->icon('location-pin')
                ->permission(['content', 'platform.cities.list'])
                ->route('platform.cities.list'),

            Menu::make(__('admin.polygon_types'))
                ->icon('layers')
                ->permission(['content', 'platform.polygon_types.list'])
                ->route('platform.polygon_types.list'),

            // Магазин
            Menu::make(__('admin.orders'))
                ->icon('basket-loaded')
                ->route('platform.orders.list')
                ->permission(['content', 'platform.orders.list'])
                ->title(__('admin.orders_and_payments')),

            Menu::make(__('admin.payment_types'))
                ->icon('friends')
                ->permission(['content', 'platform.payment_types.list'])
                ->route('platform.payment_types.list'),

            Menu::make(__('admin.questions_answers'))
                ->icon('book-open')
                ->permission(['content', 'platform.faq-categories.list'])
                ->route('platform.faq-categories.list')
                ->title(__('admin.customer_service')),

            Menu::make(__('admin.reviews'))
                ->icon('star')
                ->permission(['review', 'platform.reviews.list'])
                ->route('platform.reviews.list'),

            Menu::make(__('admin.tags'))
                ->icon('layers')
                ->route('platform.tags.list')
                ->permission(['content', 'platform.tags.list'])
                ->title(__('admin.miscellaneous')),

            Menu::make(__('admin.promos'))
                ->icon('rub')
                ->permission(['content', 'platform.promos.list'])
                ->route('platform.promos.list'),

            Menu::make(__('admin.promotional campaigns'))
                ->icon('rub')
                ->permission(['content', 'platform.promo-actions.list'])
                ->route('platform.promo-actions.list'),

            Menu::make(__('admin.coupon.coupons'))
                ->icon('docs')
                ->permission(['content', 'platform.coupons.category.list'])
                ->route('platform.coupons.category.list'),

            Menu::make(__('admin.raffles'))
                ->icon('book-open')
                ->permission(['content', 'platform.lotteries.list'])
                ->route('platform.lotteries.list'),

            Menu::make(__('admin.stories'))
                ->icon('layers')
                ->permission(['content', 'platform.stories.list'])
                ->route('platform.stories.list'),

            Menu::make(__('admin.settings.name'))
                ->icon('book-open')
                ->route('platform.settings')
                ->permission(['content', 'platform.settings'])
                ->title(__('admin.settings.name')),

            Menu::make(__('admin.mobile_version.list'))
                ->icon('modules')
                ->permission(['content', 'platform.mobile-versions.list'])
                ->route('platform.mobile-versions.list'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }

    /**
     * @return string[]
     */
    public function registerSearchModels(): array
    {
        return [
            // ...Models
            // \App\Models\User::class
        ];
    }
}
