@extends('admin.layouts.master')

@section('title', 'Order Detail')

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="">Order Detail</h4>
                        <a href="{{ route('order.index') }}" class="btn btn-primary waves-effect waves-light">
                            Back
                        </a>
                    </div>

                    {{-- Order Information --}}
                    <h5 class="text-primary">Order Info</h5>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Order ID</th>
                            <td>#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>{{ number_format($order->order_total, 2) }} {{ $order->currency ?? 'BDT' }}</td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{ date('d M Y, h:i A', $order->order_timestamp) }}</td>
                        </tr>
                        <tr>
                            <th>Transaction ID</th>
                            <td>{{ $order->transaction_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ $order->payment_method == 1 ? 'Online' : 'Cash on Delivery' }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td>
                                @php
                                    $paymentStatuses = ['Pending', 'Success', 'Canceled', 'Failed'];
                                @endphp
                                <span class="badge badge-pill badge-soft-{{
                                  $order->payment_status == 0 ? 'warning' :
                                 ($order->payment_status == 1 ? 'success' :
                               ($order->payment_status == 2 ? 'secondary' :
                               ($order->payment_status == 3 ? 'danger' : 'secondary')))
                                }}">
                                    {{ $paymentStatuses[$order->payment_status] ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Order Status</th>
                            <td>
                                @php
                                    $statuses = ['Pending', 'Processing', 'Delivered', 'Canceled'];
                                @endphp
                                <span class="badge badge-pill badge-soft-{{
                                     $order->order_status == 0 ? 'warning' :
                                     ($order->order_status == 1 ? 'secondary' :
                                     ($order->order_status == 2 ? 'success' :
                                     ($order->order_status == 3 ? 'danger' : '')))
                                }}">{{ $statuses[$order->order_status] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Card Type</th>
                            <td>{{ $order->card_type ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Address</th>
                            <td>{{ $order->delivery_address }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Within</th>
                            <td>{{ $order->delivery_within}}</td>
                        </tr>
                        <tr>
                            <th>Delivery Charge</th>
                            <td>{{ $order->delivery_charge != 0 ? 'TK.'. $order->delivery_charge : 'Free Delivery' }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Date</th>
                            <td>{{ $order->delivery_date->format('d M Y, h:i A') ?? 'N/A' }}</td>
                        </tr>
                        </tbody>
                    </table>

                    {{-- User Information --}}
                    <h5 class="text-primary mt-4">Customer Info</h5>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $order->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $order->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $order->user->phone }}</td>
                        </tr>
                        </tbody>
                    </table>

                    {{-- Products --}}
                    <h5 class="text-primary mt-4">Ordered Products</h5>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Sl</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->orderDetails as  $orderDetail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $orderDetail->product->name }}</td>
                                <td>TK.{{ $orderDetail->product->selling_price }}</td>
                                <td>{{$orderDetail->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
