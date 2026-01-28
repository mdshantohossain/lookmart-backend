@extends('admin.layouts.master')

@section('title', 'Reviews')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Product Reviews</h3>
                        @can('review create')
                            <a href="{{ route('reviews.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                                Add Review
                            </a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Sl.</th>
                                    <th class="align-middle">Image</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Sku</th>
                                    <th class="align-middle">Total Reviews </th>
                                    @canany(['review edit', 'review show','review destroy'])
                                    <th class="align-middle">Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <img src="{{ $product->image_thumbnail }}" width="60" height="60" class="rounded-2" alt="{{ $product->name }}" />
                                    </td>
                                    <td>
                                        {{ truncateString($product->name, 75) }}
                                    </td>
                                    <td>
                                        {{ $product->sku }}
                                    </td>
                                    <td>
                                        {{ count($product->reviews) }}
                                    </td>

                                    @canany(['review edit', 'review destroy'])
                                    <td>
                                        <div>
                                            @can('review edit')
                                                <a href="{{ route('reviews.edit', $product->id) }}" class="btn btn-sm btn-primary" >
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('review show')
                                                 <a href="{{ route('reviews.show', $product->id) }}" class="btn btn-sm btn-success" >
                                                    <i class="fa fa-book-open"></i>
                                                 </a>
                                            @endcan

                                            @can('review destroy')
                                                <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $product->id }}")'>
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <form action="{{ route('reviews.destroy', $product->id) }}" method="POST" id="deleteForm-{{ $product->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
