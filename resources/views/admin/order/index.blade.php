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
                            <th>User</th>
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
                                <td>{{ substr($order->user->name, 0, 16) }} <br> {{ $order->user->phone }}</td>
                                <td>{{ $order->order_total }} BDT</td>
                                <td>{{ $order->created_at->format('M m, Y') }}</td>
                                <td>
                                    {{ $order->payment_method == 0 ? 'Cash On Delivery' : 'Online' }}
                                </td>
                                <td>
                                <span class="badge badge-pill badge-soft-{{
                                  $order->payment_status == 0 ? 'warning' :
                                 ($order->payment_status == 1 ? 'success' :
                               ($order->payment_status == 2 ? 'secondary' :
                               ($order->payment_status == 3 ? 'danger' : '')))
                                }}">
                                    {{ $order->payment_status == 0 ? 'Pending': ($order->payment_status == 1 ? 'Success' : ($order->payment_status == 2 ? 'Canceled' : ($order->payment_status == 3 ? 'Failed' : ''))) }}
                                </span>
                                </td>
                                <td>
                                     <span class="badge badge-pill badge-soft-{{
                                     $order->order_status == 0 ? 'warning' :
                                     ($order->order_status == 1 ? 'secondary' :
                                     ($order->order_status == 2 ? 'success' :
                                     ($order->order_status == 3 ? 'danger' : '')))
                                }}">
                                   {{ $order->order_status == 0 ? 'Pending':
                                     ($order->order_status == 1 ? 'Processing' :
                                     ($order->order_status == 2 ? 'Delivered' :
                                     ($order->order_status == 3 ? 'Canceled' : '')))
                                   }}
                                </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('order.edit', $order->slug) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="{{ route('order.show', $order->slug) }}" class="btn btn-outline-info btn-sm"  data-bs-toggle="tooltip" title="Oder detail">
                                            <i class="fa fa-book-open"></i>
                                        </a>

                                        <a href="{{ route('download.invoice', $order->slug) }}" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Invoice download">
                                            <i class="fa fa-download"></i>
                                        </a>

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
