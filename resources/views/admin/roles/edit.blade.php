@extends('admin.layouts.master')

@section('title', $role->name. ' edit')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Role edit form</h3>
                        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary waves-effect waves-light btn-sm">
                            Back
                        </a>
                    </div>

                    <form method="POST" action="{{ route('roles.update', $role->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $role->name }}" name="name" placeholder="Enter category name" />
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="permissions[]">Permissions</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach($permissions as $permission)
                                    <div class="bg-custom-checkbox px-2 py-1 rounded-3 d-inline-block">
                                        <div class="form-check">
                                            <input
                                                type="checkbox"
                                                class="form-check-input"
                                                id="permission_{{ $permission->id }}"
                                                name="permissions[]"
                                                value="{{ $permission->name }}"
                                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                            />
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ ucfirst($permission->name) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                            <span class="text-danger">{{ $message }}</span>
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

@push('scripts')
    <script>
        $('.image').on('change', (event) => {
            const file = event.target.files[0];

            const imageContainer = $('.category-image-preview');
            imageContainer.empty();

            const render = new FileReader();
            render.onload = (e) => {
                const image = `<div class="position-relative d-inline-block">
                    <img src="${e.target.result}"
                         class="img-thumbnail rounded-2"
                         height="120"
                         width="120"
                         loading="lazy"
                         alt="${e.target.result}"
                    />
<i data-img-url="${e.target.result}" class="position-absolute top-0 end-0 remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger cursor-pointer"
aria-hidden="true"
style="cursor: pointer"
></i>
                </div>`;

                imageContainer.html(image);
            }
            if(file) render.readAsDataURL(file);
        });

        // Remove Images
        $(document).on('click', '.remove-image', function () {
            // Remove preview + hidden input
            $(this).closest('div').remove();
            $('.image').val('').trigger('change');
            $('.remove-image').val('1');
        });
    </script>
@endpush
