<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotification implements ShouldQueue
{
    use Queueable;

    protected $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function handle(): void
    {
        // In production, this would send push notifications, SMS, or emails
        // For now, we'll just mark the notification as sent
        $this->notification->update([
            'is_read' => false,
            'sent_at' => now(),
        ]);

        // You can integrate with services like:
        // - Firebase Cloud Messaging (FCM) for push notifications
        // - Twilio for SMS
        // - SendGrid/Mailgun for email notifications
    }
}
