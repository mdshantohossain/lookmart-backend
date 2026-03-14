<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentGateWay;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected string $paymentApiKey = "payment.api.key";
    public function initiatePayment(Order $order)
    {
        $apiKey = Cache::remember($this->paymentApiKey, now()->addDay(), function () {
            return PaymentGateWay::value('api_key');
        });
        // if user pay cash on delivery

        $payable_amount = $order->payment_type ? $order->order_total : $order->delivery_charge;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'RT-UDDOKTAPAY-API-KEY' => $apiKey
        ])->post(config('services.payment.url'). 'api/checkout-v2', [
            'full_name' => $order->user->name,
            'email' => $order->user->email,
            'amount' => number_format($payable_amount, 2),
            'metadata' => (object)[
                'order_id' => $order->id,
                'order_slug' => $order->slug,
                'payment_type' => $order->payment_type
            ],
            'redirect_url' => route('payment.callback'),
            'cancel_url' => config('services.frontend.url') . '/payment-cancel',
        ]);

        return $response->json()['payment_url'] ?? null;
    }
}
