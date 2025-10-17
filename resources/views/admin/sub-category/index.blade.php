@extends('admin.layouts.master')

@section('title', 'Categories')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Sub Categories</h3>
                        <a href="{{ route('sub-categories.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add Sub Category
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable"  class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Category Name</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Total Product</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($subCategories as $subCategory)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subCategory->category->name }}</td>
                                        <td>{{ $subCategory->name }}</td>
                                        <td>{{ count($subCategory->products) }}</td>
                                        <td>
                                            <span class="badge badge-pill {{ $subCategory->status === 1 ? 'badge-soft-success' : 'badge-soft-secondary' }}  font-size-11">{{ $subCategory->status === 1 ? 'Published': 'Unpublished' }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="{{ route('sub-categories.edit', $subCategory->slug) }}" class="btn btn-sm btn-primary" >
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $subCategory->slug }}")'>
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <form action="{{ route('sub-categories.destroy', $subCategory->slug) }}" method="POST" id="deleteForm-{{ $subCategory->slug }}">
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
