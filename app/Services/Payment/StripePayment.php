<?php

namespace App\Services\Payment;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer as StripeCustomer;

class StripePayment implements PaymentInterface
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('payment.stripe.secret_key');
        Stripe::setApiKey($this->secretKey);
    }

    public function processPayment(array $paymentData): array
    {
        try {
            $token = $paymentData['token'] ?? null;
            $amount = $paymentData['amount'] ?? 0;
            $currency = $paymentData['currency'] ?? 'usd';
            $description = $paymentData['description'] ?? 'Order payment';

            if (!$token || !$amount) {
                return [
                    'success' => false,
                    'transaction_id' => null,
                    'status' => 'failed',
                    'message' => 'Invalid payment data',
                ];
            }

            $charge = Charge::create([
                'amount' => $amount * 100, // Stripe uses cents
                'currency' => $currency,
                'source' => $token,
                'description' => $description,
            ]);

            return [
                'success' => true,
                'transaction_id' => $charge->id,
                'status' => 'completed',
                'message' => 'Stripe payment processed successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'transaction_id' => null,
                'status' => 'failed',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $transactionId): bool
    {
        try {
            $charge = Charge::retrieve($transactionId);
            return $charge->status === 'succeeded';
        } catch (\Exception $e) {
            return false;
        }
    }

    public function refundPayment(string $transactionId, float $amount): bool
    {
        try {
            $refund = \Stripe\Refund::create([
                'charge' => $transactionId,
                'amount' => $amount * 100, // Stripe uses cents
            ]);
            return $refund->status === 'succeeded';
        } catch (\Exception $e) {
            return false;
        }
    }
}
