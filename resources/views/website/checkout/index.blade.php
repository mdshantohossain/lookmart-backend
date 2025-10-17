@extends('website.layouts.master')

@section('title', 'Checkout')

@section('body')
    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="heading_s1">
                            <h4>Billing Details</h4>
                        </div>
                        <form method="POST" action="{{ route('order.store') }}">
                            @csrf

                            @if(!auth()->check())
                                <small class="text-muted mb-2 d-block">Account Details</small>
                                <div class="card card-body order_review mb-3 border-0">
                                    <div class="form-group mb-3">
                                        <input type="text"  class="form-control" value="{{ old('name') }}" name="name" placeholder="Full name *">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <input class="form-control" type="text" value="{{ old('email') }}" name="email" placeholder="Email address *">
                                         @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <input class="form-control"  type="text" value="{{ old('phone') }}" name="phone" placeholder="Phone *">
                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <input class="form-control"  type="password" value="{{ old('password') }}" name="password" placeholder="password *">
                                        @error('password')
                                          <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if(auth()->check() && !auth()->user()->phone)
                                <small class="text-muted mb-2 d-block">Account Details</small>
                                <div class="card card-body order_review mb-3 border-0">
                                    <div class="form-group mb-3">
                                        <input class="form-control"  type="text" value="{{ old('phone') }}" name="phone" placeholder="Phone *">
                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            @endif

                            <small class="text-muted mb-2 d-block">Order Information</small>

                            <div class="card card-body order_review mb-3 border-0">
                                <div class="form-group mb-3">
                                    <textarea name="delivery_address" rows="5" class="form-control" placeholder="Delivery Address *">{{ old('delivery_address') }}</textarea>
                                    @error('delivery_address')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="payment_method">
                                            <div class="heading_s1">
                                                <h4>Payment Method</h4>
                                            </div>
                                            <div class="payment_option">
                                                <div class="custome-radio">
                                                    <input class="form-check-input" type="radio" checked  name="payment_method" id="exampleRadios4" value="0">
                                                    <label class="form-check-label" for="exampleRadios4">Cash On Delivery</label><br>
                                                </div>
                                                <div class="custome-radio">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="exampleRadios5" value="1">
                                                    <label class="form-check-label" for="exampleRadios5">Online Payment</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(!$deliveryCharges->isEmpty())
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="payment_method" id="shippingOptions">
                                                <div class="heading_s1">
                                                    <h4>Delivery Method</h4>
                                                </div>
                                                @foreach($deliveryCharges as $charge)
                                                    @php
                                                        $idx = $loop->index; // gives 0,1,2...
                                                        $radioId = "delivery{$idx}";
                                                        $collapseId = "collapse{$idx}";
                                                    @endphp

                                                    <div class="d-flex flex-row">
                                                        <div class="custome-radio me-5">
                                                            <input
                                                                class="form-check-input"
                                                                type="radio"
                                                                name="delivery_within"
                                                                id="{{ $radioId }}"
                                                                value="{{ $charge->city_name }}"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#{{ $collapseId }}"
                                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                                {{ $loop->first ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label" for="{{ $radioId }}">
                                                                {{ $charge->city_name }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="my-2">
                                                        <div
                                                            id="{{ $collapseId }}"
                                                            class="collapse {{ $loop->first ? 'show' : '' }}"
                                                            data-bs-parent="#shippingOptions"
                                                        >
                                                            <small class="text-muted">
                                                                {{ $charge->is_free == 0 ? " * Delivery charge Tk.". $charge->charge . ' is required.' : '* Delivery charge is free.' }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {!! $charge->description  !!}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>


{{--                            cupon section--}}
{{--                            <div class="coupon field_form input-group mb-3">--}}
{{--                                <input type="text" value="" class="form-control" placeholder="Enter Coupon Code..">--}}
{{--                                <div class="input-group-append">--}}
{{--                                    <button class="btn btn-fill-out btn-sm" type="submit">Apply Coupon</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <button type="submit" class="btn btn-fill-out btn-block">Place Order</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="order_review">
                            @if(Cart::content()->isEmpty())
                                <h3 class="text-muted text-center">Your cart is empty</h3>
                            @else
                                <div class="heading_s1">
                                    <h4>Your Orders</h4>
                                </div>
                                <div class="table-responsive order_table">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach(Cart::content() as $cartProduct)
                                            <tr>
                                                <td>{{ substr($cartProduct->name, 0, 22) }} <span class="product-qty">x {{ $cartProduct->qty }}</span></td>
                                                <td>Tk.{{ $cartProduct->price * $cartProduct->qty }}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        @php
                                            $subtotal = (float) str_replace(',', '', Cart::subTotal());
                                            $total = $subtotal + getDeliveryCharge();
                                        @endphp

                                        <tfoot>
                                            <tr>
                                                <th>SubTotal</th>
                                                <td class="product-subtotal">Tk.{{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <th>Delivery charge</th>
                                                <td id="deliveryCharge">{{ getDeliveryCharge() != 0 ? "Tk.". getDeliveryCharge() : 'Free Delivery' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td class="product-subtotal" id="totalPrice">Tk.{{ $total }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const charges = @json($deliveryCharges);
            const rawSubTotal = @json(Cart::subtotal());

            const subTotal = parseFloat(rawSubTotal.replace(/,/g, ''));

            document.querySelectorAll('input[name="delivery_within"]').forEach( radio => {
                radio.addEventListener('change', (event) => {
                    value = event.target.value;
                    charges.forEach((charge) => {
                        if(value == charge.id) {
                            const deliveryCharge = parseFloat(charge.charge);
                            const total = subTotal + deliveryCharge;
                            document.getElementById('deliveryCharge').innerHTML = 'TK.' + deliveryCharge;

                            document.getElementById('totalPrice').innerHTML = 'TK.' + total;
                        }
                    })
                })
            });
        });
    </script>
@endpush
