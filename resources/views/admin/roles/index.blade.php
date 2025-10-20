@extends('admin.layouts.master')

@section('title', 'Roles Manage')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Roles</h3>
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add role
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Sl.</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Permissions</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($roles as $role)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $role->name }}
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#permissionsModal{{ $role->id }}">
                                            View ({{ $role->permissions->count() }})
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="permissionsModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog"> <!-- removed modal-dialog-scrollable -->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Permissions for {{ $role->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body d-flex flex-wrap gap-1">
                                                        @foreach($role->permissions as $permission)
                                                            <span class="badge bg-primary">{{ $permission->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div>
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary" >
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $role->id }}")'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" id="deleteForm-{{ $role->id }}">
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
