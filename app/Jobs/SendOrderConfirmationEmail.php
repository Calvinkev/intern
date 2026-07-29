<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        $user = $this->order->user;
        
        Mail::raw(
            "Dear {$user->name},\n\n" .
            "Thank you for your order #{$this->order->order_number}!\n\n" .
            "Order Details:\n" .
            "Total: \${$this->order->total}\n" .
            "Status: {$this->order->status}\n\n" .
            "We will notify you when your order is ready for delivery.\n\n" .
            "Best regards,\nCodeBase Food Ordering",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject("Order Confirmation - #{$this->order->order_number}");
            }
        );
    }
}
