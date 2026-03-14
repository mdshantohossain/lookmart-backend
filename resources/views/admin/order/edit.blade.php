@extends('admin.layouts.master')

@section('title', 'Edit Order #' . $order->id)

@section('body')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Edit Order <span class="text-primary">#{{ $order->id }}</span></h4>
                <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <form action="{{ route('orders.update', $order->slug) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0"><i class="fas fa-truck me-2 text-primary"></i>Fulfillment & Payment Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center border-bottom pb-3 mb-2">
                                <div class="col-md-3 border-end">
                                    <small class="text-muted text-uppercase d-block mb-1 small fw-bold">Payment Method</small>
                                    <span class="fw-bold text-dark">{{ $order->payment_type ? 'Online' : 'Cash on Delivery' }}</span>
                                    <div class="small text-muted badge badge-soft-info bg-small" style="font-size: 0.75rem;">{{ $order->payment->bank_type ?? '' }}</div>
                                </div>
                                @if($order->payment?->charged_amount)
                                    <div class="col-md-3 border-end">
                                        <small class="text-muted text-uppercase d-block mb-1 small fw-bold">Amount</small>
                                        <code class="text-info fw-large">৳{{ $order->payment->charged_amount }}</code>
                                    </div>
                                @endif
                                <div class="col-md-3 border-end">
                                    <small class="text-muted text-uppercase d-block mb-1 small fw-bold">Transaction ID</small>
                                    <code class="text-primary fw-medium">{{ $order->payment->transaction_id ?? 'N/A' }}</code>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted text-uppercase d-block mb-1 small fw-bold">Payment Status</small>
                                    <div class="mt-1">
                                        @if($order->payment)
                                            {!! getStatus($order->payment->status, 'payment') !!}
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-uppercase">Update Order Status</label>
                                    <select name="order_status" class="form-select @error('order_status') is-invalid @enderror">
                                        <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Processing</option>
                                        <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Delivered</option>
                                        <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold small text-uppercase">Delivery Address</label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror"
                                          name="delivery_address" rows="3"
                                          placeholder="Edit address if necessary">{{ $order->delivery_address }}</textarea>
                                @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold small text-uppercase">Phone</label>
                                <input type="text" class="form-control @error('delivery_address') is-invalid @enderror" name="phone" placeholder="Enter receiver phone" value="{{ $order->phone }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end border-top pt-3">
                                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Update Order Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4 ">
                        <div class="card-header border-0 pb-0">
                            <h6 class="card-title mb-0 small text-uppercase opacity-75"><i class="fas fa-user-circle me-2"></i>Customer Profile</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="mb-0 h5">{{ $order->user->name }}</p>
                                <small class="opacity-75">{{ $order->user->email }}</small>
                            </div>
                            <div >
                                <small class="d-block opacity-75 small text-uppercase fw-bold" style="font-size: 0.7rem;">Phone Number</small>
                                <p class="mb-0 fw-bold text-info">{{ $order->user->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start" role="alert">
                        <i class="fas fa-exclamation-triangle mt-1 me-2 text-dark"></i>
                        <div class="small">
                            <strong>Note:</strong> Check the <b>Payment Status</b> above before proceeding with delivery. Customers are notified on status updates.
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-shopping-basket me-2 text-primary"></i>Ordered Items</h5>
                            <span class="badge bg-light text-dark border">{{ count($order->orderDetails) }} Items</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 40%;">Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-4">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->orderDetails as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($item->product->image_thumbnail) }}" alt=""
                                                         class="rounded border"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 small fw-bold">{{ $item->product->name }}</h6>
                                                        @if($item->variant)
                                                            <span class="badge bg-light text-muted border-0 p-0" style="font-size: 0.8rem;">
                                                                Option: {{ $item->variant->variant_key }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">৳ {{ number_format($item->price, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-light text-dark border px-3">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-dark">৳ {{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="border-top-0">
                                    @if($order->delivery_charge != '0')
                                        <tr>
                                            <td colspan="2" class="border-0"></td>
                                            <td class="text-end py-2 text-muted">Subtotal</td>
                                            <td class="text-end pe-4 py-2 fw-medium text-dark">৳ {{ number_format($order->order_total - $order->delivery_charge, 2) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td colspan="2" class="border-0"></td>
                                        <td class="text-end py-2 text-muted">Delivery Charge</td>
                                        <td class="text-end pe-4 py-2 fw-medium text-dark">৳ {{ $order->delivery_charge == '0' ? 'Free delivery' : number_format($order->delivery_charge, 2) }}</td>
                                    </tr>
                                    <tr class="fs-5">
                                        <td colspan="2" class="border-0"></td>
                                        <td class="text-end py-3 fw-bold">Grand Total</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-primary">৳ {{ number_format($order->order_total, 2) }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
