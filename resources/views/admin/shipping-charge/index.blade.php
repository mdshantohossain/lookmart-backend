@extends('admin.layouts.master')

@section('title', 'Shipping manage')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">All Shipping Management</h4>
                    <a href="{{ route('shipping.create') }}" class="btn btn-primary waves-effect waves-light">
                        Add Shipping
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Charge</th>
                                <th class="align-middle">Free</th>
                                <th class="align-middle">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(!$shippingCharges->isEmpty())
                                @foreach($shippingCharges as $shippingCharge)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $shippingCharge->city_name }}
                                        </td>

                                        <td>
                                            {{ $shippingCharge->charge }}
                                        </td>


                                        <td>
                                            <span class="badge badge-pill {{ $shippingCharge->is_free == 1 ? 'badge-soft-success' : 'badge-soft-secondary' }} font-size-11">{{ $shippingCharge->is_free == 1 ? 'Charge Free': 'Charge Active' }}</span>
                                        </td>

                                        <td>
                                            <div>
                                                <a href="{{ route('shipping.edit', $shippingCharge->id) }}" class="btn btn-sm btn-primary" >
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $shippingCharge->id }}")'>
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <form action="{{ route('shipping.destroy', $shippingCharge->id) }}" method="POST" id="deleteForm-{{ $shippingCharge->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">
                                        <p class="text-center fs-5 mt-4">Nowhere shipping cost created yet</p>
                                    </td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(event, formId) {
            if (confirm('Are you sure to delete this one?')) {
                event.preventDefault();
                document.getElementById(formId).submit();
            }
        }
    </script>
@endpush
