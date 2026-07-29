<?php

namespace App\Services\Payment;

interface PaymentInterface
{
    public function processPayment(array $paymentData): array;
    public function verifyPayment(string $transactionId): bool;
    public function refundPayment(string $transactionId, float $amount): bool;
}
