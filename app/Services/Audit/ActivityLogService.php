<?php

namespace App\Services\Audit;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(string $action, string $description, array $data = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $data['metadata'] ?? null,
        ]);
    }

    public function logLogin(): ActivityLog
    {
        return $this->log('login', 'User logged in successfully');
    }

    public function logLogout(): ActivityLog
    {
        return $this->log('logout', 'User logged out');
    }

    public function logOrderCreated(int $orderId, float $amount): ActivityLog
    {
        return $this->log('order.created', "Order #{$orderId} created with total \${$amount}", [
            'model_type' => 'Order',
            'model_id' => $orderId,
            'metadata' => ['amount' => $amount],
        ]);
    }

    public function logOrderUpdated(int $orderId, string $status): ActivityLog
    {
        return $this->log('order.updated', "Order #{$orderId} status updated to {$status}", [
            'model_type' => 'Order',
            'model_id' => $orderId,
            'metadata' => ['status' => $status],
        ]);
    }

    public function logOrderCancelled(int $orderId, string $reason): ActivityLog
    {
        return $this->log('order.cancelled', "Order #{$orderId} cancelled: {$reason}", [
            'model_type' => 'Order',
            'model_id' => $orderId,
            'metadata' => ['reason' => $reason],
        ]);
    }

    public function logRestaurantCreated(int $restaurantId, string $name): ActivityLog
    {
        return $this->log('restaurant.created', "Restaurant '{$name}' created", [
            'model_type' => 'Restaurant',
            'model_id' => $restaurantId,
        ]);
    }

    public function logRestaurantUpdated(int $restaurantId, string $name): ActivityLog
    {
        return $this->log('restaurant.updated', "Restaurant '{$name}' updated", [
            'model_type' => 'Restaurant',
            'model_id' => $restaurantId,
        ]);
    }

    public function logFoodCreated(int $foodId, string $name): ActivityLog
    {
        return $this->log('food.created', "Food item '{$name}' created", [
            'model_type' => 'Food',
            'model_id' => $foodId,
        ]);
    }

    public function logFoodUpdated(int $foodId, string $name): ActivityLog
    {
        return $this->log('food.updated', "Food item '{$name}' updated", [
            'model_type' => 'Food',
            'model_id' => $foodId,
        ]);
    }

    public function logFoodDeleted(int $foodId, string $name): ActivityLog
    {
        return $this->log('food.deleted', "Food item '{$name}' deleted", [
            'model_type' => 'Food',
            'model_id' => $foodId,
        ]);
    }

    public function logPaymentProcessed(int $paymentId, string $method, float $amount): ActivityLog
    {
        return $this->log('payment.processed', "Payment of \${$amount} processed via {$method}", [
            'model_type' => 'Payment',
            'model_id' => $paymentId,
            'metadata' => ['method' => $method, 'amount' => $amount],
        ]);
    }

    public function logDeliveryAccepted(int $deliveryId): ActivityLog
    {
        return $this->log('delivery.accepted', "Delivery #{$deliveryId} accepted", [
            'model_type' => 'Delivery',
            'model_id' => $deliveryId,
        ]);
    }

    public function logDeliveryCompleted(int $deliveryId): ActivityLog
    {
        return $this->log('delivery.completed', "Delivery #{$deliveryId} completed", [
            'model_type' => 'Delivery',
            'model_id' => $deliveryId,
        ]);
    }

    public function logUserActivated(int $userId, string $name): ActivityLog
    {
        return $this->log('user.activated', "User '{$name}' activated", [
            'model_type' => 'User',
            'model_id' => $userId,
        ]);
    }

    public function logUserDeactivated(int $userId, string $name): ActivityLog
    {
        return $this->log('user.deactivated', "User '{$name}' deactivated", [
            'model_type' => 'User',
            'model_id' => $userId,
        ]);
    }

    public function getUserActivities(int $userId, int $limit = 50)
    {
        return ActivityLog::where('user_id', $userId)
                         ->latest()
                         ->paginate($limit);
    }

    public function getModelActivities(string $modelType, int $modelId, int $limit = 50)
    {
        return ActivityLog::where('model_type', $modelType)
                         ->where('model_id', $modelId)
                         ->latest()
                         ->paginate($limit);
    }

    public function getRecentActivities(int $limit = 20)
    {
        return ActivityLog::with('user')
                         ->latest()
                         ->take($limit)
                         ->get();
    }

    public function clearOldLogs(int $days = 90): void
    {
        ActivityLog::where('created_at', '<', now()->subDays($days))
                   ->delete();
    }
}
