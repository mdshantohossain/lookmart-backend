<?php

namespace App\Services;

use App\Models\Admin\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderService
{
    public function ensureUserExistsAndAuthenticated(Request $request, MailService $mailService): User | Authenticatable
    {
        if(auth()->check()) {
            return auth()->user();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'password' => 'required|min:6|max:13',
                'delivery_address' => 'required',
            ], [
                'delivery_address.required' => 'Delivery address is required field',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
            ]);

            // Optionally send verification email
            // $mailService->sendVerificationMail($user);
        }

        Auth::login($user);

        return $user;
    }

    public function updatePhoneIfMissing(User $user, ?string $phone): void
    {
        if (!$user->phone && !empty($phone)) {
            $user->update(['phone' => $phone]);
        }
    }

    public function validateDeliveryAddress(Request $request, User $user): array
    {
        $rules  = [
            'delivery_address' => 'required',
        ];

        if (!$user->phone) {
            $rules['phone'] = 'required|unique:users,phone';
        }

        return $request->validate($rules);
    }

    public function placeCashOrder(User $user, Request $request, float $shippingCost): Order
    {
        return  Order::create([
            'user_id' => $user->id,
            'order_total' => (float) str_replace(',', '', Cart::subTotal()),
            'order_timestamp' => now()->timestamp,
            'order_status' => 0,
            'delivery_address' => $request->delivery_address,
            'delivery_charge' => $shippingCost,
            'delivery_within' => $request->delivery_within,
            'payment_method' => $request->payment_method,
            'slug' => Str::random(44),
        ]);
    }

    public function placeOnlineOrder(array $post_data): Order
    {
       $order = Order::updateOrCreate(
            ['transaction_id' => $post_data['tran_id']],
            [
                'user_id' => auth()->id(),
                'order_total' => $post_data['total_amount'],
                'order_status' => 0,
                'order_timestamp' => now()->timestamp,

                'delivery_address' => session('delivery_address'),
                'delivery_within' => session('delivery_within'),
                'delivery_charge' => session('shipping_cost'),

                'payment_method' => 1,
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'slug' => Str::random(44),
            ]
        );

       session()->forget('delivery_address');
       session()->forget('delivery_within');
       session()->forget('shipping_cost');

       return $order;
    }

    public function handleCartItems(Order $order): void
    {
        foreach (Cart::content() as $item) {
            $product = Product::findOrFail($item->id);
            $product->decrement('quantity', $item->qty);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $item->qty,
            ]);

            Cart::remove($item->rowId);
        }
    }

    public function storeSessionData(Request $request, float $shippingCost): void
    {
        session()->put([
            'shipping_cost' =>  $shippingCost,
            'delivery_address' => $request->delivery_address,
            'payment_method'  => $request->payment_method,
            'delivery_within' =>  $request->delivery_within,
        ]);
    }
}
