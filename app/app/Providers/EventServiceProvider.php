<?php

namespace App\Providers;

use Domain\Image\Models\Attachment;
use Domain\Image\Observer\AttachmentObserver;
use Domain\Order\Models\Order;
use Domain\Order\Observers\OrderObserver;
use Domain\Product\Events\ReviewCreated;
use Domain\Product\Listeners\ProductRatingUpdateListener;
use Domain\Product\Models\Product;
use Domain\Product\Observers\ProductObserver;
use Domain\Store\Models\ProductStore;
use Domain\Store\Models\Store;
use Domain\Store\Observers\ProductStoreObserver;
use Domain\Store\Observers\StoreObserver;
use Domain\Story\Models\Story;
use Domain\Story\Models\StoryPage;
use Domain\Story\Observers\StoryObserver;
use Domain\Story\Observers\StoryPageObserver;
use Domain\Support\Models\SupportMessage;
use Domain\Support\Observers\SupportMessageObserver;
use Domain\Unit\Models\Unit;
use Domain\Unit\Observers\UnitObserver;
use Domain\User\Events\AuthenticatedByApi;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        AuthenticatedByApi::class => [
            //TODO добавить лиснер для обновления магазина пользователя
            //TODO добавить логику для синхронизации товаров в избраном
        ],
        ReviewCreated::class => [
            ProductRatingUpdateListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Unit::observe(UnitObserver::class);
        Order::observe(OrderObserver::class);
        Store::observe(StoreObserver::class);
        ProductStore::observe(ProductStoreObserver::class);
        Story::observe(StoryObserver::class);
        StoryPage::observe(StoryPageObserver::class);
        SupportMessage::observe(SupportMessageObserver::class);
        Attachment::observe(AttachmentObserver::class);
    }
}
