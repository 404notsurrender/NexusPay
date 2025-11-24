<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\PopularGameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\VipResellerController;

// Auth routes
Route::post('/auth/login', [MemberController::class, 'login']);
Route::post('/auth/admin/login', [AdminController::class, 'adminLogin'])->middleware('ip.whitelist');
Route::middleware('auth:sanctum')->post('/auth/logout', [AdminController::class, 'logout']);

// Member routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [MemberController::class, 'getProfile']);
    Route::put('/profile', [MemberController::class, 'updateProfile']);
    Route::post('/reset-password', [MemberController::class, 'resetPassword']);
    Route::get('/orders', [MemberController::class, 'getOrders']);
    Route::get('/orders/{order}', [TransactionController::class, 'checkStatus']);
});

// Public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{game}', [GameController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

// Transaction routes
Route::middleware('auth:sanctum')->post('/orders', [TransactionController::class, 'createOrder']);
Route::post('/guest-checkout', [TransactionController::class, 'guestCheckout']);

// VVIP RESELLER ROUTES 
Route::prefix('vip-reseller')->group(function () {
    Route::get('/games', [VipResellerController::class, 'getGames']);
    Route::get('/order', [VipResellerController::class, 'order']);
    Route::get('/status/{trxid}', [VipResellerController::class, 'status']);
    Route::get('/auto-update', [VipResellerController::class, 'autoUpdate']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('/admin/games', GameController::class, ['as' => 'admin']);
    Route::apiResource('/admin/categories', CategoryController::class, ['as' => 'admin']);
    Route::apiResource('/admin/products', ProductController::class, ['as' => 'admin']);
    Route::apiResource('/admin/orders', TransactionController::class, ['as' => 'admin']);
    Route::apiResource('/admin/deposits', DepositController::class, ['as' => 'admin']);
    Route::apiResource('/admin/payment-methods', PaymentMethodController::class, ['as' => 'admin']);
    Route::apiResource('/admin/banners', BannerController::class, ['as' => 'admin']);
    Route::apiResource('/admin/popups', PopupController::class, ['as' => 'admin']);
    Route::apiResource('/admin/configs', ConfigController::class, ['as' => 'admin']);
    Route::apiResource('/admin/popular-games', PopularGameController::class, ['as' => 'admin']);
    Route::apiResource('/admin/users', UserController::class, ['as' => 'admin']);

    Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetUserPassword']);
    Route::post('/admin/bulk-activate-products', [AdminController::class, 'bulkActivateProducts']);
    Route::get('/admin/analytics', [AdminController::class, 'analytics']);
});

// Payment callback
Route::post('/payment/callback', [PaymentCallbackController::class, 'handleCallback']);
