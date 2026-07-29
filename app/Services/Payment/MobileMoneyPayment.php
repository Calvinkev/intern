<?php

namespace App\Services\Payment;

class MobileMoneyPayment implements PaymentInterface
{
    protected $provider;
    protected $apiKey;
    protected $apiSecret;

    public function __construct(string $provider = 'mtn')
    {
        $this->provider = $provider;
        $this->apiKey = config('payment.mobile_money.' . $provider . '.api_key');
        $this->apiSecret = config('payment.mobile_money.' . $provider . '.api_secret');
    }

    public function processPayment(array $paymentData): array
    {
        // Simulate mobile money payment processing
        // In production, this would make API calls to the mobile money provider
        $phoneNumber = $paymentData['phone'] ?? null;
        $amount = $paymentData['amount'] ?? 0;

        if (!$phoneNumber || !$amount) {
            return [
                'success' => false,
                'transaction_id' => null,
                'status' => 'failed',
                'message' => 'Invalid payment data',
            ];
        }

        // Simulate API call
        $transactionId = strtoupper($this->provider) . '-' . uniqid();

        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'status' => 'completed',
            'message' => 'Mobile money payment processed successfully',
        ];
    }

    public function verifyPayment(string $transactionId): bool
    {
        // In production, this would verify the transaction with the mobile money provider
        return true;
    }

    public function refundPayment(string $transactionId, float $amount): bool
    {
        // In production, this would initiate a refund through the mobile money provider
        return true;
    }
}
