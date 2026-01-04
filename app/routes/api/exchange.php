<?php

use App\Http\Controllers\Api\V1\Exchange\AuthController;
use App\Http\Controllers\Api\V1\Exchange\CategoryController;
use App\Http\Controllers\Api\V1\Exchange\CityController;
use App\Http\Controllers\Api\V1\Exchange\LeftoverController;
use App\Http\Controllers\Api\V1\Exchange\Order\OrderCreateController;
use App\Http\Controllers\Api\V1\Exchange\Order\OrderUpdateController;
use App\Http\Controllers\Api\V1\Exchange\OrderConfirmController;
use App\Http\Controllers\Api\V1\Exchange\OrderController;
use App\Http\Controllers\Api\V1\Exchange\ProductController;
use App\Http\Controllers\Api\V1\Exchange\RegionController;
use App\Http\Controllers\Api\V1\Exchange\StoreController;
use App\Http\Controllers\Api\V1\Exchange\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::post('regions', [RegionController::class, 'exchangeCollection'])->name('regions');
    Route::post('cities', [CityController::class, 'exchangeCollection'])->name('cities');
    Route::post('categories', [CategoryController::class, 'exchangeCollection'])->name('categories');
    Route::post('leftovers', [LeftoverController::class, 'exchangeCollection'])->name('leftovers');
    Route::post('products', [ProductController::class, 'exchangeCollection'])->name('products');
    Route::post('stores', [StoreController::class, 'exchangeCollection'])->name('stores');
    Route::post('units', [UnitController::class, 'exchangeCollection'])->name('units');

    Route::name('orders.')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'export'])->name('export');
        Route::post('/', [OrderCreateController::class, 'exchangeCollection'])->name('exchangeCreateCollection');
        Route::put('/', [OrderUpdateController::class, 'exchangeCollection'])->name('exchangeUpdateCollection');
        Route::post('/status', [OrderController::class, 'getStatus'])->name('getStatus');
        Route::post('sync-confirm', [OrderConfirmController::class, 'exchangeCollection'])->name('confirm');
    });
});

Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh');
});
