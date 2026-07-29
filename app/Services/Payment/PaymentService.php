<?php

namespace App\Services\Payment;

class PaymentService
{
    protected $paymentMethods = [
        'cash_on_delivery' => CashOnDeliveryPayment::class,
        'mtn_mobile_money' => MobileMoneyPayment::class,
        'airtel_money' => MobileMoneyPayment::class,
        'stripe' => StripePayment::class,
        'card' => StripePayment::class,
    ];

    public function getPaymentMethod(string $method): PaymentInterface
    {
        if (!isset($this->paymentMethods[$method])) {
            throw new \InvalidArgumentException("Payment method '{$method}' is not supported.");
        }

        $paymentClass = $this->paymentMethods[$method];
        
        if ($method === 'airtel_money') {
            return new $paymentClass('airtel');
        }

        return new $paymentClass();
    }

    public function processPayment(string $method, array $paymentData): array
    {
        $paymentProcessor = $this->getPaymentMethod($method);
        return $paymentProcessor->processPayment($paymentData);
    }

    public function verifyPayment(string $method, string $transactionId): bool
    {
        $paymentProcessor = $this->getPaymentMethod($method);
        return $paymentProcessor->verifyPayment($transactionId);
    }

    public function refundPayment(string $method, string $transactionId, float $amount): bool
    {
        $paymentProcessor = $this->getPaymentMethod($method);
        return $paymentProcessor->refundPayment($transactionId, $amount);
    }

    public function getSupportedMethods(): array
    {
        return array_keys($this->paymentMethods);
    }
}
