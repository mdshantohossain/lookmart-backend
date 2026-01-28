@extends('admin.layouts.master')

@section('title', 'Product policy create')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Category create form</h3>
                        <a href="{{ route('product-policies.index') }}" class="btn btn-sm btn-secondary waves-effect waves-light">
                            Back
                        </a>
                    </div>
                    <form method="POST" action="{{ route('product-policies.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Policy<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('policy') }}" name="policy" placeholder="Enter product policy" />
                            @error('policy')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Image</label>
                            <input type="file" class="form-control" accept="image/*" value="{{ old('image') }}" name="image"  />
                            @error('image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="image-preview"></div>
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
        $('input[name="image"]').on('change', function (e) {
            const file = e.target.files[0];
            if(!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {
                const img = `
            <div class="preview-item">
                <img
                    src="${event.target.result}"
                    alt="${file.name}"
                    class="img-thumbnail m-2"
                    width="120"
                    height="120"
                   loading="lazy"
                />
            </div>
        `;
                const container = $('.image-preview');

                container.empty();
                container.append(img);
            }
            reader.readAsDataURL(file);
        });
    </script>
@endpush
