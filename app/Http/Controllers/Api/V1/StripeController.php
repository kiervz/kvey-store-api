<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\StripeService;
use App\Http\Resources\Cart\CartResource;

use App\Models\Order;

use Auth;
use Ulid\Ulid;

class StripeController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function cartItems()
    {
        return Auth::user()->selectedCartItems;
    }

    public function updateCartItems($cartItems)
    {
        foreach ($cartItems as $cartItem) {
            $cartItem->update(['status' => 'C']);
        }
    }

    public function createOrder($session_id, $totalAmount, $cartItems)
    {
        $order = Order::create([
            'ulid' => Ulid::generate(),
            'user_id' => Auth::user()->id,
            'status' => Order::UNPAID,
            'total_amount' => $totalAmount,
            'type' => 'STRIPE', // it could be set dynamically
            'session_id' => $session_id,
            'submit_at' => now()
        ]);

        foreach ($cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem['product_id'],
                'price' => $cartItem->product['actual_price'],
                'qty' => $cartItem['qty'],
                'total_price' => $cartItem->product['actual_price'] * $cartItem['qty']
            ]);
        }
    }

    public function checkout()
    {
        $cartItems = $this->cartItems();
        $lineProducts = [];
        $currency = 'php'; // it could be set dynamically
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $totalAmount += $item['qty'] * (float)$item->product['actual_price'];

            $lineProducts[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $item->product['name'],
                        'images' => [$item->product->productImages[0]['url']]
                    ],
                    'unit_amount' => (float)$item->product['actual_price'] * $this->stripeService->resolveCurrency($currency),
                ],
                'quantity' => $item['qty'],
            ];
        }

        $session = $this->stripeService->sessionCheckout($lineProducts);

        $this->createOrder($session->id, $totalAmount, $cartItems);
        $this->updateCartItems($cartItems);

        return $session->url;
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        try {
            $session = $this->stripeService->retrieveSession($sessionId);

            if (!$session) return $this->customResponse('Not Found.', [], Response::HTTP_NOT_FOUND, false);

            $order = Order::where('session_id', $sessionId)->first();

            if (!$order) return $this->customResponse('Not Found.', [], Response::HTTP_NOT_FOUND, false);

            $order->update(['status' => 'PAID']);

            return $this->customResponse('success');
        } catch (\Exception $ex) {
            return $this->customResponse('Not Found.', [], Response::HTTP_NOT_FOUND, false);
        }
    }

    public function webhook()
    {
        $endpoint_secret = config('services.stripe.webhook_key');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = $this->stripeService->constructEvent($payload, $sig_header, $endpoint_secret);
        } catch(\UnexpectedValueException $e) {
            return $this->customResponse('Invalid payload', [], Response::HTTP_BAD_REQUEST);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return $this->customResponse('Invalid signature', [], Response::HTTP_BAD_REQUEST);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $order = Order::where('session_id', $session->id)->first();

                if ($order && $order->status === Order::UNPAID) {
                    $order->update(['status' => Order::PAID]);
                }

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return $this->customResponse('success');
    }
}
