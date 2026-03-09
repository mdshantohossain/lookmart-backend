<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGatewayRequest;
use App\Models\PaymentGateWay;
use App\Models\Transaction;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class PaymentController extends Controller
{
    protected string $gatewayKey = 'api_key';

    public function create()
    {
        $cached = Redis::get($this->gatewayKey);

        if($cached) {
            $setting = json_decode($cached);
        } else {
            $setting = $this->getPaymentGateway();

            Redis::set($this->gatewayKey, json_encode($setting));

        }
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

        try {
            $invoiceId = $request->get('invoice_id');

            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'RT-UDDOKTAPAY-API-KEY' => config('services.payment.api_key')
            ])->post(config('services.payment.url'). 'api/verify-payment', [
                'invoice_id' => $invoiceId,
            ]);

            $payment = $res->json();

            logger($payment);

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

                // Confirm order (reduce stock)
                $orderService->confirmOrder($orderId);

                // Save transaction
                Transaction::create([
                    'user_id' => $payment['metadata']['user_id'],
                    'order_id' => $orderId,
                    'payable_amount' => $payment['amount'],
                    'fee' => $payment['fee'] ?? 0,
                    'charged_amount' => $payment['charged_amount'] ?? 0,
                    'invoice_id' => $invoiceId,
                    'bank_type' => $payment['payment_method'] ?? null,
                    'paid_at' => now()->timestamp,
                    'sender_number' => $payment['sender_number'] ?? null,
                    'transaction_id' => $payment['transaction_id'] ?? null,
                    'status' => 1
                ]);
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
        return PaymentGateWay::first();
    }

    public function revalidateCache()
    {
        $gateway = $this->getPaymentGateway();
        Redis::set($this->gatewayKey, json_encode($gateway));
    }
}
