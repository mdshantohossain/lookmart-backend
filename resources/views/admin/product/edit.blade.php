@extends('admin.layouts.master')

@section('title', 'Product Edit')

@section('body')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Product Edit Form</h4>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                        Back
                    </a>
                </div>

                <div class="card-body">
                    {{-- 🔎 CJ Product Import --}}
                    <div class="mb-4 p-3 border rounded bg-light">
                        <label for="cj_search" class="form-label fw-bold">Import from CJ</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cj_search" placeholder="Enter CJ ID, SKU or Product Name">
                            <button type="button" class="btn btn-primary" id="btnSearchCJ">Search CJ</button>
                        </div>
                        <div class="mt-2 text-muted small" >
                            <p id="cj_status"></p>
                        </div>
                    </div>

                    @dd($product)

                    <form method="POST" action="{{ route('products.update', $product->slug) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{--  hidden value--}}
                        <input type="hidden" id="pid" value="{{ $product->pid }}" name="pid" />
                        <input type="hidden" id="buy_price" value="{{ $product->buy_price }}" name="buy_price" />

                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="categoryId" class="form-label">Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" id="categoryId">
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Sub-category -->
                            <div class="col-md-6">
                                <label for="subCategoryId" class="form-label">Sub-category<span class="text-danger">*</span></label>
                                <select name="sub_category_id" id="subCategoryId" class="form-select">
                                    <option value="">Select sub-category</option>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $subCategory->id == old('sub_category_id', $product->sub_category_id) ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-12">
                                <label for="name" class="form-label">Product Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Pricing -->
                            <div class="col-md-4">
                                <label for="regular_price" class="form-label">Original Price</label>
                                <input type="number" step="0.01" class="form-control" id="regular_price" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}">
                                @error('regular_price')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="selling_price" class="form-label">Selling Price<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}">
                                @error('selling_price')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="text" class="form-control" id="discount" name="discount" value="{{ old('discount', $product->discount) }}">
                                <span class="text-danger" id="discountError"></span>
                                @error('discount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="suggestionPrice"></div>

                            <!-- Quantity & SKU -->
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="{{ old('quantity', $product->quantity) }}">
                                @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sku" class="form-label">SKU<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                                @error('sku')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Main Image -->
                            <div class="col-md-6">
                                <label for="main_image" class="form-label">Main Image</label>
                                <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">
                                @error('main_image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="main_image_preview_wrapper" class="mt-2">
                                    @if($product->main_image)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $product->main_image }}" class="mt-2 rounded-2" width="140" height="100" alt="{{ $product->name }}" />
                                            <button data-img-url="${src}" type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image"
                                                    style="border-radius:50%;padding:2px 6px;">×</button>
                                        </div>
                                    @endif
                                    <img id="main_image_preview" src="" alt="cj_image" class="mt-2" style="max-width:120px;display:none;" />
                                </div>
                            </div>

                            <!-- Other Images -->
                            <div class="col-md-6">
                                <label for="other_images" class="form-label">Other Images</label>
                                <input type="file" class="form-control" id="other_images" name="other_images[]" multiple accept="image/*">
                                @error('other_images')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="cj_other_images_wrapper mt-2">


                                    @foreach($product->otherImages as $img)
                                        <div class="position-relative d-inline-block m-1">
                                            <img src="{{ $img->image }}"
                                                 class="img-thumbnail"
                                                 style="width:100px;height:100px;object-fit:cover;border-radius:8px;"
                                                 loading="lazy"
                                                 alt="{{ $img->image }}"
                                            />
                                            <button data-img-url="${src}" type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image"
                                                    style="border-radius:50%;padding:2px 6px;">×</button>
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <!-- Descriptions -->
                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea class="form-control" id="long_description" name="long_description" rows="5">{{ old('long_description', $product->long_description) }}</textarea>
                                @error('long_description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12">
                                <label for="tags" class="form-label">Tags (comma separated)</label>
                                <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $product->tags) }}">
                                @error('tags')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Featured -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                    <label for="featured" class="form-check-label">Featured Product</label>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="status_published" name="status" value="1" {{ old('status', $product->status) == 1 ? 'checked' : '' }} />
                                    <label for="status_published" class="form-check-label">Publish</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="status_unpublished" name="status" value="0" {{ old('status', $product->status) == 0 ? 'checked' : '' }} />
                                    <label for="status_unpublished" class="form-check-label">Unpublish</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function () {

            const showStatus = (msg, type = '') => {
                $('#cj_status').removeClass('text-danger text-success').text(msg);
                if (type) $('#cj_status').addClass(type);
            };

            // create image for every
            const createImagePreview = (src, width = 100, height = 100, hiddenInputName = null) => {
                let html = `
                <div class="position-relative d-inline-block" style="width:${width}px;height:${height}px;margin:5px;">
                    <img src="${src}"
                         class="img-thumbnail"
                         style="width:${width}px;height:${height}px;object-fit:cover;border-radius:8px;"
                         loading="lazy">
                    <button data-img-url="${src}" type="button"
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image"
                            style="border-radius:50%;padding:2px 6px;">×</button>
                </div>
            `;
                if (hiddenInputName) {
                    html += `<input type="hidden" id="${hiddenInputName}" name="${hiddenInputName}" value="${src}">`;
                }
                return html;
            };

            const resetPreviews = () => {
                $('#main_image_preview_wrapper').empty();
                $('#other_images_preview').remove();
                $('#cj_other_images_wrapper').empty();
            };

            const previewFileInput = (input, wrapper, width = 100, height = 100) => {
                let files = input.files;
                if (!files.length) return;

                // clear old previews
                $(wrapper).empty();

                Array.from(files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = e => {
                        $(wrapper).append(createImagePreview(e.target.result, width, height));
                    };
                    reader.readAsDataURL(file);
                });
            };

            // CJ Search
            $('#btnSearchCJ').on('click', function () {

                // remove laravel validation message if exists
                $('#category_error_message, #sub_category_error_message, #name_error_message, #regular_price_error_message, #selling_price_error_message, #discount_error_message, #quantity_error_message, #sku_error_message, #main_image_error_message, #other_image_error_message, #short_description_error_message, #long_description_error_message, #tags_error_message').text('');

                let query = $('#cj_search').val().trim();
                if (!query) return showStatus('Sku is required', 'text-danger');

                showStatus('Searching CJ product...');

                $.ajax({
                    url: "{{ route('cj.product.search') }}",
                    type: "POST",
                    data: { query },
                    headers: {
                        'CJ-Access-Token': "{{ env('CJ_ACCESS_TOKEN') }}",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.code !== 200) return showStatus('No product found in CJ', 'text-danger');

                        let product = res.data;

                        console.log(product);

                        showStatus('CJ product imported successfully', 'text-success');
                        resetPreviews();

                        // Fill fields
                        $('#name').val(product.productNameEn ?? '');
                        $('#pid').val(product.pid);
                        // $('#buy_price').val(product.sellPrice);
                        $('#long_description').summernote('code', product.description ?? '');
                        $('#sku').val(product.productSku ?? '');

                        // Show suggested price UI
                        $('#suggestionPrice').html(`
                            <div class="alert alert-info">
                                <p><strong>Buy Price:</strong> ${product.sellPrice}</p>
                                <p><strong>Suggested Price:</strong> ${product.suggestSellPrice}</p>
                            </div>
                          `);

                        // Main Image
                        if (product.productImageSet?.length) {
                            $('#main_image_preview_wrapper').append(
                                createImagePreview(product.productImageSet[0], 160, 140, "cj_main_image")
                            );
                        }

                        // Other Images
                        if (product.productImageSet?.length > 1) {
                            let wrapper = $('<div id="other_images_preview" class="mt-2 d-flex flex-wrap"></div>');
                            product.productImageSet.slice(1).forEach(img => {
                                wrapper.append(createImagePreview(img, 100, 100, "cj_other_images[]"));
                            });
                            $('#other_images').after(wrapper);
                        }
                    },
                    error: () => showStatus('Error connecting to CJ API', 'text-danger')
                });
            });

            // File Input Previews
            $('#main_image').on('change', function () {
                previewFileInput(this, '#main_image_preview_wrapper', 160, 140);
            });

            $('#other_images').on('change', function () {
                $('#other_images_preview').remove(); // clear old container
                $('<div id="other_images_preview" class="mt-2 d-flex flex-wrap"></div>')
                    .insertAfter('#other_images');
                previewFileInput(this, '#other_images_preview', 100, 100);
            });

            // Remove Images
            $(document).on('click', '.remove-image', function () {
                let imgUrl = $(this).data('img-url');

                // Remove preview + hidden input
                $(this).closest('div').remove();
                $(`#cj_other_images_wrapper input[value="${imgUrl}"]`).remove();

                // Reset file input if main image
                if ($(this).closest('#main_image_preview_wrapper').length) {
                    $('#main_image').val('');
                    $('#cj_main_image').val('');
                }
            });


            const calculateSellingPrice = () => {
                let regular = parseFloat($('#regular_price').val());
                let sellingPrice = $("#selling_price");
                let rawDiscount = $('#discount').val().trim();
                let discountError = $('#discountError');

                if(!regular) {
                    sellingPrice.val('');
                }

                if(rawDiscount && !regular) {
                    sellingPrice.val('');
                }

                if (!regular || !rawDiscount) return

                if (!/^\d+(\.\d+)?%$/.test(rawDiscount)) {
                    discountError.text("Discount must be a valid percentage (e.g. 10%).");
                    sellingPrice.val('');
                    return;
                } else {
                    discountError.text('');
                }



                let discount = parseFloat(rawDiscount.replace('%', ''));

                if (discount < 0 || discount > 100) {
                    discountError.text("Discount must be a (0-100%).");
                    sellingPrice.val(regular);
                    return;
                }

                discountError.text("");

                let finalPrice = regular - (regular * (discount / 100));
                sellingPrice.val(finalPrice.toFixed(2));
            };

            $('#discount').on('input', calculateSellingPrice);
            $('#regular_price').on('input', calculateSellingPrice);

            // Dynamic Subcategories
            $('#categoryId').on('change', function () {
                let categoryId = $(this).val();
                let subCategorySelect = $('#subCategoryId');
                if (!categoryId) return subCategorySelect.html('<option value="">Select sub-category</option>');

                $.get(`/get-sub-categories/${categoryId}`, function (res) {
                    subCategorySelect.html('<option value="">Select sub-category</option>');
                    res.forEach(sc => subCategorySelect.append(`<option value="${sc.id}">${sc.name}</option>`));
                });
            });

            // Summernote Init
            $('#long_description').summernote();
        });
    </script>
@endpush
