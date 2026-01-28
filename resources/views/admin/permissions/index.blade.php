@extends('admin.layouts.master')

@section('title', 'All Permissions')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Permissions</h3>
                        <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add Permissions
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($permissions as $permission)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $permission->name }}
                                    </td>

                                    <td>
                                        <div>
                                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary" >
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $permission->id }}")'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" id="deleteForm-{{ $permission->id }}">
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
