<?php

namespace App\Services\Payment;

class CashOnDeliveryPayment implements PaymentInterface
{
    public function processPayment(array $paymentData): array
    {
        // Cash on delivery doesn't require actual payment processing
        // Just return a successful response
        return [
            'success' => true,
            'transaction_id' => 'COD-' . uniqid(),
            'status' => 'pending',
            'message' => 'Cash on delivery payment recorded successfully',
        ];
    }

    public function verifyPayment(string $transactionId): bool
    {
        // COD payments are verified when the order is delivered
        return true;
    }

    public function refundPayment(string $transactionId, float $amount): bool
    {
        // COD refunds are handled manually by the restaurant
        return true;
    }
}
