@extends('admin.layouts.master')

@section('title', 'Products')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Products</h3>
                        @can('product create')
                            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                                Add Product
                            </a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Category Name</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Price</th>
                                <th class="align-middle">Image</th>
                                <th class="align-middle">Status</th>
                                @canany(['product destroy', 'product edit', 'product show'])
                                    <th class="align-middle">Action</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ truncateString($product->name, 25) }}</td>
                                    <td>${{ $product->selling_price }}</td>
                                    <td>
                                        <img src="{{ $product->main_image }}" height="80" width="100" class="rounded-2" alt="{{ $product->name }}" />
                                    </td>
                                    <td>
                                        {!! getStatus($product->status, 'catalog') !!}
                                    </td>
                                    @canany(['product destroy', 'product edit', 'product show'])
                                        <td>
                                            <div>
                                                @can('product edit')
                                                    <a href="{{ route('products.edit', $product->slug) }}" class="btn btn-sm btn-primary" >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('product show')
                                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-success" >
                                                        <i class="fa fa-book-open"></i>
                                                    </a>
                                                @endcan
                                                @can('product destroy')
                                                    <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $product->slug }}")'>
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product->slug) }}" method="POST" id="deleteForm-{{ $product->slug }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <p class="text-center fs-5 mt-4">Doesn't have any product </p>
                                    </td>
                                </tr>
                            @endforelse

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
