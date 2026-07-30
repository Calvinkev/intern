<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function dashboard()
    {
        $availableDeliveries = Delivery::pending()
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['confirmed', 'preparing', 'ready']);
            })
            ->with('order.restaurant', 'order.user')
            ->latest()
            ->get();

        $myDeliveries = Delivery::where('delivery_personnel_id', Auth::id())
            ->with('order.restaurant', 'order.user')
            ->latest()
            ->get();

        $completedDeliveries = $myDeliveries->where('status', 'delivered')->count();
        $totalEarnings = $myDeliveries->where('status', 'delivered')->sum('delivery_fee');

        return view('delivery.dashboard', compact(
            'availableDeliveries',
            'myDeliveries',
            'completedDeliveries',
            'totalEarnings'
        ));
    }

    public function acceptDelivery($id)
    {
        // Use atomic update with where clause to prevent race condition
        $affected = Delivery::where('id', $id)
            ->where('status', 'pending')
            ->update([
                'delivery_personnel_id' => Auth::id(),
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

        if ($affected === 0) {
            return redirect()->back()->with('error', 'This delivery is no longer available.');
        }

        return redirect()->route('delivery.dashboard')->with('success', 'Delivery accepted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:picked_up,delivered',
        ]);

        $delivery = Delivery::where('id', $id)
                           ->where('delivery_personnel_id', Auth::id())
                           ->firstOrFail();

        $delivery->update([
            'status' => $request->status,
            'picked_up_at' => $request->status == 'picked_up' ? now() : $delivery->picked_up_at,
            'delivered_at' => $request->status == 'delivered' ? now() : $delivery->delivered_at,
        ]);

        // Sync order status with delivery status
        if ($request->status == 'picked_up') {
            $delivery->order->update([
                'status' => 'out_for_delivery',
                'picked_up_at' => now(),
            ]);

            // Notify customer that order is out for delivery
            Notification::create([
                'user_id' => $delivery->order->user_id,
                'title' => 'Order Out for Delivery',
                'message' => 'Your order has been picked up and is on its way!',
                'type' => 'order',
                'order_id' => $delivery->order->id,
            ]);
        } elseif ($request->status == 'delivered') {
            $delivery->order->update([
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);

            // Notify customer that order has been delivered
            Notification::create([
                'user_id' => $delivery->order->user_id,
                'title' => 'Order Delivered',
                'message' => 'Your order has been delivered successfully!',
                'type' => 'order',
                'order_id' => $delivery->order->id,
            ]);
        }

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    public function showDelivery($id)
    {
        $delivery = Delivery::where('id', $id)
                           ->where('delivery_personnel_id', Auth::id())
                           ->with('order.restaurant', 'order.user', 'order.items.food')
                           ->firstOrFail();

        return view('delivery.show', compact('delivery'));
    }
}
