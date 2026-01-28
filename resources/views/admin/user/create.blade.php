@extends('admin.layouts.master')

@section('title', 'User create')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>User create form</h3>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary waves-effect waves-light btn-sm">
                            Back
                        </a>
                    </div>
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Enter name" />
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="Enter email" />
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Phone</label>
                            <input type="text" class="form-control" value="{{ old('phone') }}" name="phone" placeholder="Enter phone" />
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control" value="{{ old('password') }}" name="password" placeholder="Enter password" />
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">User role<span class="text-danger">*</span></label>
                            <select name="role" class="form-select">
                                <option value="">select role</option>
                                @forelse($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected': '' }}>{{ $role->name }}</option>
                                @empty
                                    <option value="">No role is available</option>
                                @endforelse
                            </select>
                            @error('role')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100">Create</button>
                        </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection





