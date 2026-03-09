@extends('admin.layouts.master')

@section('title', 'Order manage')

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">All Orders Information</h3>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                        <tr>
                            <th>Sl</th>
                            <th>Customer</th>
                            <th>Order Total</th>
                            <th>Order Date</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ substr($order->user->name, 0, 18) }} <br>
                                    {{ $order->user->email }} <br>
                                    {{ $order->user->phone }}
                                </td>
                                <td>৳{{ $order->order_total }}</td>
                                <td>{{ $order->created_at->format('M m, Y') }}</td>
                                <td>
                                    {{ $order->payment_method == 0 ? 'Cash On Delivery' : 'Online' }}
                                </td>
                                <td>
                                    @if($order->payment)
                                    {!! getStatus($order->payment?->status, 'payment') !!}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    {!! getStatus($order->order_status, 'order') !!}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('orders.edit', $order->slug) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="{{ route('orders.show', $order->slug) }}" class="btn btn-outline-info btn-sm"  data-bs-toggle="tooltip" title="Oder detail">
                                            <i class="fa fa-book-open"></i>
                                        </a>

{{--                                        <a href="{{ route('download.invoice', $order->slug) }}" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Invoice download">--}}
{{--                                            <i class="fa fa-download"></i>--}}
{{--                                        </a>--}}

{{--                                        <button href="" class="btn btn-outline-danger btn-sm" onclick="deleteOrder('orderDeleteForm{{$order->id}}')">--}}
{{--                                            <i class="fa fa-trash"></i>--}}
{{--                                        </button>--}}
{{--                                        <form action="{{ route('order.destroy', $order->slug) }}" method="POST" id="orderDeleteForm{{$order->id}}">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                        </form>--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection


@push('scripts')
    <script>
        function deleteOrder(orderId) {
            if(confirm('Are you sure you want to delete this order?')) {
                document.getElementById(orderId).submit();
            }
        }
    </script>
@endpush
