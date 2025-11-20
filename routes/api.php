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

// Auth routes
Route::post('/auth/login', [MemberController::class, 'login']);
Route::post('/auth/admin/login', [AuthController::class, 'adminLogin'])->middleware('ip.whitelist');
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

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

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Games
    Route::apiResource('/admin/games', GameController::class, ['as' => 'admin']);

    // Categories
    Route::apiResource('/admin/categories', CategoryController::class, ['as' => 'admin']);

    // Products
    Route::apiResource('/admin/products', ProductController::class, ['as' => 'admin']);

    // Orders
    Route::apiResource('/admin/orders', OrderController::class, ['as' => 'admin']);

    // Deposits
    Route::apiResource('/admin/deposits', DepositController::class, ['as' => 'admin']);

    // Payment Methods
    Route::apiResource('/admin/payment-methods', PaymentMethodController::class, ['as' => 'admin']);

    // Banners
    Route::apiResource('/admin/banners', BannerController::class, ['as' => 'admin']);

    // Popups
    Route::apiResource('/admin/popups', PopupController::class, ['as' => 'admin']);

    // Configs
    Route::apiResource('/admin/configs', ConfigController::class, ['as' => 'admin']);

    // Popular Games
    Route::apiResource('/admin/popular-games', PopularGameController::class, ['as' => 'admin']);

    // Users
    Route::apiResource('/admin/users', UserController::class, ['as' => 'admin']);
    Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetUserPassword']);

    // Bulk operations
    Route::post('/admin/bulk-activate-products', [AdminController::class, 'bulkActivateProducts']);

    // Analytics
    Route::get('/admin/analytics', [AdminController::class, 'analytics']);
});

// Payment callback
Route::post('/payment/callback', [PaymentCallbackController::class, 'handleCallback']);
