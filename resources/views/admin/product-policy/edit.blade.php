@extends('admin.layouts.master')

@section('title', $productPolicy->policy)

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Category edit form</h3>
                        <a href="{{ route('product-policies.index') }}" class="btn btn-sm btn-secondary waves-effect waves-light">
                            Back
                        </a>
                    </div>
                    <form method="POST" action="{{ route('product-policies.update', $productPolicy->slug) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="remove_image" class="remove-image" value="0" />
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Policy<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $productPolicy->policy }}" name="policy" placeholder="Enter product policy" />
                            @error('policy')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Image</label>
                            <input type="file" class="form-control" value="{{ old('image') }}" name="image"  />
                            @error('image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="image-preview mt-2">
                                @if($productPolicy->image)
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ $productPolicy->image }}"
                                             class="img-thumbnail rounded-2"
                                             height="120"
                                             width="120"
                                             loading="lazy"
                                             alt="{{ $productPolicy->name }}"
                                        />
                                        <i data-img-url="${e.target.result}" class="position-absolute top-0 end-0 remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger cursor-pointer"
                                           aria-hidden="true"
                                           style="cursor: pointer"
                                        ></i>
                                    </div>
                                @endif
                            </div>
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
        $('input[name="image"]').on('change', function (e) {
            const file = e.target.files[0];
            if(!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {
                const img = `<div class="position-relative d-inline-block">
                    <img src="${event.target.result}"
                         class="img-thumbnail rounded-2"
                         height="120"
                         width="120"
                         loading="lazy"
                         alt="${file.name}"
                    />
<i data-img-url="${event.target.result}" class="position-absolute top-0 end-0 remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger cursor-pointer"
aria-hidden="true"
style="cursor: pointer"
></i>
                </div>`;
                const container = $('.image-preview');

                container.empty();
                container.html(img);
            }
            reader.readAsDataURL(file);
        });

        $(document).on('click', '.remove-image', function () {
            // Remove preview + hidden input
            $(this).closest('div').remove();
            $('.image').val('').trigger('change');
            $('.remove-image').val('1');
        });
    </script>
@endpush
