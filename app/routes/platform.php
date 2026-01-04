<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\SupportController;
use App\Orchid\Screens\Bonus\BonusLevelListScreen;
use App\Orchid\Screens\Content\MainSlider\MainSlideEditScreen;
use App\Orchid\Screens\Content\MainSlider\MainSlidesListScreen;
use App\Orchid\Screens\Content\SecondSlider\SecondSlideEditScreen;
use App\Orchid\Screens\Content\SecondSlider\SecondSlidesListScreen;
use App\Orchid\Screens\Content\Story\StoryEditScreen;
use App\Orchid\Screens\Content\Story\StoryListScreen;
use App\Orchid\Screens\Content\Story\StoryPageEditScreen;
use App\Orchid\Screens\Content\Tag\TagEditScreen;
use App\Orchid\Screens\Content\ThirdSlider\ThirdSlideEditScreen;
use App\Orchid\Screens\Content\ThirdSlider\ThirdSlidesListScreen;
use App\Orchid\Screens\Faq\FaqCategory\FaqCategoryEditScreen;
use App\Orchid\Screens\Faq\FaqCategory\FaqCategoryListScreen;
use App\Orchid\Screens\Faq\FaqCategory\FaqQuestionEditScreen;
use App\Orchid\Screens\FarmerCounter\FarmerCounterEditScreen;
use App\Orchid\Screens\FarmerCounter\FarmerCounterListScreen;
use App\Orchid\Screens\Feedback\FeedbackListScreen;
use App\Orchid\Screens\Feedback\FeedbackShowScreen;
use App\Orchid\Screens\Mobile\MobileVersionEditScreen;
use App\Orchid\Screens\Mobile\MobileVersionListScreen;
use App\Orchid\Screens\Notifications\AdminNotificationScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\References\City\CityEditScreen;
use App\Orchid\Screens\References\City\CityListScreen;
use App\Orchid\Screens\References\LegalEntity\LegalEntityEditScreen;
use App\Orchid\Screens\References\LegalEntity\LegalEntityListScreen;
use App\Orchid\Screens\References\PaymentType\PaymentTypeEditScreen;
use App\Orchid\Screens\References\PaymentType\PaymentTypeListScreen;
use App\Orchid\Screens\References\PolygonType\PolygonTypeEditScreen;
use App\Orchid\Screens\References\PolygonType\PolygonTypeListScreen;
use App\Orchid\Screens\References\Region\RegionEditScreen;
use App\Orchid\Screens\References\Region\RegionListScreen;
use App\Orchid\Screens\References\Store\StoreEditScreen;
use App\Orchid\Screens\References\Store\StoreListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Seo\Page\PageEditScreen;
use App\Orchid\Screens\Seo\Page\PageListScreen;
use App\Orchid\Screens\Setting\SettingEditScreen;
use App\Orchid\Screens\Setting\SettingListScreen;
use App\Orchid\Screens\Shop\Category\CategoryListScreen;
use App\Orchid\Screens\Shop\Category\CategoryShowScreen;
use App\Orchid\Screens\Shop\Coupon\CouponCategoryEditScreen;
use App\Orchid\Screens\Shop\Coupon\CouponCategoryListScreen;
use App\Orchid\Screens\Shop\ExpectedProduct\ExpectedProductListScreen;
use App\Orchid\Screens\Shop\Farmer\FarmerListScreen;
use App\Orchid\Screens\Shop\Farmer\FarmerShowScreen;
use App\Orchid\Screens\Shop\FarmerQuestionnaire\FarmerQuestionnaireListScreen;
use App\Orchid\Screens\Shop\FarmerQuestionnaire\FarmerQuestionnaireShowScreen;
use App\Orchid\Screens\Shop\ForgottenProduct\ForgottenProductListScreen;
use App\Orchid\Screens\Shop\Group\GroupEditScreen;
use App\Orchid\Screens\Shop\Group\GroupListScreen;
use App\Orchid\Screens\Content\Tag\TagListScreen;
use App\Orchid\Screens\Shop\Lottery\LotteryEditScreen;
use App\Orchid\Screens\Shop\Lottery\LotteryListScreen;
use App\Orchid\Screens\Shop\Order\OrderEditScreen;
use App\Orchid\Screens\Shop\Order\OrderListScreen;
use App\Orchid\Screens\Shop\Order\Payment\PaymentLogListScreen;
use App\Orchid\Screens\Shop\PopularProduct\PopularProductListScreen;
use App\Orchid\Screens\Shop\WeekProduct\WeekProductListScreen;
use App\Orchid\Screens\Shop\Product\ProductEditScreen;
use App\Orchid\Screens\Shop\Product\ProductListScreen;
use App\Orchid\Screens\Shop\Promo\PromoEditScreen;
use App\Orchid\Screens\Shop\Promo\PromoListScreen;
use App\Orchid\Screens\Shop\PromoAction\PromoActionEditScreen;
use App\Orchid\Screens\Shop\PromoAction\PromoActionListScreen;
use App\Orchid\Screens\Shop\RecommendedProduct\RecommendedProductListScreen;
use App\Orchid\Screens\Shop\Review\ReviewEditScreen;
use App\Orchid\Screens\Shop\Review\ReviewListScreen;
use App\Orchid\Screens\Support\SupportChatScreen;
use App\Orchid\Screens\Support\SupportScreen;
use App\Orchid\Screens\User\MassNotificationScreen;
use App\Orchid\Screens\User\PushNotificationScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserNotificationScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Tag\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users.')
            ->push(__('Notifications'), route('users/{user}/edit', ['user' => $user]));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{roles}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });


// Platform > System > Users
Route::screen('users/{user}/notification', UserNotificationScreen::class)
    ->name('platform.systems.user.notify')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users.edit', $user)
            ->push(__('Notifications'), route('platform.systems.user.notify', ['user' => $user]));
    });

if (config('platform.notifications.enabled', true)) {
    Route::screen('notifications/{id?}', AdminNotificationScreen::class)
        ->name('platform.notifications')
        ->breadcrumbs(fn (Trail $trail) => $trail->parent('platform.index')
            ->push(__('Notifications')));
}

/**
 * Farmer counter
 */
Route::screen('farmer-counter/{farmerCounter}/edit', FarmerCounterEditScreen::class)
    ->name('platform.farmer-counter.edit')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.farmer-counter.list')
            ->push('Счетчик');
    });

Route::screen('farmer-counter/create', FarmerCounterEditScreen::class)
    ->name('platform.farmer-counter.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.farmer-counter.list')
            ->push('Добавление счетчика');
    });

Route::screen('farmer-counter', FarmerCounterListScreen::class)
    ->name('platform.farmer-counter.list')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('admin.farmer_counters'), route('platform.farmer-counter.list'));
    });
/**************************************************************/

Route::middleware(['access:platform.systems.users'])->group(function () {
    Route::screen('users/{user}/edit', UserEditScreen::class)
        ->name('platform.systems.users.edit')
        ->breadcrumbs(function (Trail $trail, $user) {
            return $trail
                ->parent('platform.systems.users')
                ->push(__('Edit'), route('platform.systems.users.edit', ['user' => $user]));
        });
    Route::post('users/{user}/edit/saveBonus', [UserEditScreen::class, 'saveBonus']);
    Route::post('users/{user}/edit/saveCoupon', [UserEditScreen::class, 'saveCoupon']);

    Route::screen('users/create', UserEditScreen::class)
        ->name('platform.systems.users.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.systems.users')
                ->push(__('Create'), route('platform.systems.users.create'));
        });

    Route::screen('users', UserListScreen::class)
        ->name('platform.systems.users')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('Users'), route('platform.systems.users'));
        });

    Route::screen('bonuslevels', BonusLevelListScreen::class)
        ->name('platform.bonuslevels.list');
});

Route::middleware(['access:platform.systems.roles'])->group(function () {
    Route::screen('roles/{roles}/edit', RoleEditScreen::class)
        ->name('platform.systems.roles.edit')
        ->breadcrumbs(function (Trail $trail, $role) {
            return $trail
                ->parent('platform.systems.roles')
                ->push(__('Role'), route('platform.systems.roles.edit', $role));
        });

    Route::screen('roles/create', RoleEditScreen::class)
        ->name('platform.systems.roles.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.systems.roles')
                ->push(__('Create'), route('platform.systems.roles.create'));
        });

    Route::screen('roles', RoleListScreen::class)
        ->name('platform.systems.roles')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('Roles'), route('platform.systems.roles'));
        });
});

/** Coupons */

Route::screen('coupon/categories/create', CouponCategoryEditScreen::class)
    ->name('platform.coupons.category.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.coupons.category.list')
            ->push('Добавление категории');
    });

Route::screen('coupon/categories/{id}/edit', CouponCategoryEditScreen::class)
    ->name('platform.coupons.category.edit')
    ->breadcrumbs(function (Trail $trail, $id) {
        return $trail
            ->parent('platform.coupons.category.list')
            ->push(__('admin.coupon.categories.edit'), route('platform.coupons.category.list', ['id' => $id]));
    });

Route::screen('coupon/categories', CouponCategoryListScreen::class)
    ->name('platform.coupons.category.list')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('admin.coupon.categories.list'), route('platform.coupons.category.list'));
    });

/** Feedback */
Route::middleware(['access:feedback'])->prefix('feedbacks')->name('platform.feedbacks.')->group(function () {
    Route::screen('', FeedbackListScreen::class)
        ->name('list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.feedbacks'), route('platform.feedbacks.list'));
        });

    Route::screen('{feedback}/show', FeedbackShowScreen::class)
        ->name('show')
        ->breadcrumbs(function (Trail $trail, Feedback $feedback) {
            return $trail
                ->parent('platform.feedbacks.list')
                ->push('№ ' . $feedback->id);
        });
});
/**************************************************************/

Route::middleware(['access:review'])->group(function () {
    /** Reviews */
    Route::screen('reviews', ReviewListScreen::class)
        ->name('platform.reviews.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.reviews'), route('platform.reviews.list'));
        });

    Route::screen('reviews/{review}/edit', ReviewEditScreen::class)
        ->name('platform.reviews.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.reviews.list')
                ->push('Отзыв');
        });
    /**************************************************************/
});

Route::middleware(['access:content'])->group(callback: function () {

    /** Stories */
    Route::screen('stories/{id}/edit', StoryEditScreen::class)
        ->name('platform.stories.edit')
        ->breadcrumbs(function (Trail $trail, $id) {
            return $trail
                ->parent('platform.stories.list')
                ->push('Редактирование истории', route('platform.stories.edit', ['id' => $id]));
        });

    Route::screen('stories/create', StoryEditScreen::class)
        ->name('platform.stories.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.stories.list')
                ->push('Добавление истории');
        });

    Route::screen('stories', StoryListScreen::class)
        ->name('platform.stories.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Истории', route('platform.stories.list'));
        });
    Route::screen('stories/{parent_id}/page/{id}/edit', StoryPageEditScreen::class)
        ->name('platform.stories.pages.edit')
        ->breadcrumbs(function (Trail $trail, string $parent_id) {
            return $trail
                ->parent('platform.stories.edit', $parent_id)
                ->push('Страница истории');
        });
    Route::screen('stories/{parent_id}/page/create', StoryPageEditScreen::class)
        ->name('platform.stories.pages.create')
        ->breadcrumbs(function (Trail $trail, $parent_id) {
            return $trail
                ->parent('platform.stories.list')
                ->push('Добавление страницы');
        });

    /** Cities */
    Route::screen('cities/{city}/edit', CityEditScreen::class)
        ->name('platform.cities.edit')
        ->breadcrumbs(function (Trail $trail, $city) {
            $cityName = $city->title ?? 'Город';
            return $trail
                ->parent('platform.cities.list')
                ->push($cityName);
        });

    Route::screen('cities/create', CityEditScreen::class)
        ->name('platform.cities.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.cities.list')
                ->push('Добавление города');
        });

    Route::screen('cities', CityListScreen::class)
        ->name('platform.cities.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Города', route('platform.cities.list'));
        });
    /**************************************************************/

    /** Categories */
    Route::screen('categories', CategoryListScreen::class)
        ->name('platform.categories.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.categories'), route('platform.categories.list'));
        });

    Route::screen('categories/{category}/show', CategoryShowScreen::class)
        ->name('platform.categories.show')
        ->breadcrumbs(function (Trail $trail,$category) {
            return $trail
                ->parent('platform.categories.list')
                ->push($category->title);
        });
    /**************************************************************/

    /** Products */
    Route::screen('products', ProductListScreen::class)
        ->name('platform.products.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.products'), route('platform.products.list'));
        });

    Route::screen('products/{product}/edit', ProductEditScreen::class)
        ->name('platform.products.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.products.list')
                ->push('Товар');
        });
    /**************************************************************/


    /** ExpectedProducts */
    Route::screen('expected_products', ExpectedProductListScreen::class)
        ->name('platform.expected-products.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.expected_products'), route('platform.expected-products.list'));
        });
    /**************************************************************/


    /** Farmers */
    Route::screen('farmers', FarmerListScreen::class)
        ->name('platform.farmers.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.farmers'), route('platform.farmers.list'));
        });

    Route::screen('farmers/{farmer}/show', FarmerShowScreen::class)
        ->name('platform.farmers.show')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.farmers.list')
                ->push('Фермер');
        });
    /**************************************************************/

    /** Groups */
    Route::screen('groups', GroupListScreen::class)
        ->name('platform.groups.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.groups'), route('platform.groups.list'));
        });

    Route::screen('groups/{group}/edit', GroupEditScreen::class)
        ->name('platform.groups.edit')
        ->breadcrumbs(function (Trail $trail, $group) {
            $groupName = $group->title ?? 'Подборка';
            return $trail
                ->parent('platform.groups.list')
                ->push($groupName);
        });

    Route::screen('groups/create', GroupEditScreen::class)
        ->name('platform.groups.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.groups.list')
                ->push('Добавить подборку');
        });
    /**************************************************************/

    /**
     * Popular products
     */
    Route::screen('popular-products', PopularProductListScreen::class)
        ->name('platform.popular-products')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.popular_products'), route('platform.popular-products'));
        });
    /**************************************************************/

    /**
     * Forgotten products
     */
    Route::screen('forgotten-products', ForgottenProductListScreen::class)
        ->name('platform.forgotten-products')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.forgotten_products'), route('platform.forgotten-products'));
        });
    /**************************************************************/

    /**
     * Product Of The Week
     */
    Route::screen('week-products', WeekProductListScreen::class)
        ->name('platform.week-products')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.week_products'), route('platform.week-products'));
        });
    /**************************************************************/

    /**
     * Recommended products
     */
    Route::screen('recommended-products', RecommendedProductListScreen::class)
        ->name('platform.recommended-products')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.recommended_products'), route('platform.recommended-products'));
        });
    /**************************************************************/

    /** Orders */
    Route::screen('orders/{order}/payment/{payment}', PaymentLogListScreen::class)
        ->name('platform.orders.payment')
        ->breadcrumbs(function (Trail $trail, Order $order, OnlinePayment $payment) {
            return $trail
                ->parent('platform.orders.list')
                ->push('Заказ №' . $order->id, route('platform.orders.edit', $order->id))
                ->push('Логи платежа №' . $payment->id);
        });

    Route::screen('orders/{order}/edit', OrderEditScreen::class)
        ->name('platform.orders.edit')
        ->breadcrumbs(function (Trail $trail, Order $order) {
            return $trail
                ->parent('platform.orders.list')
                ->push('Заказ №' . $order->id);
        });

    Route::screen('orders/create', OrderEditScreen::class)
        ->name('platform.orders.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.orders.list')
                ->push('Заказ');
        });

    Route::screen('orders', OrderListScreen::class)
        ->name('platform.orders.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.orders'), route('platform.orders.list'));
        });
    /**************************************************************/

    Route::screen('tags/{tag}/edit', TagEditScreen::class)
        ->name('platform.tags.edit')
        ->breadcrumbs(function (Trail $trail, Tag $tag) {
            $tagName = $tag->text ?? 'Тег';
            return $trail
                ->parent('platform.tags.list')
                ->push( $tagName);
        });

    Route::screen('tags/create', TagEditScreen::class)
        ->name('platform.tags.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.tags.list')
                ->push('Тег');
        });

    Route::screen('tags', TagListScreen::class)
        ->name('platform.tags.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.tags'), route('platform.tags.list'));
        });

    /** Stores */
    Route::screen('stores', StoreListScreen::class)
        ->name('platform.stores.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.stores'), route('platform.stores.list'));
        });

    Route::screen('stores/{store}/edit', StoreEditScreen::class)
        ->name('platform.stores.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.stores.list')
                ->push('Магазин');
        });
    /**************************************************************/

    /** Farmers questionnaires */
    Route::screen('farmer_questionnaires', FarmerQuestionnaireListScreen::class)
        ->name('platform.farmer_questionnaires.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.farmer_questionnaires'), route('platform.farmer_questionnaires.list'));
        });

    Route::screen('farmer_questionnaires/{farmer_questionnaire}/show', FarmerQuestionnaireShowScreen::class)
        ->name('platform.farmer_questionnaires.show')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.farmer_questionnaires.list')
                ->push('Анкета фермера');
        });
    /**************************************************************/

    /** Regions */
    Route::screen('regions/{region}/edit', RegionEditScreen::class)
        ->name('platform.regions.edit')
        ->breadcrumbs(function (Trail $trail, $regoin) {
            $regionName = $regoin->title ?? 'Регион';
            return $trail
                ->parent('platform.regions.list')
                ->push($regionName);
        });

    Route::screen('regions/create', RegionEditScreen::class)
        ->name('platform.regions.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.regions.list')
                ->push('Добавление региона');
        });

    Route::screen('regions', RegionListScreen::class)
        ->name('platform.regions.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Регионы', route('platform.regions.list'));
        });
    /**************************************************************/

    /** Main banners */
    Route::screen('main-slider/{slide}/edit', MainSlideEditScreen::class)
        ->name('platform.main-slider.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.main-slider.list')
                ->push('Редактирование слайдера');
        });

    Route::screen('main-slider/create', MainSlideEditScreen::class)
        ->name('platform.main-slider.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.main-slider.list')
                ->push('Добавление слайдера');
        });

    Route::screen('main-slider', MainSlidesListScreen::class)
        ->name('platform.main-slider.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Главный слайдер', route('platform.main-slider.list'));
        });
    /**************************************************************/

    /** Second banners */
    Route::screen('second-slider/{slide}/edit', SecondSlideEditScreen::class)
        ->name('platform.second-slider.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.second-slider.list')
                ->push('Редактирование слайдера');
        });

    Route::screen('second-slider/create', SecondSlideEditScreen::class)
        ->name('platform.second-slider.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.second-slider.list')
                ->push('Добавление слайдера');
        });

    Route::screen('second-slider', SecondSlidesListScreen::class)
        ->name('platform.second-slider.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Главный слайдер', route('platform.second-slider.list'));
        });
    /**************************************************************/

    /** Third banners */
    Route::screen('third-slider/{slide}/edit', ThirdSlideEditScreen::class)
        ->name('platform.third-slider.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.third-slider.list')
                ->push('Редактирование слайдера');
        });

    Route::screen('third-slider/create', ThirdSlideEditScreen::class)
        ->name('platform.third-slider.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.third-slider.list')
                ->push('Добавление слайдера');
        });

    Route::screen('third-slider', ThirdSlidesListScreen::class)
        ->name('platform.third-slider.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Главный слайдер', route('platform.third-slider.list'));
        });
    /**************************************************************/

    /** Promos */
    Route::screen('promos/{promo}/edit', PromoEditScreen::class)
        ->name('platform.promos.edit')
        ->breadcrumbs(function (Trail $trail, $promo) {
            $promoCode = $promo->code ?? 'Промокод';
            return $trail
                ->parent('platform.promos.list')
                ->push($promoCode);
        });

    Route::screen('promos/create', PromoEditScreen::class)
        ->name('platform.promos.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.promos.list')
                ->push('Добавить промокод');
        });

    Route::screen('promos', PromoListScreen::class)
        ->name('platform.promos.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.promos'), route('platform.promos.list'));
        });
    /**************************************************************/

    /** Promo actions */
    Route::screen('promo-actions/{id}/edit', PromoActionEditScreen::class)
        ->name('platform.promo-actions.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.promo-actions.list')
                ->push('Промо-акции');
        });

    Route::screen('promo-actions/create', PromoActionEditScreen::class)
        ->name('platform.promo-actions.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.promo-actions.list')
                ->push('Добавить промо-акцию');
        });

    Route::screen('promo-actions', PromoActionListScreen::class)
        ->name('platform.promo-actions.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Список промо-акций', route('platform.promo-actions.list'));
        });
    /**************************************************************/

    /** Lotteries */

    Route::screen('lotteries/{id}/edit', LotteryEditScreen::class)
        ->name('platform.lotteries.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.lotteries.list')
                ->push('Розыгрыши');
        });

    Route::screen('lotteries/create', LotteryEditScreen::class)
        ->name('platform.lotteries.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.lotteries.list')
                ->push('Добавить розыгрыш');
        });

    Route::screen('lotteries', LotteryListScreen::class)
        ->name('platform.lotteries.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Список розыгрышей', route('platform.lotteries.list'));
        });

    /**************************************************************/

    /** Pages */
    Route::screen('pages/{page}/edit', PageEditScreen::class)
        ->name('platform.pages.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.pages.list')
                ->push('Страницы');
        });

    Route::screen('pages/create', PageEditScreen::class)
        ->name('platform.pages.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.pages.list')
                ->push('Добавить страницу');
        });

    Route::screen('pages', PageListScreen::class)
        ->name('platform.pages.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.pages'), route('platform.pages.list'));
        });

    /** Legal Entities */
    Route::screen('legal_entities/{entity}/edit', LegalEntityEditScreen::class)
        ->name('platform.legal_entities.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.legal_entities.list')
                ->push(__('admin.legal_entities'));
        });

    Route::screen('legal_entities', LegalEntityListScreen::class)
        ->name('platform.legal_entities.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.legal_entities'), route('platform.legal_entities.list'));
        });
    /**************************************************************/

    /** Payment types */
    Route::screen('payment-types/{payment}/edit', PaymentTypeEditScreen::class)
        ->name('platform.payment_types.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.payment_types.list')
                ->push(__('admin.payment_types'));
        });

    Route::screen('payment-types', PaymentTypeListScreen::class)
        ->name('platform.payment_types.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.payment_types'), route('platform.payment_types.list'));
        });

    /**************************************************************/

    /** Polygon types */
    Route::screen('polygon-types/{polygon}/edit', PolygonTypeEditScreen::class)
        ->name('platform.polygon_types.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.polygon_types.list')
                ->push(__('admin.polygon_type.type'));
        });

    Route::screen('polygon-types', PolygonTypeListScreen::class)
        ->name('platform.polygon_types.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.polygon_types'), route('platform.polygon_types.list'));
        });

    /** Mobile versions */
    Route::screen('mobile-versions/{mobileVersion}/edit', MobileVersionEditScreen::class)
        ->name('platform.mobile-versions.edit')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.mobile-versions.list')
                ->push('Версии МП');
        });

    Route::screen('mobile-versions/create', MobileVersionEditScreen::class)
        ->name('platform.mobile-versions.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.mobile-versions.list')
                ->push('Добавить версию');
        });

    Route::screen('mobile-versions', MobileVersionListScreen::class)
        ->name('platform.mobile-versions.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.mobile_version.list'), route('platform.mobile-versions.list'));
        });

    /**************************************************************/

    /** Audience */

    Route::screen('audiences/create', \App\Orchid\Screens\Reports\AudienceEditScreen::class)
        ->name('platform.audiences.create')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.audiences.list')
                ->push('Создать аудитории');
        });

    Route::screen('audiences/{audience}/edit', \App\Orchid\Screens\Reports\AudienceEditScreen::class)
        ->name('platform.audiences.edit')
        ->breadcrumbs(function (Trail $trail, $id) {
            return $trail
                ->parent('platform.audiences.list')
                ->push('Редактировать аудиторию', route('platform.audiences.list', ['id' => $id]));
        });

    Route::screen('audiences', \App\Orchid\Screens\Reports\AudienceScreen::class)
        ->name('platform.audiences.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push(__('admin.audience.title'), route('platform.audiences.list'));
        });
});

Route::screen('mass-notifications', MassNotificationScreen::class)
    ->name('platform.mass_notifications')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('admin.notifications.mass_notifications'), route('platform.mass_notifications'));
    });

Route::screen('push-notifications', PushNotificationScreen::class)
    ->name('platform.push_notifications')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('admin.notifications.push_notifications'), route('platform.push_notifications'));
    });

Route::get('export', [ExportController::class, 'export'])->name('export');

Route::screen('support', SupportScreen::class)
    ->name('platform.support')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('admin.support'), route('platform.support'));
    });

Route::screen('support/chat/{user}', SupportChatScreen::class)
    ->name('platform.support.detail')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.support')
            ->push('Чат с покупателем #' . $user->id, route('platform.support.detail', ['user' => $user]));
    });

Route::get('/support/messages/unread-count', [SupportController::class, 'getUnreadCount'])
    ->name('platform.support.messages.unread-count');

/** Groups */
Route::screen('faq-categories', FaqCategoryListScreen::class)
    ->name('platform.faq-categories.list')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Справочник', route('platform.faq-categories.list'));
    });

Route::screen('faq-categories/{category}/edit', FaqCategoryEditScreen::class)
    ->name('platform.faq-categories.edit')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.faq-categories.list')
            ->push('Категория справочника');
    });

Route::screen('faq-categories/create', FaqCategoryEditScreen::class)
    ->name('platform.faq-categories.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.faq-categories.list')
            ->push('Добавить категорию справочника');
    });

Route::screen('faq-categories/{parent_id}/questions/{id}/edit', FaqQuestionEditScreen::class)
    ->name('platform.faq-categories.questions.edit')
    ->breadcrumbs(function (Trail $trail, string $parent_id) {
        return $trail
            ->parent('platform.faq-categories.edit', $parent_id)
            ->push('Вопрос категории');
    });

Route::screen('faq-categories/{parent_id}/questions/create', FaqQuestionEditScreen::class)
    ->name('platform.faq-categories.questions.create')
    ->breadcrumbs(function (Trail $trail, $parent_id) {
        return $trail
            ->parent('platform.faq-categories.edit', $parent_id)
            ->push('Добавление страницы');
    });
Route::screen('settings/{setting}/edit', SettingEditScreen::class)
    ->name('platform.settings.edit')
    ->breadcrumbs(function (Trail $trail, $setting) {
        return $trail
            ->parent('platform.settings')
            ->push(__('Edit'), route('platform.settings.edit', $setting));
    });

Route::screen('settings/create', SettingEditScreen::class)
    ->name('platform.settings.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.settings')
            ->push(__('Create'), route('platform.settings.create'));
    });

Route::screen('settings', SettingListScreen::class)
    ->name('platform.settings')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('admin.settings.name'), route('platform.settings'));
    });

Route::get('/admin/profile/logout', function () {
    Auth::logout();

    return redirect()->route('platform.main');
})->name('platform.admin.logout');
/**************************************************************/


// FIXME: Убрать этот треш
Route::get('/download-report-upload-user', function () {
    $filePath = storage_path('app/' . AnalyticConstants::NAME_FILE_REPORT_UPLOAD_USER);
    $fileName = basename($filePath);

    if (file_exists($filePath)) {
        return response()->download($filePath, $fileName)->deleteFileAfterSend();
    } else {
        return response()->json(['error' => 'File xlsx not found'], 404);
    }
});

Route::get('/download/log-report/{file}', [DownloadController::class, 'logReport'])
    ->name('download.log-report')
    ->where('file', '.*');
