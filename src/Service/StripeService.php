<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct(protected string $secretKey, protected string $publicKey)
    {
    }

    public function getPaymentIntent($amount): ?PaymentIntent
    {
        Stripe::setApiKey($this->secretKey);

        $paymentIntent  = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
        ]);

        return $paymentIntent;
    }

    // Getters
    public function getSecretKey(): string
    {
        return $this->secreKey;
    }

    public function getpublicKey(): string
    {
        return $this->publicKey;
    }
}
