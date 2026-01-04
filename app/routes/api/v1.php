<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Basket\BasketController;
use App\Http\Controllers\Api\V1\Basket\BasketSettingController;
use App\Http\Controllers\Api\V1\City\CityController;
use App\Http\Controllers\Api\V1\City\RegionController;
use App\Http\Controllers\Api\V1\Coupon\CouponController;
use App\Http\Controllers\Api\V1\DaDataController;
use App\Http\Controllers\Api\V1\Delivery\DeliveryTypeController;
use App\Http\Controllers\Api\V1\Faq\FaqController;
use App\Http\Controllers\Api\V1\FarmerController;
use App\Http\Controllers\Api\V1\Lottery\LotteryController;
use App\Http\Controllers\Api\V1\Mobile\MobileAppTokenController;
use App\Http\Controllers\Api\V1\Modules\ProductGroupController;
use App\Http\Controllers\Api\V1\Modules\StoryController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\Order\OnlinePaymentBindingController;
use App\Http\Controllers\Api\V1\Order\OrderController;
use App\Http\Controllers\Api\V1\Order\OrderReviewController;
use App\Http\Controllers\Api\V1\Order\OrderSettingController;
use App\Http\Controllers\Api\V1\Order\Sberbank\SberbankController;
use App\Http\Controllers\Api\V1\Order\Yookassa\YookassaController;
use App\Http\Controllers\Api\V1\Product\CategoryController;
use App\Http\Controllers\Api\V1\Product\ExpectedProductController;
use App\Http\Controllers\Api\V1\Product\FavoriteController;
use App\Http\Controllers\Api\V1\Product\ForgottenProductController;
use App\Http\Controllers\Api\V1\Product\PopularProductController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Product\ReviewController;
use App\Http\Controllers\Api\V1\Product\WeekProductController;
use App\Http\Controllers\Api\V1\PromoAction\PromoActionController;
use App\Http\Controllers\Api\V1\Promocode\PromocodeController;
use App\Http\Controllers\Api\V1\Sbermarket\SbermarketController;
use App\Http\Controllers\Api\V1\ServerDataController;
use App\Http\Controllers\Api\V1\Stores\StoreController;
use App\Http\Controllers\Api\V1\Support\SupportMessageController;
use App\Http\Controllers\Api\V1\User\Bonuses\UserBonusesController;
use App\Http\Controllers\Api\V1\User\Category\FavoriteCategoryController;
use App\Http\Controllers\Api\V1\User\LoyaltyCards\LoyaltyCardController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Api\V1\User\Settings\UserSettingsController;
use App\Http\Controllers\Api\V1\User\UserAddressController;
use App\Http\Controllers\Api\V1\User\UserOrderController;
use App\Http\Controllers\Api\V1\User\UserPaymentController;
use App\Http\Controllers\Api\V1\User\UserProductController;
use App\Http\Controllers\Api\V1\User\UserStoreController;
use Illuminate\Support\Facades\Route;

Route::get('check-token', [AuthController::class, 'checkToken'])->name('checkAuthToken');
Route::get('check-version', [AuthController::class, 'checkVersion'])->name('checkMobileVersion');

// Authorization and authentication
Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('/phone', [AuthController::class, 'login'])
        ->name('phone')
        ->middleware(['auth-rate-limiter']);
    Route::post('/code/check', [AuthController::class, 'codeCheck'])
        ->name('code-check');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])
        ->name('refreshToken');
});

Route::middleware(['auth:api'])->group(function () {
    // User profile & other data
    Route::name('user.')->prefix('user')->group(function () {
        // Profile
        Route::name('profile.')->prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::post('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'delete'])->name('delete');
            Route::post('/phone', [ProfileController::class, 'updatePhone'])
                ->name('updatePhone')
                ->middleware(['auth-rate-limiter']);
            Route::post('/phone/check-code', [ProfileController::class, 'checkCode'])
                ->name('checkCode');
        });

        // Addresses
        Route::apiResource('address', UserAddressController::class)
            ->only('index', 'show', 'store', 'update', 'destroy');
        Route::get('/address/settings/entrance-variants', [UserAddressController::class, 'getEntranceVariants']);

        // Notifications
        Route::name('notifications.')->prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
            Route::put('/{id}', [NotificationController::class, 'readOne'])->name('readOne');
            Route::put('/', [NotificationController::class, 'readAll'])->name('readAll');
            Route::delete('/', [NotificationController::class, 'removeAll'])->name('removeAll');
        });

        // User payment options
        Route::name('payment.')->prefix('payment')->group(function () {
            Route::get('/methods', [UserPaymentController::class, 'paymentMethods'])->name('paymentMethods');

            Route::name('cards.')->prefix('cards')->group(function () {
                Route::get('/', [OnlinePaymentBindingController::class, 'index'])->name('list');
                Route::post('/', [OnlinePaymentBindingController::class, 'store'])->name('create');
                Route::delete('/{id}', [OnlinePaymentBindingController::class, 'destroy'])->name('delete');
            });
        });

        // Orders
        Route::name('orders.')->prefix('orders')->group(function () {
            Route::get('/', [UserOrderController::class, 'index'])->name('index');
            Route::get('/history', [UserOrderController::class, 'history'])->name('history');
            Route::get('/closest', [UserOrderController::class, 'closest'])->name('closest');
            Route::get('/{id}', [UserOrderController::class, 'show'])->name('show');
            Route::post('/{id}/repeat', [UserOrderController::class, 'repeat'])->name('repeat');
            Route::post('{id}/cancel', [UserOrderController::class, 'cancel'])->name('cancel');
            Route::post('{id}/review', [OrderReviewController::class, 'store'])->name('createReview');
        });

        // Product
        Route::name('products.')->prefix('products')->group(function () {
            Route::get('/purchased', [UserProductController::class, 'purchased'])
                ->name('purchased');
            Route::post('expected/{id}', [ExpectedProductController::class, 'store'])
                ->name('store-expected-product');
        });

        // Favorite stores
        Route::name('stores.')->prefix('stores')->group(function () {
            Route::get('/', [UserStoreController::class, 'index'])->name('index');
            Route::post('/{storeId}', [UserStoreController::class, 'add'])->name('add');
            Route::delete('/{storeId}', [UserStoreController::class, 'delete'])->name('delete');
        });

        //Settings
        Route::name('settings.')->prefix('settings')->group(function () {
            Route::get('/', [UserSettingsController::class, 'index'])->name('index');
            Route::post('/', [UserSettingsController::class, 'update'])->name('update');
            Route::get('/electronic-checks', [UserSettingsController::class, 'getElectronicChecksParam'])
                ->name('getElectronicChecksParam');
            Route::post('/electronic-checks', [UserSettingsController::class, 'updateElectronicChecksParam'])
                ->name('updateElectronicChecksParam');
            Route::get('/auto-brightness', [UserSettingsController::class, 'getAutoBrightnessParam'])
                ->name('getAutoBrightnessParam');
            Route::post('/auto-brightness', [UserSettingsController::class, 'updateAutoBrightnessParam'])
                ->name('updateAutoBrightnessParam');
        });

        // Categories
        Route::name('categories.')->prefix('categories')->group(function () {
            Route::get('/', [FavoriteCategoryController::class, 'index'])
                ->name('index');
            Route::get('/available', [FavoriteCategoryController::class, 'availableCategories'])
                ->name('availableCategories');
            Route::post('/', [FavoriteCategoryController::class, 'store'])
                ->name('store');
            Route::get('/current', [FavoriteCategoryController::class, 'currentCategories'])
                ->name('currentCategories');
        });

        //Loyalty cards
        Route::name('loyalty-cards.')->prefix('loyalty-cards')->group(function () {
            Route::get('/', [LoyaltyCardController::class, 'index'])->name('index');
            Route::post('/', [LoyaltyCardController::class, 'add'])->name('store');
        });
    });

    Route::name('coupons.')->prefix('coupons')->group(function () {

        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/list', [CouponController::class, 'list'])->name('list');
        Route::get('/{id}', [CouponController::class, 'show'])->name('show');

        Route::get('/bonus/overview', [UserBonusesController::class, 'getBonusAccountOverview'])
            ->name('bonus.overview');
        Route::get('/bonus/balance', [UserBonusesController::class, 'getBonusAccountBalances'])
            ->name('bonus.balance');
        Route::get('/bonus/history', [UserBonusesController::class, 'getBonusAccountHistory'])
            ->name('bonus.history');
    });

    // Delivery
    Route::name('delivery.')->prefix('delivery')->group(function () {
        Route::post('/type', [DeliveryTypeController::class, 'setDeliveryType'])
            ->name('setType');
        Route::get('/check', [DeliveryTypeController::class, 'getAvailableDeliveryType'])
            ->name('checkType');
        Route::prefix('date-time')->group(function () {
            Route::get('/', [DeliveryTypeController::class, 'getDateTimeIntervals'])
                ->name('getDateTime');
            Route::post('/', [DeliveryTypeController::class, 'setDateTimeIntervals'])
                ->name('setDateTime');
        });
    });

    // Basket
    Route::name('basket.')->prefix('basket')->group(function () {
        Route::get('/', [BasketController::class, 'getBasket'])->name('get');
        Route::put('/{id}', [BasketController::class, 'addProduct'])->name('addProduct');
        Route::delete('/{id}', [BasketController::class, 'removeProduct'])->name('removeProduct');
        Route::patch('/increment/{productId}', [BasketController::class, 'incrementProduct'])->name('incrementProduct');
        Route::patch('/decrement/{productId}', [BasketController::class, 'decrementProduct'])->name('decrementProduct');
        Route::patch('/set-count', [BasketController::class, 'setCountProduct'])->name('setCountProduct');
        Route::post('/clear', [BasketController::class, 'clear'])->name('clear');
        Route::post('/delivery', [BasketController::class, 'setDeliveryParams'])->name('deliveryParams');

        Route::name('promocode.')->prefix('promocode')->group(function () {
            Route::post('/set', [BasketController::class, 'setPromocode'])->name('set');
            Route::post('/clear', [BasketController::class, 'clearPromocode'])->name('clear');
        });

        Route::name('coupon.')->prefix('coupon')->group(function () {
            Route::post('/set', [BasketController::class, 'setCoupon'])->name('set');
            Route::post('/clear', [BasketController::class, 'clearCoupon'])->name('clear');
        });

        Route::prefix('settings')->group(function (){
            Route::get('/', [BasketSettingController::class, 'getSettings']);
            Route::post('/', [BasketSettingController::class, 'setSettings']);
        });
    });

    // Orders
    Route::name('order.')->prefix('order')->group(function () {
        Route::post('/', [OrderController::class, 'create'])->name('create');

        Route::prefix('settings')->group(function (){
            Route::get('product-missing', [OrderSettingController::class, 'productMissing']);
        });

        // Для тестирования оплат и поведения заказа при смене статусов
        if (config('app.env') !== 'production') {
            Route::post('/collect', [OrderController::class, 'collect'])->name('collect');
            Route::post('/complete', [OrderController::class, 'complete'])->name('complete');
            Route::post('/init-payment', [OrderController::class, 'initPayment'])->name('initPayment');
        }
    });

    // Stories metadata
    Route::name('stories.')->prefix('stories')->group(function () {
        Route::post('/{id}/metadata', [StoryController::class, 'storeMetadata'])->name('storeMetadata');
    });

    // Promocodes
    Route::name('promocodes.')->prefix('promocodes')->group(function () {
        Route::get('/', [PromocodeController::class, 'index'])->name('index');
        Route::get('/first-order', [PromocodeController::class, 'firstOrder'])->name('getFirstOrderPromocode');
    });

    // Mobile
    Route::name('mobile.')->prefix('mobile')->group(function () {
        Route::post('/token-app', [MobileAppTokenController::class, 'store'])->name('storeAppToken');
    });

    // Support
    Route::name('support.')->prefix('support')->group(function () {
        Route::name('messages.')->prefix('messages')->group(function () {
            Route::get('/', [SupportMessageController::class, 'index'])
                ->name('index');
            Route::get('/unread-count', [SupportMessageController::class, 'getUnreadCount'])
                ->name('unreadCount');
            Route::post('/', [SupportMessageController::class, 'store'])->name('store');
            Route::put('/{id}', [SupportMessageController::class, 'update'])->name('update');
            Route::post('/read', [SupportMessageController::class, 'read'])->name('read');

            if (app()->isProduction() === false) {
                Route::post('/admin-message', [SupportMessageController::class, 'storeAdminMessage'])
                    ->name('storeAdminMessage');
            }
        });
    });

    // Favorite products
    Route::name('favorites.')->prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::post('/{id}', [FavoriteController::class, 'addToFavorite'])->name('add');
        Route::delete('/{id}', [FavoriteController::class, 'deleteFromFavorite'])->name('delete');
    });
});

// DaData cities and addresses
Route::name('dadata.')->prefix('dadata')->group(function () {
    Route::get('/city', [DaDataController::class, 'getCities'])
        ->name('cities');
    Route::get('/address', [DaDataController::class, 'getAddresses'])
        ->name('addresses');
    Route::get('/location', [DaDataController::class, 'getBuyerLocation'])
        ->name('location');
    Route::get('/geolocate', [DaDataController::class, 'getAddressByCoords'])
        ->name('addressByCoordinates');
});

Route::name('cities.')->prefix('cities')->group(function () {
    Route::get('/', [CityController::class, 'index'])->name('index');
});

Route::name('regions.')->prefix('regions')->group(function () {
    Route::get('/', [RegionController::class, 'index'])->name('index');
    Route::get('/{region:id}', [RegionController::class, 'view'])->name('region');
    Route::get('/{region:id}/cities', [RegionController::class, 'cities'])->name('cities');
});

// Products
Route::name('products.')->prefix('products')->group(function () {
    Route::name('catalog.')->prefix('catalog')->group(function () {
        Route::get('/{categorySlug}', [ProductController::class, 'index'])->name('catalog');
        Route::get('/preview/{categorySlug}', [ProductController::class, 'preview'])->name('category');
    });

    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/search-by-barcode/{barcode}', [ProductController::class, 'searchByBarcode'])
        ->where('barcode', '[A-Za-z0-9]{13}')
        ->name('searchByBarcode');

    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
    Route::get('/{slug}/review', [ReviewController::class, 'index'])->name('indexReview');
    Route::post('/{slug}/review', [ReviewController::class, 'store'])
        ->name('storeReview')
        ->middleware(['auth:api']);
    Route::get('/{slug}/related', [ProductController::class, 'getRelatedProducts'])->name('relatedProduct');
});

// Popular products
Route::name('popular-products.')->prefix('popular-products')->group(function () {
    Route::get('/', [PopularProductController::class, 'index'])->name('index');
    Route::get('/catalog', [PopularProductController::class, 'catalog'])->name('catalog');
});

Route::name('forgotten-products.')->prefix('forgotten-products')->group(function () {
    Route::get('/', [ForgottenProductController::class, 'index'])->name('index');
    Route::get('/catalog', [ForgottenProductController::class, 'catalog'])->name('catalog');
});

// Week products
Route::name('week-products.')->prefix('week-products')->group(function () {
    Route::get('/', [WeekProductController::class, 'index'])->name('index');
    Route::get('/catalog', [WeekProductController::class, 'catalog'])->name('catalog');
});

// Product groups
Route::name('product-groups.')->prefix('product-groups')->group(function () {
    Route::get('/', [ProductGroupController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductGroupController::class, 'show'])->name('show');
});

// Promo-actions
Route::name('promo-actions.')->prefix('promo-actions')->group(function () {
    Route::get('/', [PromoActionController::class, 'index'])->name('index');
    Route::get('/{slug}', [PromoActionController::class, 'show'])->name('show');
    Route::get('/{slug}/catalog', [PromoActionController::class, 'catalog'])->name('catalog');
});

// Lotteries
Route::name('lotteries.')->prefix('lotteries')->group(function () {
    Route::get('/', [LotteryController::class, 'index'])->name('index');
    Route::get('/all', [LotteryController::class, 'list'])->name('list');
    Route::get('/{slug}', [LotteryController::class, 'show'])->name('show');
    Route::get('/{slug}/catalog', [LotteryController::class, 'catalog'])->name('catalog');
});

// Stories
Route::name('stories.')->prefix('stories')->group(function () {
    Route::get('/', [StoryController::class, 'index'])->name('index');
});

// Stores
Route::name('stores.')->prefix('stores')->group(function () {
    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::get('/city/{cityId}', [StoreController::class, 'getByCity'])->name('getByCity');
    Route::get('/{slug}', [StoreController::class, 'show'])->name('show');
});

// Farmers
Route::name('farmers.')->prefix('farmers')->group(function () {
    Route::get('/', [FarmerController::class, 'index'])->name('index');
    Route::get('/{slug}', [FarmerController::class, 'show'])->name('show');
    Route::get('/{slug}/review', [FarmerController::class, 'getFarmerReviews'])->name('review');
});

// Categories
Route::name('categories.')->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'mainCategories'])->name('index');
    Route::get('/{slug}', [CategoryController::class, 'category'])->name('show');
});

// Delivery default data
Route::name('delivery.')->prefix('delivery')->group(function () {
    Route::get('/', [DeliveryTypeController::class, 'getDeliveryType']);
    Route::post('/type/city', [DeliveryTypeController::class, 'setDeliveryTypeByCity'])
        ->name('setByCity');
});

// FAQ
Route::name('faq.')->prefix('faq')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('/{category}', [FaqController::class, 'show'])->name('show');
});

// Yookassa
Route::name('yookassa')->prefix('yookassa')->group(function () {
    Route::post('notification', [YookassaController::class, 'index'])->name('webhook');
});

// Sbermarket
Route::name('sberbank.')->prefix('sberbank')->group(function () {
    Route::post('notification', [SberbankController::class, 'index'])->name('webhook');
});

// Sbermarket
Route::name('orders.')->prefix('orders')->group(function () {
    Route::get('/payment-callback', [SbermarketController::class, 'handlePayment'])->name('handlePayment');
    Route::get('/status', [SbermarketController::class, 'checkStatus'])->name('status');
});

Route::post('/sbermarket_webhook', [SbermarketController::class, 'index'])->name('sbermarketWebhook');

Route::get('/servertime', [ServerDataController::class, 'servertime'])->name('servertime');
