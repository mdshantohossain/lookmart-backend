<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingChargeRequest;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Cache;

class ShippingChargeController extends Controller
{
    protected string $apiCachedKey = 'api.shipping', $cachedKey = 'all.shipping';

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
        $this->invalidateCache();

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
            $this->invalidateCache();

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
            $this->invalidateCache();

            return back()->with('success', 'Shipping charge deleted!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invalidateCache()
    {
        Cache::forget($this->cachedKey);
        Cache::forget($this->apiCachedKey);
    }

    // fetch all shipping
    public function getShipping()
    {
       return Cache::remember($this->cachedKey, now()->addHours(1), function () {
           return ShippingCharge::all();
       });
    }

    public function getShippingMethods()
    {
        $shipping = Cache::remember($this->apiCachedKey, now()->addDays(1), function () {
           return ShippingCharge::where('status', 1)->get();
        });

            return response()->json([
                'success' => true,
                'data' => $shipping
            ]);
        }

}
