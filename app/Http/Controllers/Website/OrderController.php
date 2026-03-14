<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Services\MailService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.order.index', [
            'orders' => Order::with('user')->latest()->get(),
        ]);
    }

    public function show(Order $order): View
    {// check permission of current user
        isAuthorized('order show');

        $order->load(['user', 'orderDetails', 'orderDetails.product']);
        return view('admin.order.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        // check permission of current user
        isAuthorized('order edit');

        $order->load('user');
        return view('admin.order.edit', compact('order'));
    }

    public function update(OrderUpdateRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        // check permission of current user
        isAuthorized('order update');

        if($request->order_status == 1) {
            $order->order_status = 1;
            $order->phone = $request->phone;
            $order->delivery_address = $validated->delivery_address;
            $order->save();

            return redirect('/order')->with('success', 'Order updated successful.');

        } elseif($request->order_status == 2) {
            $order->order_status = 2;
            $order->delivery_at = now();

            if($order->payment_type == 0) {
                $order->payment_status = 1;
                $order->paid_at = now();
            }

            $order->save();

            return redirect('/order')->with('success', 'Order delivered successful.');
        } elseif($request->order_status == 3) {
            $order->order_status = 3;
            $order->payment_status = 0;

            $order->save();
            return redirect('/order')->with('warning', 'Order canceled');
        } else {
            return redirect('/order')->with('warning', 'Nothing changed.');
        }
    }

    public function destroy(Order $order): RedirectResponse
    {
        // check permission of current user
        isAuthorized('order destroy');

        try {
            $order->delete();
            return back()->with('success', 'Order deleted successfully');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function invoice(Order $order): Response
    {
        // check permission of current user
        isAuthorized('order invoice download');

        $order->load(['user', 'orderDetails','orderDetails.product']);
        $pdf = Pdf::loadView('admin.order.order-invoice', compact('order'));
        return $pdf->download('invoice-'.$order->id.'.pdf');
    }
}
