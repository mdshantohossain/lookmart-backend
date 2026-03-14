<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGatewayRequest;
use App\Models\Order;
use App\Models\PaymentGateWay;
use App\Models\Transaction;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected string $cachedKey = 'payment.gateway', $paymentApiKey = 'payment.api.key';

    public function create()
    {
        $setting = $this->getPaymentGateway();

        return view('admin.payment.index', compact('setting'));
    }

    public function store(PaymentGatewayRequest $request)
    {
        $gateway = $this->getPaymentGateway();

        if($gateway) {
            $gateway->update($request->validated());
            $message = 'Payment Gateway updated successful';
        } else {
            PaymentGateWay::create($request->validated());
            $message = 'Payment Gateway created successful';
        }

        // revalidate cache
        $this->revalidateCache();

        return redirect()->route('dashboard')->with('success', $message);
    }

    public function payment(Request $request, OrderService $orderService)
    {
        $slug = null;

        $apiKey = Cache::remember($this->paymentApiKey, now()->addDay(), function () {
            return PaymentGateWay::value('api_key');
        });

        try {
            $invoiceId = $request->get('invoice_id');

            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'RT-UDDOKTAPAY-API-KEY' => $apiKey
            ])->post(config('services.payment.url'). 'api/verify-payment', [
                'invoice_id' => $invoiceId,
            ]);

            $payment = $res->json();

            if (!$payment || !isset($payment['status'])) {
                throw new \Exception("Invalid payment response");
            }

            if ($payment['status'] !== 'COMPLETED') {
                return redirect()->away(config('services.frontend.url') . '/payment-failed');
            }

            DB::transaction(function () use ($payment, $orderService, $invoiceId, &$slug) {
                // Prevent duplicate transactions
                if (Transaction::where('invoice_id', $invoiceId)->exists()) {
                    return;
                }

                $orderId = $payment['metadata']['order_id'];
                $slug = $payment['metadata']['order_slug'];
                $paymentType = $payment['metadata']['payment_type'];

                // Confirm order (reduce stock)
                $orderService->confirmOrder($orderId);

                // Save transaction
                $transaction = Transaction::create([
                    'order_id' => $orderId,
                    'payable_amount' => $payment['amount'],
                    'fee' => $payment['fee'] ?? 0,
                    'charged_amount' => $payment['charged_amount'],
                    'invoice_id' => $invoiceId,
                    'sender_number' => $payment['sender_number'],
                    'bank_type' => $payment['payment_method'] ?? null,
                    'transaction_id' => $payment['transaction_id'] ?? null,
                    'status' => 1
                ]);

                $order = Order::find($orderId);
                if($transaction && $paymentType) {
                    $order->update([
                        'payment_status' => 1,
                        'charge_status' => 1,
                        'paid_at' => now()
                    ]);
                } else {
                    $order->update([
                        'charge_status' => 1,
                        'paid_at' => now()
                    ]);
                }
            });

            return redirect()->away(
                config('services.frontend.url') . "/order-success?token=$slug"
            );

        } catch (\Throwable $throwable) {
            report($throwable);

            return redirect()->away(
                config('services.frontend.url') . "/payment-error?token=$slug"
            );
        }
    }

    public function getPaymentGateway(): ?PaymentGateway
    {
        return Cache::remember($this->cachedKey, now()->addDays(7), function () {
            return PaymentGateWay::first();
        });
    }

    public function revalidateCache()
    {
        Cache::forget($this->cachedKey);
    }
}
