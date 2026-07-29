<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\User;
use App\Jobs\SendNotification;

class NotificationService
{
    public function create(array $data): Notification
    {
        $notification = Notification::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'general',
            'order_id' => $data['order_id'] ?? null,
            'restaurant_id' => $data['restaurant_id'] ?? null,
            'delivery_id' => $data['delivery_id'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'is_read' => false,
        ]);

        // Dispatch notification job for sending
        SendNotification::dispatch($notification);

        return $notification;
    }

    public function sendOrderNotification(User $user, string $orderNumber, string $status): Notification
    {
        return $this->create([
            'user_id' => $user->id,
            'title' => "Order #{$orderNumber} Updated",
            'message' => "Your order #{$orderNumber} status has been updated to: {$status}",
            'type' => 'order',
            'action_url' => route('orders.show', $orderNumber),
        ]);
    }

    public function sendRestaurantNotification(User $user, string $message, ?int $orderId = null): Notification
    {
        return $this->create([
            'user_id' => $user->id,
            'title' => 'Restaurant Notification',
            'message' => $message,
            'type' => 'restaurant',
            'order_id' => $orderId,
            'action_url' => $orderId ? route('restaurant.admin.orders') : route('restaurant.admin.dashboard'),
        ]);
    }

    public function sendDeliveryNotification(User $user, string $message, ?int $deliveryId = null): Notification
    {
        return $this->create([
            'user_id' => $user->id,
            'title' => 'Delivery Update',
            'message' => $message,
            'type' => 'delivery',
            'delivery_id' => $deliveryId,
            'action_url' => $deliveryId ? route('delivery.show', $deliveryId) : route('delivery.dashboard'),
        ]);
    }

    public function sendSystemNotification(User $user, string $title, string $message): Notification
    {
        return $this->create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'system',
            'action_url' => route('dashboard'),
        ]);
    }

    public function broadcastToRole(string $role, string $title, string $message, array $data = []): void
    {
        $users = User::where('role', $role)->where('is_active', true)->get();

        foreach ($users as $user) {
            $this->create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => $data['type'] ?? 'system',
                'order_id' => $data['order_id'] ?? null,
                'restaurant_id' => $data['restaurant_id'] ?? null,
                'delivery_id' => $data['delivery_id'] ?? null,
                'action_url' => $data['action_url'] ?? null,
            ]);
        }
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
                                   ->where('user_id', $userId)
                                   ->first();

        if (!$notification) {
            return false;
        }

        $notification->update(['is_read' => true, 'read_at' => now()]);
        return true;
    }

    public function markAllAsRead(int $userId): void
    {
        Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
                         ->where('is_read', false)
                         ->count();
    }

    public function getUserNotifications(int $userId, int $limit = 20)
    {
        return Notification::where('user_id', $userId)
                         ->latest()
                         ->paginate($limit);
    }

    public function deleteNotification(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
                                   ->where('user_id', $userId)
                                   ->first();

        if (!$notification) {
            return false;
        }

        $notification->delete();
        return true;
    }

    public function clearOldNotifications(int $userId, int $days = 30): void
    {
        Notification::where('user_id', $userId)
                    ->where('created_at', '<', now()->subDays($days))
                    ->delete();
    }
}
