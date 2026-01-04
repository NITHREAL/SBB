<?php

namespace App\Providers;

use Domain\Farmer\Models\Farmer;
use Domain\Order\Models\Order;
use Domain\Product\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\Models\Review;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\CouponCategory\Models\CouponCategory;
use Domain\Store\Models\Store;
use Domain\Story\Models\Story;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (!$this->app->environment('local')) {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        Relation::enforceMorphMap([
            'category'          => Category::class,
            'product'           => Product::class,
            'farmer'            => Farmer::class,
            'store'             => Store::class,
            'user'              => User::class,
            'couponCategory'    => CouponCategory::class,
            'group'             => ProductGroup::class,
            'order'             => Order::class,
            'story'             => Story::class,
            'review'            => Review::class,
        ]);
    }
}
