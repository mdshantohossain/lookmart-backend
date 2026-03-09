@extends('admin.layouts.master')

@section('title', 'Order Detail #' . $order->id)

@section('body')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="mb-0">Order <span class="text-primary">#{{ $order->id }}</span></h4>
                <span class="text-muted">Placed on {{ date('d M Y, h:i A', $order->order_timestamp) }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('orders.index') }}" class="btn btn-light border shadow-sm btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button onclick="window.print()" class="btn btn-outline-dark shadow-sm btn-sm">
                    <i class="fas fa-print me-1"></i> Print Invoice
                </button>
                <a href="{{ route('orders.edit', $order->slug) }}" class="btn btn-primary shadow-sm btn-sm px-3">
                    <i class="fas fa-edit me-1"></i> Edit Order
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Transaction Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center border-bottom pb-3 mb-3">
                            <div class="col-md-4 border-end">
                                <small class="text-muted text-uppercase d-block mb-1">Payment Method</small>
                                <span class="fw-bold">{{ $order->payment_method == 1 ? 'Online' : 'Cash on Delivery' }}</span>
                                <div class="small text-muted">{{ $order->bank_type ?? '' }}</div>
                            </div>
                            <div class="col-md-4 border-end">
                                <small class="text-muted text-uppercase d-block mb-1">Transaction ID</small>
                                <span class="badge bg-light text-dark border fw-medium">{{ $order->transaction_id ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted text-uppercase d-block mb-1">Payment Status</small>
                                @php
                                    $pStatuses = ['Pending', 'Success', 'Canceled', 'Failed'];
                                    $pColors = ['warning', 'success', 'secondary', 'danger'];
                                @endphp
                                <span class="badge rounded-pill bg-{{ $pColors[$order->payment_status] ?? 'secondary' }} px-3">
                                {{ $pStatuses[$order->payment_status] ?? 'Unknown' }}
                            </span>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i>Delivery Address</h6>
                                <p class="text-muted mb-0" style="line-height: 1.6;">
                                    {{ $order->delivery_address }}
                                </p>
                            </div>
                            <div class="col-md-6 border-start ps-4">
                                <h6 class="fw-bold mb-3"><i class="fas fa-calendar-alt text-primary me-2"></i>Fulfillment</h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block small">Expected Delivery</small>
                                    <span>{{ $order->delivery_date?->format('d M Y, h:i A') ?? 'Not Scheduled' }}</span>
                                </div>
                                <div>
                                    <small class="text-muted d-block small">Order Status</small>
                                    @php
                                        $oStatuses = ['Pending', 'Processing', 'Delivered', 'Canceled'];
                                        $oColors = ['warning', 'info', 'success', 'danger'];
                                    @endphp
                                    <span class="badge bg-{{ $oColors[$order->order_status] ?? 'dark' }}">
                                    {{ $oStatuses[$order->order_status] }}
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 small">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-user text-primary h4 mb-0"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $order->user->name }}</h6>
                                <small class="text-muted">Registered Customer</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-envelope text-muted me-2"></i> {{ $order->user->email }}</li>
                            <li><i class="fas fa-phone text-muted me-2"></i> {{ $order->user->phone }}</li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Order Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal</span>
                            <span>৳ {{ number_format($order->order_total - $order->delivery_charge, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Shipping</span>
                            <span class="text-{{ $order->delivery_charge == 0 ? 'success' : 'dark' }}">
                            {{ $order->delivery_charge != 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                        </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Amount</span>
                            <h4 class="text-primary mb-0 fw-bold">৳ {{ number_format($order->order_total, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Ordered Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product Name</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderDetails as $orderDetail)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="fw-medium text-dark">{{ $orderDetail->product->name }}</span>
                                        </td>
                                        <td class="text-center">৳ {{ number_format($orderDetail->product->price, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-3 rounded-pill">{{ $orderDetail->quantity }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">৳ {{ number_format($orderDetail->product->price * $orderDetail->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn, .sidebar, .navbar { display: none !important; }
            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
            .container-fluid { width: 100% !important; padding: 0 !important; }
        }
    </style>
@endsection
