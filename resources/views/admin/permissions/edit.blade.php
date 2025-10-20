@extends('admin.layouts.master')

@section('title', 'Permission edit')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Permission edit form</h3>
                        <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-secondary waves-effect waves-light btn-sm">
                            Back
                        </a>
                    </div>

                    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" value="0" name="remove_image" class="remove-image" />

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $permission->name }}" name="name" placeholder="Enter permission name" />
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div>
                            <button type="submit" class="btn btn-primary w-md">Update</button>
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
