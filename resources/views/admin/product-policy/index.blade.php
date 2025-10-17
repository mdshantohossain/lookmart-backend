@extends('admin.layouts.master');

@section('title', 'Product policies')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">Product all policies</h3>
                        <a href="{{ route('product-policies.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add Policy
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Policy</th>
                                <th class="align-middle">Image</th>
                                <th class="align-middle">Action</th>
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
                                    <td>
                                        <div>
                                            <a href="{{ route('product-policies.edit', $productPolicy->slug) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $productPolicy->slug }}")'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form action="{{ route('product-policies.destroy', $productPolicy->slug) }}" method="POST" id="deleteForm-{{ $productPolicy->slug }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
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


