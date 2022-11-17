<?php

namespace App\Services;

use App\Models\Product;

class StripeService
{
    protected $baseUri;
    protected $secretKey;
    protected $publishableKey;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->secretKey = config('services.stripe.secret_key');
        $this->publishableKey = config('services.stripe.publishable_key');

        $this->setKeyApi($this->secretKey);
    }

    public function setKeyApi($key)
    {
        \Stripe\Stripe::setApiKey($key);
    }

    public function resolveCurrency($currency)
    {
        $zeroDecimalCurrencies = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return 1;
        }

        return 100;
    }

    public function sessionCheckout($lineProducts)
    {
        return \Stripe\Checkout\Session::create([
            'line_items' => $lineProducts,
            'mode' => 'payment',
            'success_url' => env('APP_URL') . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('APP_URL') . '/canceled',
        ]);
    }

    public function retrieveSession($sessionId)
    {
        return \Stripe\Checkout\Session::retrieve($sessionId);
    }

    public function retrieveCustomer($session)
    {
        return \Stripe\Customer::retrieve($session->customer);
    }
}
