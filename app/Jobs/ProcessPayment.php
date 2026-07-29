<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessPayment implements ShouldQueue
{
    use Queueable;

    protected $payment;
    protected $paymentData;

    public function __construct(Payment $payment, array $paymentData)
    {
        $this->payment = $payment;
        $this->paymentData = $paymentData;
    }

    public function handle(PaymentService $paymentService): void
    {
        try {
            $result = $paymentService->processPayment(
                $this->payment->payment_method,
                $this->paymentData
            );

            if ($result['success']) {
                $this->payment->update([
                    'status' => $result['status'],
                    'transaction_id' => $result['transaction_id'],
                    'processed_at' => now(),
                ]);

                // Update order status if payment is completed
                if ($result['status'] === 'completed') {
                    $this->payment->order->update([
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                }
            } else {
                $this->payment->update([
                    'status' => 'failed',
                    'failure_reason' => $result['message'],
                    'processed_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            $this->payment->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
                'processed_at' => now(),
            ]);
        }
    }
}
