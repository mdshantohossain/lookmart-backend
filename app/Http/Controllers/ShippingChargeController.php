<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingChargeRequest;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;

class ShippingChargeController extends Controller
{

    public function index()
    {
        return view('admin.shipping-charge.index', [
            'shippingCharges' => ShippingCharge::all(),
        ]);
    }

    public function create()
    {
        return view('admin.shipping-charge.create');
    }

    public function store(ShippingChargeRequest $request)
    {
        try {

        ShippingCharge::create($request->all());

        return redirect('/shipping')->with('success', 'Shipping Charge Created Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(ShippingCharge $shipping)
    {
        return view('admin.shipping-charge.edit', compact('shipping'));
    }

    public function update(ShippingCharge $shipping, Request $request)
    {
        try {
            $shipping->update($request->all());

            return redirect('/shipping')->with('success', 'Shipping Charge Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ShippingCharge $shipping)
    {
        try {
            $shipping->delete();
            return back()->with('success', 'Shipping charge deleted!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
