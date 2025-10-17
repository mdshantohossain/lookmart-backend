@extends('admin.layouts.master')

@section('title', 'Order edit')

@section('body')
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="">Order Edit</h4>
                        <a href="{{ route('order.index') }}" class="btn btn-primary waves-effect waves-light">
                            Back
                        </a>
                    </div>

                    <form action="{{ route('order.update', $order->slug) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="">Name</label>
                            <input type="text" class="form-control" readonly value="{{ $order->user->name }}"/>
                        </div>
                        <div class="row mb-3">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" readonly value="{{ $order->user->phone }}" />
                        </div>
                        <div class="row mb-3">
                            <label for="">Order Total</label>
                            <input type="text" class="form-control" readonly value="{{ $order->order_total. ' BDT' }}" />
                        </div>

                        <div class="row mb-3">
                            <label for="">Delivery Address</label>
                            <textarea  class="form-control" name="delivery_address" rows="5">{{ $order->delivery_address }}</textarea>
                            @error('delivery_address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <label for="">Delivery Address</label>
                            <select name="order_status" class="form-select">
                                <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Processing</option>
                                <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Delivered</option>
                                <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                           <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
