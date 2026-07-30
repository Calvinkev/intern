<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\Delivery;
use App\Models\Notification;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('restaurant', 'items')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                      ->where('user_id', Auth::id())
                      ->with('restaurant', 'items.food', 'payment', 'delivery')
                      ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cart = Auth::user()->cart;
        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        return view('orders.checkout', compact('cart'));
    }

    public function place(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:255',
            'delivery_phone' => 'required|string|max:20',
            'delivery_notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,mtn_mobile_money,airtel_money,stripe,card',
        ]);

        $cart = Auth::user()->cart;
        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Check if restaurant is open
        if (!$cart->restaurant->isOpen()) {
            return redirect()->route('cart.index')->with('error', 'This restaurant is currently closed.');
        }

        // Check minimum order amount
        if ($cart->subtotal < $cart->restaurant->min_order_amount) {
            return redirect()->route('cart.index')->with('error', "Minimum order amount is {$cart->restaurant->min_order_amount}. Current subtotal is {$cart->subtotal}.");
        }

        // Re-validate stock and availability for all cart items
        foreach ($cart->items as $cartItem) {
            $food = $cartItem->food;
            
            if (!$food->is_available) {
                return redirect()->route('cart.index')->with('error', "{$food->name} is currently unavailable. Please remove it from your cart.");
            }

            if ($food->stock_quantity !== null && $food->stock_quantity < $cartItem->quantity) {
                return redirect()->route('cart.index')->with('error', "Only {$food->stock_quantity} {$food->name} available in stock. Please adjust your quantity.");
            }
        }

        DB::transaction(function () use ($request, $cart) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'restaurant_id' => $cart->restaurant_id,
                'status' => 'pending',
                'subtotal' => $cart->subtotal,
                'delivery_fee' => $cart->delivery_fee,
                'tax' => $cart->tax,
                'total' => $cart->total,
                'delivery_address' => $request->delivery_address,
                'delivery_phone' => $request->delivery_phone,
                'delivery_notes' => $request->delivery_notes,
                'estimated_delivery_time' => now()->addMinutes($cart->restaurant->estimated_delivery_time),
            ]);

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $cartItem->food_id,
                    'food_name' => $cartItem->food->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'subtotal' => $cartItem->subtotal,
                    'options' => $cartItem->options,
                ]);

                $cartItem->food->increment('order_count', $cartItem->quantity);
                
                // Decrement stock quantity if set
                if ($cartItem->food->stock_quantity !== null) {
                    $cartItem->food->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'amount' => $order->total,
            ]);

            // Process payment for cash_on_delivery and mobile money synchronously
            if (in_array($request->payment_method, ['cash_on_delivery', 'mtn_mobile_money', 'airtel_money'])) {
                $paymentService = new PaymentService();
                $paymentData = [
                    'order_id' => $order->id,
                    'amount' => $order->total,
                    'phone' => $request->delivery_phone,
                    'user_id' => Auth::id(),
                ];

                try {
                    $result = $paymentService->processPayment($request->payment_method, $paymentData);
                    
                    $payment->update([
                        'status' => $result['success'] ? 'completed' : 'failed',
                        'transaction_id' => $result['transaction_id'] ?? null,
                        'payment_reference' => $result['reference'] ?? null,
                        'payment_details' => $result['details'] ?? [],
                        'paid_at' => $result['success'] ? now() : null,
                        'failure_reason' => $result['success'] ? null : ($result['error'] ?? 'Payment processing failed'),
                    ]);

                    if (!$result['success']) {
                        throw new \Exception('Payment failed: ' . ($result['error'] ?? 'Unknown error'));
                    }
                } catch (\Exception $e) {
                    $payment->update([
                        'status' => 'failed',
                        'failure_reason' => $e->getMessage(),
                    ]);
                    throw $e;
                }
            }

            Delivery::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'pickup_address' => $cart->restaurant->address,
                'delivery_address' => $request->delivery_address,
                'delivery_fee' => $cart->delivery_fee,
            ]);

            Notification::create([
                'user_id' => $cart->restaurant->user_id,
                'title' => 'New Order Received',
                'message' => "Order #{$order->order_number} has been placed.",
                'type' => 'order',
                'order_id' => $order->id,
            ]);

            $cart->items()->delete();
            $cart->update([
                'subtotal' => 0,
                'delivery_fee' => 0,
                'tax' => 0,
                'total' => 0,
                'restaurant_id' => null,
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Cancelled by customer',
        ]);

        Notification::create([
            'user_id' => $order->restaurant->user_id,
            'title' => 'Order Cancelled',
            'message' => "Order #{$order->order_number} has been cancelled by the customer.",
            'type' => 'order',
            'order_id' => $order->id,
        ]);

        return redirect()->back()->with('success', 'Order cancelled successfully!');
    }
}
