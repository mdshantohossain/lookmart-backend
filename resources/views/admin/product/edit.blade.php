@extends('admin.layouts.master')

@section('title', 'Product Edit')

@section('body')
    <div class="row">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Edit Product</h4>
                    <div class="page-title-right">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mx-auto">
            <form method="POST" action="{{ route('products.update', $product->slug) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Basic Information</h4>
                        <p class="card-title-desc">Fill all information below</p>

                        {{-- Hidden values --}}
                        <input type="hidden" id="cj_id" value="{{ $product->cj_id }}" name="cj_id" />
                        <input type="hidden" id="buy_price" name="buy_price" />

                        <div class="row g-3">

                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="categoryId" class="form-label">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-select" id="category">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span id="category_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Sub-category -->
                            <div class="col-md-6">
                                <label for="subCategoryId" class="form-label">Sub category</label>
                                <select name="sub_category_id" id="subCategoryId" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $subCategory->id == $product->sub_category_id ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_category_id')
                                <span id="sub_category_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-12">
                                <label for="name" class="form-label">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ $product->name }}" placeholder="Enter product name">
                                @error('name')
                                <span id="name_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Pricing -->
                            <div class="col-md-4">
                                <label for="original_price" class="form-label">Original Price</label>
                                <input type="number" step="0.01" class="form-control" id="original_price"
                                       name="original_price" value="{{ $product->original_price }}" placeholder="0.00">
                                @error('original_price')
                                <span id="original_price_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="text" class="form-control" id="discount" name="discount"
                                       value="{{ $product->discount }}" placeholder="0%">
                                <span class="text-danger" id="discountError"></span>
                                @error('discount')
                                <span id="discount_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="selling_price" class="form-label">
                                    Selling Price <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" class="form-control" id="selling_price"
                                       name="selling_price" value="{{ $product->selling_price }}" placeholder="0.00">
                                @error('selling_price')
                                <span id="selling_price_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div class="col-md-6">
                                <label for="sku" class="form-label">
                                    SKU <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="sku" name="sku" readonly
                                       value="{{ $product->sku }}" placeholder="Enter product unique SKU">
                                @error('sku')
                                <span id="sku_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                       value="{{ $product->quantity }}" min="1" placeholder="Enter product quantity">
                                @error('quantity')
                                <span id="quantity_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Image thumbnail -->
                            <div class="col-md-3">
                                <label for="image_thumbnail" class="form-label">
                                    Image Thumbnail <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="image_thumbnail" name="image_thumbnail"
                                       accept="image/*" />
                                <input type="hidden" name="remove_previous_image_thumbnail" value="0" />
                                @error('image_thumbnail')
                                    <span id="image_thumbnail_error_message" class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="image_thumbnail_preview_wrapper" class="mt-2">
                                    <img src="{{ $product->image_thumbnail }}" style="max-width:120px;" alt="{{ $product->name }}" />
                                </div>
                            </div>

                            <!-- Video thumbnail -->
                            <div class="col-md-3">
                                <label for="video_thumbnail" class="form-label">
                                    Video Thumbnail
                                </label>
                                <input type="file" class="form-control" id="video_thumbnail" name="video_thumbnail"
                                       accept="video/*" />
                                <input type="hidden" id="remove_previous_video_thumbnail" name="remove_previous_video_thumbnail" value="0" />
                                @error('video_thumbnail')
                                <span id="video_thumbnail_error_message" class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="video_thumbnail_preview_wrapper" class="mt-2">
                                    @if($product->video_thumbnail)
                                        <div class="position-relative d-inline-block" style="width:160px;height:140px;margin:5px;">
                                            <video width="150" height="140" controls class="rounded-2" muted>
                                                <source src="{{ $product->video_thumbnail }}" />
                                            </video>
                                            <i data-img-url="{{ $product->video_thumbnail }}"
                                               class="position-absolute remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
                                               style="cursor:pointer; top:5px; right:-2px">
                                            </i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Other Images -->
                            <div class="col-md-6">
                                <label for="other_images" class="form-label">
                                    Gallery Images <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="other_images"
                                       name="other_images[]" multiple accept="image/*">
                                @error('other_images')
                                <span id="other_image_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="other_image_preview">
                                    @foreach($product->otherImages as $otherImage)
                                        <div class="position-relative d-inline-block" style="width:100px;height:100px;margin:5px;">
                                            <img src="{{ $otherImage->image }}" class="img-thumbnail rounded-2"
                                                 style="width:100px;height:100px;object-fit:cover;border-radius:8px;"
                                                 loading="lazy" alt="product" referrerpolicy="no-referrer" />
                                            <i data-id="{{ $otherImage->id }}"
                                               class="position-absolute top-0 end-0 remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
                                               style="cursor:pointer"></i>
                                            <input type="hidden" name="remove_other_image[{{$otherImage->id}}]" value="0" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="col-md-4">
                                <label for="variants_title" class="form-label">Variants Title</label>
                                <input type="text" class="form-control" id="variants_title"
                                       name="variants_title" value="{{ $product->variants_title }}"
                                       placeholder="Select color, Size, etc" />
                            </div>

                            <div class="col-md-4">
                                <label for="total_sold" class="form-label">Total Sold</label>
                                <input type="text" class="form-control" id="total_sold"
                                       name="total_sold" value="{{ $product->total_sold }}"
                                       placeholder="Product sold count" />
                            </div>

                            <div class="col-md-4">
                                <label for="total_day_to_delivery" class="form-label">Total day for delivery</label>
                                <input type="text" class="form-control" id="total_day_to_delivery"
                                       name="total_day_to_delivery" value="{{ $product->total_day_to_delivery }}"
                                       placeholder="Total delivery day" />
                            </div>

                            <!-- Variants Section -->
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
                                        @foreach($product->variants as $key => $variant)
                                            <tr class="variant-item">
                                                <td>
                                                    <input type="file" name="variants[{{ $key }}][image]" accept="image/*" class="form-control form-control-sm variant-image-input mb-2" />
                                                    <input type="hidden" name="variants[{{ $key }}][id]" value="{{ $variant->id }}" />

                                                    @if($variant->image)
                                                        <div class="variant-preview position-relative mt-1" style="width:60px;">
                                                            <input type="hidden" name="variants[{{ $key }}][image]" value="{{ $variant['image'] }}" />

                                                            <img src="{{ $variant->image }}" class="img-thumbnail" style="max-width:60px;" alt="">
                                                            <i class="fa fa-times-circle text-danger remove-variant-image"
                                                               data-id="{{ $variant->id }}" style="cursor:pointer; position:absolute; top:-2px; right:-2px;">
                                                            </i>
                                                        </div>
                                                    @endif

                                                <!-- hidden value for remove image -->
                                                    <input type="hidden" name="variants[{{ $key }}][remove_image]" class="remove-image-field" value="0">
                                                </td>
                                                <td><input type="text" name="variants[{{ $key }}][variant_key]" value="{{ $variant->variant_key }}" class="form-control form-control-sm" placeholder="Color - Size" /></td>
                                                <td><input type="number" step="0.01" name="variants[{{ $key }}][buy_price]" value="{{ $variant->buy_price }}" class="form-control form-control-sm" placeholder="0.00" /></td>
                                                <td><input type="number" step="0.01" name="variants[{{ $key }}][suggested_price]" disabled value="{{ $variant->suggested_price }}" class="form-control form-control-sm" placeholder="0.00" readonly /></td>
                                                <td><input type="number" step="0.01" name="variants[{{ $key }}][selling_price]" value="{{ $variant->selling_price }}" class="form-control form-control-sm" placeholder="0.00" /></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="remove_variants[{{ $variant->id }}]" value="0" />
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addVariantBtn">
                                    + Add Variant
                                </button>
                            </div>

                            <!-- Short Description -->
                            <div class="col-md-12">
                                <label for="short_description" class="form-label">
                                    Short Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="short_description" name="short_description"
                                          rows="3" placeholder="Enter product's short description">{{ $product->short_description }}</textarea>
                                @error('short_description')
                                <span id="short_description_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Long Description -->
                            <div class="col-md-12">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea class="form-control" id="long_description" name="long_description"
                                          rows="5" placeholder="Enter product long description">{!! $product->long_description !!}</textarea>
                                @error('long_description')
                                <span id="long_description_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12">
                                <label for="tags" class="form-label">Product Tags</label>
                                <input type="text" class="form-control tags" id="tags" name="tags"
                                       value="" placeholder="e.g. electronics, mobile, laptop">
                                @error('tags')
                                <span id="tags_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <!-- row end -->
                    </div>
                </div>

                <!-- Meta Data Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Meta Data</h4>
                        <p class="card-title-desc">Fill all about your product for better SEO</p>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title"
                                           name="meta_title" value="{{ $product->meta_title }}"
                                           placeholder="Enter product meta title">
                                </div>

                                <div class="mb-3">
                                    <label for="metakeywords" class="form-label">Meta Keywords</label>
                                    <input id="metakeywords" name="meta_keywords" type="text"
                                           class="form-control" value="{{ $product->meta_keywords }}"
                                           placeholder="Meta Keywords">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description"
                                              placeholder="Enter product meta description">{{ $product->meta_description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Control Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Product Control</h4>
                        <p class="card-title-desc">Set product options</p>

                        <!-- Product Policy -->
                        <div class="mb-3">
                            <label class="control-label">Product Policy</label>
                            <select class="select2 form-control select2-multiple"
                                    name="product_policy_id[]" multiple="multiple"
                                    data-placeholder="Choose ...">
                                @foreach($productPolicies as $productPolicy)
                                    <option value="{{ $productPolicy->id }}" {{ in_array($productPolicy->id, json_decode($product->product_policy_id, true) ?? []) ? 'selected' : '' }}>
                                        {{ $productPolicy->policy }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Visibility -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                                    {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="form-check-label">Featured Product</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_trending" name="is_trending" value="1"
                                    {{ old('is_trending', $product->is_trending) ? 'checked' : '' }}>
                                <label for="is_trending" class="form-check-label">Trending Product</label>
                            </div>
                        </div>

                        <!-- Owner -->
                        <div class="mb-3">
                            <label class="form-label d-block">Product Owner</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" class="form-check-input" name="product_owner" value="0" {{ old('product_owner', $product->product_owner) == 0 ? 'checked' : '' }}> Own
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="form-check-input" name="product_owner" value="1" {{ old('product_owner', $product->product_owner) == 1 ? 'checked' : '' }}> CJ Dropshipping
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="form-check-input" name="product_owner" value="2" {{ old('product_owner', $product->product_owner) == 2 ? 'checked' : '' }}> AliExpress
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="form-label d-block">Product Status</label>
                            <input type="radio" class="form-check-input" name="status" value="1" {{ old('status', $product->status) == 1 ? 'checked' : '' }}> Publish
                            <input type="radio" class="form-check-input" name="status" value="0" {{ old('status', $product->status) == 0 ? 'checked' : '' }}> Unpublish
                        </div>

                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100">Update Product</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Reusable error cleaner
        const clearError = (inputId) => {
            $(`#${inputId}_error_message`).empty();
        };

        // Apply on inputs (keyup)
        $('#name, #short_description, #selling_price, #sku, #quantity')
            .on('keyup', function () {
                clearError(this.id);
            });

        // Apply on file inputs (change)
        $('#image_thumbnail, #video_thumbnail, #other_images')
            .on('change', function () {
                clearError(this.id);
            });

        // --- Handle Add Variant manually ---
        $(document).on('click', '#addVariantBtn', function () {
            const rowCount = $('#variantTableBody tr').length;
            const newRow = `
                  <tr class="variant-item">
                    <td>
                      <input type="file" name="variants[${rowCount + 1}][image]" accept="image/*" class="form-control form-control-sm variant-image-input" />

                    </td>
                  <td><input type="text" name="variants[${rowCount + 1}][variant_key]" class="form-control form-control-sm" placeholder="Color - Size" /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][buy_price]" class="form-control form-control-sm" placeholder="0.00" /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][suggested_price]" class="form-control form-control-sm" placeholder="0.00" readonly /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][selling_price]" class="form-control form-control-sm" placeholder="0.00" /></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button></td>
                  </tr>`;
            $('#variantTableBody').append(newRow);
        });

        // --- Preview uploaded variant image ---
        $(document).on('change', '.variant-image-input', function () {
            const file = this.files[0];

            // change remove image value
            $(this).next('.remove-image-field').val(0);

            // remove previous image
            $(this).parent().find('.variant-preview').remove();

            // set remove value 1 to remove old image
            const td = $(this).closest('td');
            td.find('.remove-image-field').val(1);

            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    $(this).after(`
                     <div class="variant-preview position-relative mt-1" style="width:60px;">
                        <img src="${e.target.result}" class="img-thumbnail" width="60" height="60" style="object-fit:cover;border-radius:6px;" alt="variant image" />
                        <i class="fa fa-times-circle text-danger remove-variant-image"
                           style="cursor:pointer; position:absolute; top:-2px; right:-2px;">
                        </i>
                    </div>`)
                };
                reader.readAsDataURL(file);
            }
        });

        // remove variant image
        $(document).on('click', '.remove-variant-image', function () {
            const wrapper = $(this).closest('.variant-preview');
            wrapper.next('.remove-image-field').val(1);
            wrapper.prev('.variant-image-input').val(''); // reset file input
            wrapper.remove();
        });

        // remove variant row
        $(document).on('click', '.remove-variant', function () {
            let tr = $(this).closest('tr');
            tr.next('input').val(1);
            tr.remove();
        });

        $(document).ready(function () {
            // tag Inputs
            const metaKeywords = createTagInput('#metakeywords');
            const tags = createTagInput('#tags');

            const oldMetaKeywords = "{{ $product->meta_keywords }}";
            if(oldMetaKeywords) {
                metaKeywords.setValues(oldMetaKeywords);
            }

            const oldTags = "{{ $product->tags }}";
            if(oldTags) {
                tags.setValues(oldTags);
            }

            // helper: Image Preview
            const createImagePreview = (src, width = 100, height = 100, hiddenInputName = null) => {
                let html = `
        <div class="position-relative d-inline-block" style="width:${width}px;height:${height}px;margin:5px;">
            <img src="${src}" class="img-thumbnail rounded-2"
                 style="width:${width}px;height:${height}px;object-fit:cover;border-radius:8px;"
                 loading="lazy" alt="product" referrerpolicy="no-referrer" />
            <i data-img-url="${src}"
               class="position-absolute top-0 end-0 remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
               style="cursor:pointer"></i>
        </div>`;
                if (hiddenInputName) {
                    const id = hiddenInputName.replace(/\[\]/g, '');
                    html += `<input type="hidden" id="${id}" name="${hiddenInputName}" value="${src}">`;
                }
                return html;
            };

            const previewFileInput = (input, wrapper, width = 100, height = 100, hiddenInputName = null) => {
                const files = input.files;
                if (!files.length) return;
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $(wrapper).prepend(createImagePreview(e.target.result, width, height, hiddenInputName));
                    };
                    reader.readAsDataURL(file);
                });
            };

            //  Image thumbnail
            $('#image_thumbnail').on('change', function () {
                const file = this.files[0];
                const previewWrapper = $('#image_thumbnail_preview_wrapper');
                previewWrapper.empty();

                if (!file) return;

                const reader = new FileReader();
                reader.onload = e => {
                    previewWrapper.html(`
                             <div class="position-relative d-inline-block" style="160px;height:140px;margin:5px;">
                                 <img src="${e.target.result}" class="img-thumbnail rounded-2"
                                      style="width:160px;height:140px;object-fit:cover;border-radius:8px;"
                                      loading="lazy" alt="thumbnail preview" referrerpolicy="no-referrer" />
                                  <i data-img-url="${e.target.result}"
                                     class="position-absolute remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
                                     style="cursor:pointer; top:5px; right:-2px">
                                  </i>
                            </div>
                        `);
                };
                reader.readAsDataURL(file);

                $(this).next('input').val(1);
            });

            //  Video thumbnail
            $('#video_thumbnail').on('change', function () {
                const file = this.files[0];
                const previewWrapper = $('#video_thumbnail_preview_wrapper');
                previewWrapper.empty();

                if(!file) return;

                const videoURL = URL.createObjectURL(file);
                previewWrapper.html(`
                                <div class="position-relative d-inline-block" style="width:160px;height:140px;margin:5px;">
                                    <video width="150" height="140" controls muted class="rounded-2" style="border-radius:8px;">
                                        <source src="${videoURL}" type="${file.type}"  />
                                        Your browser does not support video.
                                    </video>
                                    <i data-img-url="${videoURL}"
                                       class="position-absolute remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
                                       style="cursor:pointer; top:5px; right:-2px">
                                    </i>
                                </div>
                    `);

                $(this).next('input').val(1);
            });

            //  Other Images Upload
            $('#other_images').on('change', function () {
                previewFileInput(this, '.other_image_preview', 100, 100);
            });

            // remove Image
            $(document).on('click', '.remove-image', function () {
                const imgUrl = $(this).data('img-url');
                const dataId = $(this).data('id');

                if(dataId) {
                   const input = $(this).next('input');
                    input.val(1);
                    $(this).closest('div').addClass("d-none"); // add d-none class to remove image

                } else {
                    $('#remove_previous_video_thumbnail').val(1);
                    $(this).closest('div').remove(); // remove thumbnail container
                }

                if(imgUrl) {
                    $(`input[value="${imgUrl}"]`).remove(); // remove input  value

                }
            });

            // Calculate Selling Price
            const calculateSellingPrice = () => {
                const originalPrice = parseFloat($('#original_price').val());
                const sellingPrice = $("#selling_price");
                const rawDiscount = $('#discount').val().trim();
                const discountError = $('#discountError');

                if (!originalPrice || !rawDiscount) return sellingPrice.val('');

                if (!/^\d+(\.\d+)?%$/.test(rawDiscount)) {
                    discountError.text("Discount must be a valid percentage (e.g. 10%).");
                    return sellingPrice.val('');
                } else {
                    discountError.text('');
                }

                const discount = parseFloat(rawDiscount.replace('%', ''));
                if (discount < 0 || discount > 100) {
                    discountError.text("Discount must be between 0% and 100%.");
                    return sellingPrice.val(originalPrice);
                }

                const finalPrice = originalPrice - (originalPrice * (discount / 100));
                sellingPrice.val(finalPrice.toFixed(2));
            };

            $('#discount, #original_price').on('input', calculateSellingPrice);

            // Dynamic Subcategories
            $('#category').on('change', function () {
                clearError(this.id);
                const categoryId = $(this).val();
                const subCategorySelect = $('#subCategoryId');
                if (!categoryId)
                    return subCategorySelect.html('<option value="">Select</option>');
                $.get(`/get-sub-categories/${categoryId}`, function (res) {
                    subCategorySelect.html('<option value="">Select</option>');
                    res.forEach(sc => subCategorySelect.append(`<option value="${sc.id}">${sc.name}</option>`));
                });
            });

            // Summernote Init
            $('#long_description').summernote({ height: 200, placeholder: 'Enter product long description...' });
        });
    </script>

@endpush
