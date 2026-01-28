@extends('admin.layouts.master')

@section('title', 'Categories')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Categories</h3>
                        @can('category create')
                        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add Category
                        </a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">image</th>
                                <th class="align-middle">Total Sub category</th>
                                <th class="align-middle">Status</th>
                                @canany(['category edit', 'category destroy'])
                                <th class="align-middle">Action</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $category->name }}
                                    </td>
                                    <td>
                                        @if($category->image)
                                            <img src="{{ $category->image }}" height="60" width="80" class="rounded-2" alt="">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        {{ $category->sub_categories_count }}
                                    </td>
                                    <td>
                                        {!! getStatus($category->status, 'catalog') !!}
                                    </td>
                                    @canany(['category edit', 'category destroy'])
                                    <td>
                                        <div>
                                            @can('category edit')
                                                <a href="{{ route('categories.edit', $category->slug) }}" class="btn btn-sm btn-primary" >
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('category destroy')
                                                <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $category->slug }}")'>
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <form action="{{ route('categories.destroy', $category->slug) }}" method="POST" id="deleteForm-{{ $category->slug }}">
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
