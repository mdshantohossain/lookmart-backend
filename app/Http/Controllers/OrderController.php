<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Order;
use App\Models\ShippingCharge;
use App\Models\User;
use App\Services\AuthService;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function placeOrder(
        OrderRequest $request,
        AuthService $authService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $data = $request->validated();

        $authResponse = null;

        // authentication
        if(empty($data['user_id'])) {
            $user = User::where('email', $data['email'])->first();

            // register if user not exists
            if(!$user) {
                $userCredential = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' =>$data['password'],
                    'phone' => $data['phone'],
                ];

                // register
                $user = $authService->register($userCredential);

                $userId = $user->id;

                // set auth response type as register
                $authResponse['type'] = 'registered';
                $authResponse['message'] = 'Registered successfully. Check your email for verify your email address';

            } else {
                // login if exists user
                $existsUser = [
                  'email' => $data['email'],
                  'password' => $data['password'],
                  'phone' => $data['phone'],
                ];

                if (!Hash::check($data['password'], $user->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => "You're already register user. Login then order again",
                    ], 400);
                }

                // user login
                $response = $authService->login($existsUser);

                $userId = $response['payload']['user']['id'];

                // set auth response type as register
                $authResponse['type'] = 'login';
                $authResponse['message'] = 'You was registered user. Login successfully';
                $authResponse['data'] = $response['payload'];
            }
        } else {
            $userId = $data['user_id'];
            $user = User::find($userId);
            if(!$user->phone) {
                $user->update(['phone' => $data['phone']]);
            }
        }

        // create initiate order
       $order = $orderService->createOrder($userId, $data);

       // generate payment url
       $paymentUrl = $paymentService->initiatePayment($order['order']);

        // final response
        $response = [
            'success' => true,
            'message' => 'Order placed successfully',
            'data' => [
                'auth_response' => $authResponse,
                'payment_url' => $paymentUrl,
            ],
        ];

        return response()->json($response, 201);
    }

    public function verifyOrder(string $slug)
    {
        $order = Order::with('payment')->where('slug', $slug)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order.'
            ]);
        }

        if($order->payment && $order->payment?->status == '1') {
            return response()->json([
                'success' => true,
                'message' => 'Your order has been placed successfully. We’ve received your payment and your order is now being processed. You can track your order status from your dashboard.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Your order has been placed successfully. But payment is still pending. You can retry the payment from your dashboard to complete your order."
        ]);
    }
}
