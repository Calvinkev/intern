<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class GenerateDailyReport implements ShouldQueue
{
    use Queueable;

    protected $date;

    public function __construct(string $date = null)
    {
        $this->date = $date ?? now()->toDateString();
    }

    public function handle(): void
    {
        $orders = Order::whereDate('created_at', $this->date)->get();
        $totalRevenue = $orders->where('status', 'delivered')->sum('total');
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'delivered')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();

        $report = "Daily Report for {$this->date}\n\n";
        $report .= "Total Orders: {$totalOrders}\n";
        $report .= "Completed Orders: {$completedOrders}\n";
        $report .= "Pending Orders: {$pendingOrders}\n";
        $report .= "Total Revenue: \${$totalRevenue}\n\n";

        // Top restaurants
        $topRestaurants = Restaurant::withCount(['orders' => function ($query) {
            $query->whereDate('created_at', $this->date);
        }])->orderByDesc('orders_count')->take(5)->get();

        $report .= "Top Restaurants:\n";
        foreach ($topRestaurants as $restaurant) {
            $report .= "- {$restaurant->name}: {$restaurant->orders_count} orders\n";
        }

        // Send report to system admin
        $adminEmail = config('mail.admin_email', 'admin@codebase.com');
        
        Mail::raw($report, function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                    ->subject("Daily Report - {$this->date}");
        });
    }
}
