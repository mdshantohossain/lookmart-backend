@extends('admin.layouts.master')

@section('title', 'Admin users')

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="">All Users information</h3>
                        @can('user create',)
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Add user
                        </a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sl.</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Contact</th>
                                <th class="align-middle">Email</th>
                                <th class="align-middle">Role</th>
                                <th class="align-middle">Activity</th>
                                <th class="align-middle">Status</th>
                                @canany(['user edit', 'user destroy'])
                                <th class="align-middle">Action</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }} <br> {{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#permissionsModal{{ $user->id }}">
                                            View
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="permissionsModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog"> <!-- removed modal-dialog-scrollable -->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Role for <strong>{{ $user->name }}    </strong></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h4>Roles: </h4>
                                                        <div class=" d-flex flex-wrap gap-1">
                                                            @foreach($user->roles as $role)
                                                                <span class="bg-primary text-light rounded-2 px-2">{{ $role->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{!! getStatus($user->is_active, 'activity') !!}</td>
                                    <td>{!! getStatus($user->status)  !!}</td>

                                    @canany(['user edit', 'user destroy'])
                                        <td>
                                            <div>
                                                @can('user edit')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary" >
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan

                                                @can('user destroy')
                                                <a href="#" class="btn btn-sm btn-danger" onclick='confirmDelete(event, "deleteForm-{{ $user->id }}")'>
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="deleteForm-{{ $user->id }}">
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
