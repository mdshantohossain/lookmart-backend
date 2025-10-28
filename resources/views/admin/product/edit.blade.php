@extends('admin.layouts.master')

@section('title', 'Edit Product')

@section('body')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Edit Product</h4>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" id="categoryId">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Sub Category -->
                            <div class="col-md-6">
                                <label class="form-label">Sub Category</label>
                                <select name="sub_category_id" id="subCategoryId" class="form-select">
                                    <option value="">Select Sub Category</option>
                                    @foreach ($subCategories as $sub)
                                        <option value="{{ $sub->id }}" {{ $product?->sub_category_id == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-12">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" />
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Prices -->
                            <div class="col-md-3">
                                <label class="form-label">Original Price</label>
                                <input type="number" step="0.01" id="regular_price" name="regular_price" class="form-control"
                                       value="{{ old('regular_price', $product->regular_price) }}" placeholder="0.00" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Discount (%)</label>
                                <input type="text" id="discount" name="discount" class="form-control"
                                       value="{{ old('discount', $product->discount) }}" placeholder="0%" />
                                <small id="discountError" class="text-danger"></small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Selling Price</label>
                                <input type="number" step="0.01" id="selling_price" name="selling_price" class="form-control"
                                       value="{{ old('selling_price', $product->selling_price) }}" placeholder="0.00" />
                            </div>

                            <!-- SKU -->
                            <div class="col-md-3">
                                <label class="form-label">SKU<span class="text-danger">*</span></label>
                                <input type="text" name="sku" class="form-control"
                                       value="{{ old('sku', $product->sku) }}" />
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" min="1"
                                       value="{{ old('quantity', $product->quantity) }}" placeholder="Enter product quantity" />
                            </div>

                            <!-- Product sizes -->
                            <div class="col-md-6">
                                <label for="other_images" class="form-label">Product Sizes</label>
                                <input type="text" class="form-control sizes" id="sizes" name="sizes" placeholder="Enter product sizes" />
                                @error('sizes')
                                <span id="sizes" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Colors-->
                            <div class="">
                                <label for="other_images" class="form-label">Colors</label>
                                <input type="file" class="form-control" id="colors" name="color_images[]" multiple />
                                @error('colors')
                                <span id="colors" class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="color-previews-container d-flex flex-wrap"></div>
                            </div>

                            <!-- Main Image -->
                            <div class="col-md-6">
                                <label class="form-label">Main Image<span class="text-danger">*</span></label>
                                <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*" />
                                <div id="main_image_preview_wrapper" class="mt-2">
                                    @if ($product->main_image)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset($product->main_image) }}" class="rounded-2"
                                                 width="150" height="120" alt="">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image"
                                                    data-img-url="{{ $product->main_image }}" style="border-radius: 50%; padding: 2px 6px;">×</button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="video" class="form-label">Product Video</label>
                                <input type="file" class="form-control" id="video" name="video" />
                                @error('video')
                                <span id="video" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Other Images -->
                            <div class="col-md-12">
                                <label class="form-label">Other Images</label>
                                <input type="file" name="other_images[]" id="other_images" multiple class="form-control"
                                       accept="image/*">
                                <div id="cj_other_images_wrapper" class="mt-2 d-flex flex-wrap">
                                    @foreach ($product->otherImages ?? [] as $img)
                                        <div class="position-relative d-inline-block m-1">
                                            <img src="{{ asset($img->image) }}" class="img-thumbnail"
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image"
                                                    data-img-url="{{ $img->image }}" style="border-radius: 50%; padding: 2px 6px;">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Variants Title -->
                            <div class="col-md-12">
                                <label for="variants_title" class="form-label">Variants Title</label>
                                <input type="text" class="form-control" id="variants_title" name="variants_title" value="{{ $product->variants_title }}" placeholder="Color, Size, etc" />
                                @error('variants_title')
                                <span id="variants_title_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Variants -->
                            <div class="col-md-12 mt-4" id="variantSection">
                                <h5 class="fw-bold mb-3">Product Variants</h5>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Variant Key</th>
                                            <th>Buy Price</th>
                                            <th>Suggested Price</th>
                                            <th>Selling Price</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="variantTableBody">
                                        @foreach ($product->variants as $index => $variant)
                                            <tr class="variant-item">
                                                <td>
                                                    @if ($variant->image)
                                                        <img src="{{ asset($variant->image) }}" width="60" height="60" class="img-thumbnail" style="object-fit:cover;">
                                                    @endif
                                                    <input type="file" name="variant_image[]" accept="image/*" class="form-control form-control-sm variant-image-input mt-1" />
                                                    <input type="hidden" name="existing_variant_image[]" value="{{ $variant->image }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="variant_key[]" class="form-control form-control-sm" value="{{ $variant->variant_key }}" />
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="buy_price[]" class="form-control form-control-sm" value="{{ $variant->buy_price }}" />
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="suggested_price[]" class="form-control form-control-sm" placeholder="0.00" value="{{ $variant->suggested_price }}" readonly />
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="selling_price[]" class="form-control form-control-sm" placeholder="0.00" value="{{ $variant->selling_price }}" />
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addVariantBtn">+ Add Custom Variant</button>
                            </div>

                            <!-- Short Description -->
                            <div class="col-md-12">
                                <label class="form-label">Short Description<span class="text-danger">*</span></label>
                                <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>

                            <!-- Long Description -->
                            <div class="col-md-12">
                                <label class="form-label">Long Description</label>
                                <textarea name="long_description" id="long_description" class="form-control" rows="5">{{ $product->long_description }}</textarea>
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control tags" id="tags" name="tags" value="{{ old('tags') }}" placeholder="e.g. electronics, mobile, laptop">
                                @error('tags')
                                <span id="tags_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="tags" class="form-label">Meta Title</label>
                                <input type="text" class="form-control meta-title" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" placeholder="Enter product meta title">
                                @error('meta_title')
                                <span id="meta_title_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                                <div id="meta_title_container"></div>
                            </div>

                            <div class="col-md-12">
                                <label for="tags" class="form-label">Meta Description</label>
                                <textarea type="text" class="form-control" id="meta_description" name="meta_description"  placeholder="Enter product meta description">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                <span id="tags_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Owner -->
                            <div class="mb-2">
                                <label class="form-label d-block">Product Owner</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input
                                                type="radio"
                                                class="form-check-input"
                                                id="own"
                                                name="product_owner"
                                                value="0"
                                                {{ $product->product_owner === '0' ? 'checked' : '' }}
                                            >
                                            <label for="own" class="form-check-label">Own</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input
                                                type="radio"
                                                class="form-check-input"
                                                id="cj_dropshipping"
                                                name="product_owner"
                                                value="1"
                                                {{ $product->product_owner === '1' ? 'checked' : '' }}
                                            >
                                            <label for="cj_dropshipping" class="form-check-label">CJ Dropshipping</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input
                                                type="radio"
                                                class="form-check-input"
                                                id="aliexpress"
                                                name="product_owner"
                                                value="2"
                                                {{ $product->product_owner === '2' ? 'checked' : '' }}
                                            >
                                            <label for="aliexpress" class="form-check-label">AliExpress</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Status -->
                            <div class="mb-2">
                                <label class="form-label d-block">Product Status</label>

                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="status_published"
                                        name="status"
                                        value="1"
                                        {{ $product->status === 1 ? 'checked' : '' }}
                                    >
                                    <label for="status_published" class="form-check-label">Publish</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="status_unpublished"
                                        name="status"
                                        value="0"
                                        {{ $product->status === '0' ? 'checked' : '' }}
                                    >
                                    <label for="status_unpublished" class="form-check-label">Unpublish</label>
                                </div>
                            </div>

                            <!-- Featured -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ $product->is_featured === 1  ? 'checked' : '' }}>
                                    <label for="is_featured" class="form-check-label">Featured Product</label>
                                </div>
                            </div>

                            <!-- Trending -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_trending" name="is_trending" value="1" {{ $product->is_trending === 1 ? 'checked' : '' }}>
                                    <label for="is_trending" class="form-check-label">Trending Product</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-primary w-100 mt-3">Update Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>

        // --- Variant table dynamic handling ---
        $(document).on('click', '#addVariantBtn', function () {
            const rowCount = $('#variantTableBody tr').length;
            const newRow = `
                  <tr class="variant-item">
                    <td>
                      <input type="file" name="variants[${rowCount + 1}][image]" accept="image/*" class="form-control form-control-sm variant-image-input" />
                      <img src="" alt="Preview" class="img-thumbnail mt-1" style="max-width:60px; display:none;">
                    </td>
                  <td><input type="text" name="variants[${rowCount + 1}][variant_key]" class="form-control form-control-sm" placeholder="Color - Size" /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][buy_price]" class="form-control form-control-sm" placeholder="0.00" /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][suggested_price]" class="form-control form-control-sm" placeholder="0.00" readonly /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][selling_price]" class="form-control form-control-sm" placeholder="0.00" /></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button></td>
                  </tr>`;
            $('#variantTableBody').append(newRow);
        });

        // --- Preview uploaded variant image ---
        $(document).on('change', '.variant-image-input', function () {
            const file = this.files[0];
            const img = $(this).closest('td').find('img');
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });

        // --- Remove variant row ---
        $(document).on('click', '.remove-variant', function () {
            $(this).closest('tr').remove();
        });


        $(document).ready(function () {
            // tag Inputs
            const sizesTagInput = createTagInput('#sizes');
            const metaTitleInput = createTagInput('#meta_title');
            const tagInput = createTagInput('#tags');

            const sizes = "{{ $product->sizes }}";
            if(sizes) {
                sizesTagInput.setValues(sizes)
            }

            const metaTitle = "{{ $product->meta_title }}";
            if(metaTitle) {
                sizesTagInput.setValues(metaTitle)
            }

            // Sub-category dynamic loading
            $('#categoryId').on('change', function () {
                let id = $(this).val();
                let subSelect = $('#subCategoryId');
                subSelect.html('<option value="">Loading...</option>');
                if (!id) return subSelect.html('<option value="">Select sub category</option>');
                $.get(`/get-sub-categories/${id}`, function (res) {
                    subSelect.html('<option value="">Select Sub Category</option>');
                    res.forEach(sc => subSelect.append(`<option value="${sc.id}">${sc.name}</option>`));
                });
            });

            // Image preview
            function previewImages(input, wrapper) {
                const files = input.files;
                $(wrapper).empty();
                for (let file of files) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $(wrapper).append(`
                    <div class="position-relative d-inline-block m-1">
                        <img src="${e.target.result}" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image" style="border-radius:50%;padding:2px 6px;">×</button>
                    </div>`);
                    };
                    reader.readAsDataURL(file);
                }
            }

            $('#main_image').on('change', function () { previewImages(this, '#main_image_preview_wrapper'); });

            $('#other_images').on('change', function () { previewImages(this, '#cj_other_images_wrapper'); });

            $(document).on('click', '.remove-image', function () {
                $(this).closest('.position-relative').remove();
            });

            // Auto-calc discount
            const discountError = $('#discountError');
            $('#discount, #regular_price').on('input', function () {
                let reg = parseFloat($('#regular_price').val());
                let disc = $('#discount').val().replace('%', '');
                if (!reg || isNaN(disc)) return;
                if (disc < 0 || disc > 100) return discountError.text('0–100% only');
                discountError.text('');
                let final = reg - (reg * (disc / 100));
                $('#selling_price').val(final.toFixed(2));
            });

            $(document).on('click', '.remove-variant', function () {
                $(this).closest('.variant-item').remove();
            });

            // Summernote
            $('#long_description').summernote({ height: 200 });
        });
    </script>
@endpush
