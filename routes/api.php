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
use App\Http\Controllers\PaymentCallbackController;

// Auth routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/admin/login', [AuthController::class, 'adminLogin'])->middleware('ip.whitelist');

// Member routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
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
Route::post('/orders', [TransactionController::class, 'createOrder'])->middleware('auth:sanctum');
Route::post('/guest-checkout', [TransactionController::class, 'guestCheckout']);

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('/admin/games', GameController::class);
    Route::apiResource('/admin/categories', CategoryController::class);
    Route::apiResource('/admin/products', ProductController::class);
    Route::apiResource('/admin/orders', OrderController::class);
    Route::apiResource('/admin/deposits', DepositController::class);
    Route::apiResource('/admin/payment-methods', PaymentMethodController::class);
    Route::apiResource('/admin/banners', BannerController::class);
    Route::apiResource('/admin/popups', PopupController::class);
    Route::apiResource('/admin/configs', ConfigController::class);
    Route::apiResource('/admin/popular-games', PopularGameController::class);
    Route::apiResource('/admin/users', UserController::class);
    Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetUserPassword']);
    Route::post('/admin/bulk-activate-products', [AdminController::class, 'bulkActivateProducts']);
    Route::get('/admin/analytics', [AdminController::class, 'analytics']);
});

// Payment callback
Route::post('/payment/callback', [PaymentCallbackController::class, 'handleCallback']);
