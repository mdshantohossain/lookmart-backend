@extends('admin.layouts.master');

@section('title', 'Product policies')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">Product all policies</h3>
                        @can('product policy create')
                        <a href="{{ route('product-policies.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add Policy
                        </a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Policy</th>
                                <th class="align-middle">Image</th>
                                @canany(['product policy edit', 'product policy destroy'])
                                <th class="align-middle">Action</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($productPolicies as $productPolicy)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ substr($productPolicy->policy, 0, 70) }}
                                    </td>
                                    <td>
                                        @if($productPolicy->image)
                                            <img src="{{ $productPolicy->image }}" width="80" height="60" class="rounded-2" alt="{{ $productPolicy->image }}" />
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </td>
                                    @canany(['product policy edit', 'product policy destroy'])
                                    <td>
                                        <div>
                                            @can('product policy edit')
                                            <a href="{{ route('product-policies.edit', $productPolicy->slug) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan

                                            @can('product policy destroy')
                                            <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $productPolicy->slug }}")'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form action="{{ route('product-policies.destroy', $productPolicy->slug) }}" method="POST" id="deleteForm-{{ $productPolicy->slug }}">
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


