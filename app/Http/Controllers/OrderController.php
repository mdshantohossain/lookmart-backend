<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
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
        }

       $order = $orderService->createOrder($userId, $data);

        $paymentUrl = $paymentService->initiatePayment($order['order']);

        // final response
        $response = [
            'success' => true,
            'data' => [
                'authResponse' => $authResponse,
                'payment_url' => $paymentUrl,
            ],
        ];

        return response()->json($response, 201);
    }
}
