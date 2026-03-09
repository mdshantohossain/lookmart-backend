<?php

namespace App\Services;

use App\Models\Admin\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingCharge;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * @param int $userId
     * @param array $data
     * @return array|null
     * @throws \Throwable
     */
    public function createOrder(int $userId, array $data): array|null
    {
        try {
            DB::beginTransaction();
            // calculate total order
            $productIts = array_column($data['products'], 'product_id');

            $orderTotal = 0; // initiate value

            // fetch all ordered product
            $products = Product::whereIn('id', $productIts)->get(['id', 'original_price', 'selling_price', 'discount', 'quantity'])->keyBy('id');

            foreach ($data['products'] as $item) {
                $product = $products[$item['product_id']];
                $finalPrice = $product->selling_price;

                if($product->discount) {
                    $discount = (float) rtrim($product->discount, '%');
                    $discountAmount = ($product->original_price * $discount) / 100;
                    $finalPrice = $product->original_price - $discountAmount;
                }

                // IMPORTANT: include quantity
                $orderTotal += $finalPrice * $item['quantity'];
            }

            // shipping charge
            $shipping = ShippingCharge::select('is_free', 'charge')->find($data['delivery_method']);
            $shippingCharge = $shipping->is_free ? 0 : $shipping->charge;

            $order = Order::create([
                'user_id' => $userId,
                'order_total' => $orderTotal + $shippingCharge,
                'order_timestamp' => now()->timestamp,
                'delivery_address' => $data['delivery_address'],
                'phone' => $data['phone'],
                'delivery_charge' => $shippingCharge,
                'payment_method' => $data['payment_method'],
                'slug' => Str::random(44),
                'order_status' => 0,
            ]);

            // order detail insert
            $orderDetails = [];
            foreach($data['products'] as $item) {
                $product = $products[$item['product_id']];

                // Check stock
                if($product->quantity) {
                    if ($product->quantity < $item['quantity']) {
                        throw new \Exception("Product out of stock");
                    }
                }

                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $product->selling_price,
                    'quantity' => $item['quantity'],
                    'variant_id' => $item['variant_id'],
                    'discount' => $product->discount,
                ];
            }

            OrderDetail::insert($orderDetails);

            DB::commit();

            return [
                'order' => $order,
                'orderTotal' => $orderTotal,
            ];
        } catch (\Throwable $th) {
            report($th);
            DB::rollBack();
            return null;
        }
    }

    public function confirmOrder(int $orderId)
    {
        DB::transaction(function () use ($orderId) {
            $order = Order::with('orderDetails.product')->find($orderId);

            foreach ($order->orderDetails  as $detail) {
                $product = $detail->product;

                if (!$product) {
                    throw new \Exception("Product not found");
                }

                // Check stock
                if($product->quantity) {
                    if ($product->quantity < $detail->quantity) {
                        throw new \Exception("Product out of stock");
                    }
                }

                // Reduce stock
                $product->decrement('quantity', $detail->quantity);
            }

            // mark order status as  processing
            $order->update(['order_status' => 1]);
        });
    }
}
