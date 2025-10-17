@extends('admin.layouts.master')

@section('title', 'Categories')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Categories</h3>
                        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add Category
                        </a>
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
                                <th class="align-middle">Action</th>
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
                                        {{ count($category->subCategories) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-pill {{ $category->status === 1 ? 'badge-soft-success' : 'badge-soft-secondary' }}  font-size-11">{{ $category->status === 1 ? 'Published': 'Unpublished' }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('categories.edit', $category->slug) }}" class="btn btn-sm btn-primary" >
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $category->slug }}")'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category->slug) }}" method="POST" id="deleteForm-{{ $category->slug }}">
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
