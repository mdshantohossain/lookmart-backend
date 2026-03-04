<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    public function initiatePayment(Order $order)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'RT-UDDOKTAPAY-API-KEY' => config('services.payment.api_key')
        ])->post(config('services.payment.url'). 'api/checkout-v2', [
            'full_name' => $order->user->name,
            'email' => $order->user->email,
            'amount' => number_format($order->order_total, 2, '.', ''),
            'metadata' => (object)[
                'order_id' => $order->id,
                 'user_id' => $order->user->id
            ],
            'redirect_url' => route('payment.callback'),
            'cancel_url' => route('payment.cancel'),
        ]);

        logger()->info('Response: ' . $response->body());

        return $response->json()['payment_url'] ?? null;
    }
}
