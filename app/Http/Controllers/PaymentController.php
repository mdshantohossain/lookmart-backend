<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function payment(Request $request, OrderService $orderService)
    {
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

            if (!$payment || !isset($payment['status'])) {
                throw new \Exception("Invalid payment response");
            }

            if ($payment['status'] !== 'COMPLETED') {
                return redirect()->away(config('services.frontend.url') . '/payment-failed');
            }


            DB::transaction(function () use ($payment, $orderService, $invoiceId) {
                // Prevent duplicate transactions
                if (Transaction::where('invoice_id', $invoiceId)->exists()) {
                    return;
                }

                $orderId = $payment['metadata']['order_id'];

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
                    'payment_method' => $payment['payment_method'] ?? null,
                    'sender_number' => $payment['sender_number'] ?? null,
                    'transaction_id' => $payment['transaction_id'] ?? null,
                    'status' => 1
                ]);

            });

            return redirect()->away(
                config('services.frontend.url') . '/order-success'
            );

        } catch (\Throwable $throwable) {
            report($throwable);

            return redirect()->away(
                config('services.frontend.url') . '/payment-error'
            );
        }
    }


    public function cancel()
    {
        return redirect()->away(
            config('services.frontend.url') . '/payment-cancel'
        );
    }
}
