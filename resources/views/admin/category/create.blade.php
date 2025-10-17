@extends('admin.layouts.master')

@section('title', 'Category create')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Category create form</h3>
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary waves-effect waves-light btn-sm">
                            Back
                        </a>
                    </div>
                    <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Enter category name" />
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Image</label>
                            <input type="file" class="form-control image" name="image" />
                            @error('image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="mt-2 category-image-preview"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-published" checked name="status" value="1" />
                                    <label for="formrow-for-published" class="form-check-label">Publish</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-unpublished" name="status" value="0" />
                                    <label for="formrow-for-unpublished" class="form-check-label" >Unpublish</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md">Create</button>
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
        });
    </script>
@endpush



