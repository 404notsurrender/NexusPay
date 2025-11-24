<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Services\TripayService;

class TransactionController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $product = Product::find($request->product_id);
        $paymentMethod = \App\Models\PaymentMethod::find($request->payment_method_id);

        if (!$product->available) {
            return response()->json(['message' => 'Product not available'], 400);
        }

        $totalPrice = ($product->custom_price ?? $product->price) * $request->quantity;

        $order = \App\Models\Order::create([
            'user_id' => optional($request->user())->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'payment_method_id' => $request->payment_method_id,
        ]);

        // Create payment transaction
        $paymentData = $this->tripayService->createTransaction($order, $paymentMethod);

        $order->update(['payment_reference' => $paymentData['reference']]);

        return response()->json([
            'order' => $order->load('product', 'paymentMethod'),
            'payment' => $paymentData,
        ], 201);
    }

    public function guestCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'customer_email' => 'required|email',
        ]);

        $product = Product::find($request->product_id);
        $paymentMethod = PaymentMethod::find($request->payment_method_id);

        if (!$product->available) {
            return response()->json(['message' => 'Product not available'], 400);
        }

        $totalPrice = ($product->custom_price ?? $product->price) * $request->quantity;

        $order = \App\Models\Order::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'payment_method_id' => $request->payment_method_id,
        ]);

        // Create payment transaction for guest
        $paymentData = $this->tripayService->createTransaction($order, $paymentMethod, $request->customer_email);

        $order->update(['payment_reference' => $paymentData['reference']]);

        return response()->json([
            'order' => $order->load('product', 'paymentMethod'),
            'payment' => $paymentData,
        ], 201);
    }

    public function checkStatus(Request $request, \App\Models\Order $order)
    {
        // Allow user to check their own orders or admin to check any
        $currentUser = $request->user();
        $currentUserId = $currentUser ? $currentUser->id : null;

        // If the current user is neither the owner nor an admin, deny access
        if ($order->user_id !== $currentUserId && (!$currentUser || $currentUser->role !== 'admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'order' => $order->load('product', 'paymentMethod', 'user'),
            'status' => $order->status,
        ]);
    }
}
