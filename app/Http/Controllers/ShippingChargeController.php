<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingChargeRequest;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Redis;

class ShippingChargeController extends Controller
{
    public string $activeShippingKey = 'active_shipping', $shippingKey = 'all';

    public function index()
    {
        return view('admin.shipping-charge.index', [
            'shippingCharges' => $this->getShipping(),
        ]);
    }

    public function create()
    {
        return view('admin.shipping-charge.create');
    }

    public function store(ShippingChargeRequest $request)
    {
        try {

        ShippingCharge::create($request->validated());

        // revalidating shipping cached
        $this->revalidateShippingCharge();

        return redirect('/shipping')->with('success', 'Shipping Charge Created Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(ShippingCharge $shipping)
    {
        return view('admin.shipping-charge.edit', compact('shipping'));
    }

    public function update(ShippingChargeRequest $request, ShippingCharge $shipping)
    {
        try {
            $shipping->update($request->validated());

            // revalidating shipping cached
            $this->revalidateShippingCharge();

            return redirect('/shipping')->with('success', 'Shipping Charge Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ShippingCharge $shipping)
    {
        try {
            $shipping->delete();

            // revalidating shipping cached
            $this->revalidateShippingCharge();

            return back()->with('success', 'Shipping charge deleted!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function revalidateShippingCharge()
    {
        $shipping = $this->getShipping();

        $activeShipping = $shipping->filter(fn ($shipping) => $shipping->status == '1');

        // set active shipping
        Redis::set($this->activeShippingKey, json_encode([
            'success' => true,
            'data' => $activeShipping
        ]));

        // set all shipping
        Redis::set($this->shippingKey, json_encode($shipping));
    }

    // fetch all shipping
    public function getShipping()
    {
        $cached = Redis::get($this->shippingKey);

        if($cached) {
            $shipping = collect(json_decode($cached));
        } else {
            $shipping = ShippingCharge::all();
            Redis::set($this->shippingKey, json_encode($shipping));
        }

        return $shipping;
    }

    public function getActiveShipping()
    {
        // retrieve if cache exists
        $cached = Redis::get($this->activeShippingKey);

        if ($cached) {
            return collect(json_decode($cached, true));
        } else {
            // fetch all active shipping and cached
            $activeShipping = ShippingCharge::where('status', 1)->get();

            $response = [
                'success' => true,
                'data' => $activeShipping
            ];

            // cached
            Redis::set($this->activeShippingKey, json_encode($response));

            return $response;
        }
    }

}
