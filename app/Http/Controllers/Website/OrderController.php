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
            'orders' => Order::with('user')->get(),
        ]);
    }

    public function store(Request $request, OrderService $orderService, MailService $mailService)
    {
        // check permission of current user
        isAuthorized('order destroy');

        $user = $orderService->ensureUserExistsAndAuthenticated($request, $mailService);

        if ($user->role != 'user') {
            abort(403);
        }

        // get current delivery charge
        $shippingCost = getDeliveryCharge($request->delivery_method);

        // validate delivery address
        $orderService->validateDeliveryAddress($request, $user);

        // Update phone if missing
        $orderService->updatePhoneIfMissing($user, $request->phone);

        if($request->payment_method == 0 && $shippingCost == 0) {
            // order placed
            $order = $orderService->placeCashOrder($user, $request, $shippingCost);

            // create order detail and remove cart item
            $orderService->handleCartItems($order);

            return redirect('/profile')->with('success', 'Order placed successfully');
        } else {
            // store session data to handle online  payment
            $orderService->storeSessionData($request, $shippingCost);

            return redirect('/online-payment');
        }
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
        // check permission of current user
        isAuthorized('order update');

        if ($request->order_status == 0) {
            $order->order_status = 0;

            if($order->payment_method == 1) {
                $order->payment_status = 1;
            } else {
                $order->payment_status = 0;
            }

            $order->delivery_address = $request->delivery_address;
            $order->save();

            return redirect('/order')->with('success', 'Order updated successful.');

        } elseif($request->order_status == 1) {
            $order->order_status = 1;
            $order->delivery_address = $request->delivery_address;
            $order->save();

            return redirect('/order')->with('success', 'Order updated successful.');

        } elseif($request->order_status == 2) {
            $order->order_status = 2;
            $order->delivery_address = $request->delivery_address;
            $order->delivery_date = now();
            $order->delivery_timestamp = now()->timestamp;

            if($order->payment_method == 0) {
                $order->payment_status = 1;
                $order->payment_timestamp = now();
                $order->payment_date = date('Y-m-d H:i:s');
            }

            $order->save();

            return redirect('/order')->with('success', 'Order updated successful.');
        }
        elseif($request->order_status == 3) {
            $order->order_status = 3;
            $order->payment_status = 2;

            $order->save();
            return redirect('/order')->with('success', 'Order updated successful.');
        } else {
            return redirect('/order');
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
