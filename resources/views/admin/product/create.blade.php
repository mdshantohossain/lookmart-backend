@extends('admin.layouts.master')

@section('title', 'Product Create')

@section('body')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Product Create Form</h4>
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

                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{--  hidden value--}}
                        <input type="hidden" id="cj_id" name="cj_id" />
                        <input type="hidden" id="buy_price" name="buy_price" />

                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="categoryId" class="form-label">Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" id="categoryId">
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>
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
                                <label for="subCategoryId" class="form-label">Sub-category<span class="text-danger">*</span></label>
                                <select name="sub_category_id" id="subCategoryId" class="form-select">
                                    <option value="">Select sub-category</option>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $subCategory->id == old('sub_category_id') ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_category_id')
                                <span id="sub_category_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Name -->
                            <div>
                                <label for="name" class="form-label">Product Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter product name">
                                @error('name')
                                <span id="name_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Pricing -->
                            <div class="col-md-4">
                                <label for="regular_price" class="form-label">Original Price</label>
                                <input type="number" step="0.01" class="form-control" id="regular_price" name="regular_price" value="{{ old('regular_price') }}" placeholder="0.00" />
                                @error('regular_price')
                                <span id="regular_price_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="selling_price" class="form-label">Selling Price<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" placeholder="0.00" />
                                @error('selling_price')
                                <span id="selling_price_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="text" class="form-control" id="discount" name="discount" value="{{ old('discount') }}" placeholder="Enter discount amount" />
                                <span class="text-danger" id="discountError"></span>
                                @error('discount')
                                <span id="discount_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div id="suggestionPrice"></div>

                            <!-- Quantity & SKU -->
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Enter product quantity">
                                @error('quantity')
                                <span id="quantity_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sku" class="form-label">SKU<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" placeholder="Enter unique SKU">
                                @error('sku')
                                <span id="sku_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Images -->
                            <div class="col-md-6">
                                <label for="main_image" class="form-label">Main Image<span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">
                                @error('main_image')
                                <span id="main_image_error_message" class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="main_image_preview_wrapper" class="mt-2">
                                    <img id="main_image_preview" src="" alt="cj_image" class="mt-2" style="max-width:120px;display:none;" />
                                </div>
                            </div>

                            {{--Other images--}}
                            <div class="col-md-6">
                                <label for="other_images" class="form-label">Other Images</label>
                                <input type="file" class="form-control" id="other_images" name="other_images[]" multiple accept="image/*">
                                @error('other_images')
                                <span id="other_image_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="other_image_preview"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="video" class="form-label">Product Video</label>
                                <input type="file" class="form-control" id="video" name="video" />
                                @error('video')
                                <span id="video" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

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

                            <!-- Variants Section -->
                            <div class="col-md-12 mt-4" id="variantSection" style="display:none;">
                                <h5 class="fw-bold mb-3">Product Variants</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Variant Key</th>
                                            <th>CJ Buy Price</th>
                                            <th>Suggested Price</th>
                                            <th>Set Selling Price</th>
                                        </tr>
                                        </thead>
                                        <tbody id="variantTableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="3" placeholder="Enter product's short description">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                <span id="short_description_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea class="form-control" id="long_description" name="long_description" rows="5" placeholder="Enter product's long description">{{ old('long_description') }}</textarea>
                                @error('long_description')
                                <span id="long_description_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
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

                            <!-- Featured -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label for="is_featured" class="form-check-label">Featured Product</label>
                                </div>
                            </div>

                            <!-- Trending -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_trending" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}>
                                    <label for="is_trending" class="form-check-label">Trending Product</label>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="status_published" name="status" checked value="1" {{ old('status') ? 'checked' : '' }} />
                                    <label for="status_published" class="form-check-label">Publish</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="status_unpublished" name="status" value="0" {{ old('status', 0) ? 'checked' : '' }} />
                                    <label for="status_unpublished" class="form-check-label">Unpublish</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100">Create Product</button>
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

            // Tag Inputs
            const sizesTagInput = createTagInput('#sizes');
            createTagInput('#meta_title');
            createTagInput('#tags');

            // Helper: Status Message
            const showStatus = (msg, type = '') => {
                $('#cj_status').removeClass('text-danger text-success').text(msg);
                if (type) $('#cj_status').addClass(type);
            };

            // Helper: Image Preview
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
                    html += `<input type="hidden" id="${hiddenInputName}" name="${hiddenInputName}" value="${src}">`;
                }
                return html;
            };

            const resetPreviews = () => {
                $('#main_image_preview_wrapper').empty();
                $('.other_image_preview').empty();
                $('.color-previews-container').empty();
            };

            const previewFileInput = (input, wrapper, width = 100, height = 100) => {
                const files = input.files;
                if (!files.length) return;
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $(wrapper).prepend(createImagePreview(e.target.result, width, height));
                    };
                    reader.readAsDataURL(file);
                });
            };

            // CJ Product Search
            $('#btnSearchCJ').on('click', function () {
                const query = $('#cj_search').val().trim();
                if (!query) return showStatus('SKU is required', 'text-danger');

                showStatus('Searching CJ product...');

                $.ajax({
                    url: "{{ route('cj.product.search') }}",
                    type: "POST",
                    data: { query },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) {
                        if (res.code !== 200 || !res.data) {
                            return showStatus('No product found in CJ', 'text-danger');
                        }

                        const product = res.data;
                        console.log("✅ Product:", product);
                        showStatus('CJ product imported successfully', 'text-success');

                        resetPreviews();

                        // Basic Info
                        $('#name').val(product.productNameEn ?? '');
                        $('#cj_id').val(product.pid);
                        $('#buy_price').val(product.sellPrice);
                        $('#sku').val(product.productSku ?? '').attr('disabled', true);
                        $('#long_description').summernote('code', product.description ?? '');

                        // Suggested Price Info
                        $('#suggestionPrice').html(`
                    <div class="alert alert-info mb-2">
                        <p><strong>Buy Price:</strong> $${product.sellPrice ?? 0}</p>
                        <p><strong>Suggested Price:</strong> $${product.suggestSellPrice ?? 0}</p>
                    </div>`);

                        // Main Image
                        if (product.productImageSet?.length) {
                            $('#main_image_preview_wrapper').append(
                                createImagePreview(product.productImageSet[0], 160, 140, "cj_main_image")
                            );
                        }

                        // Other Images
                        if (product.productImageSet?.length > 1) {
                            const container = $('.other_image_preview').empty();
                            product.productImageSet.slice(1).forEach(img => {
                                container.append(createImagePreview(img, 100, 100, "cj_other_images[]"));
                            });
                        }

                        // VARIANTS
                        const $variantSection = $('#variantSection');
                        const $variantBody = $('#variantTableBody');
                        $variantBody.empty();

                        if (product.variants && product.variants.length > 0) {
                            $variantSection.show();
                            const sizes = [];
                            const imageSet = new Set();

                           const variantMap = {}; // color → { image, sizes: {}, default: {...} }
                            product.variants.forEach((variant, index) => {
                                if (!variant.variantKey) return;

                                // Split variantKey (example: "Black-L", "Same as Photos", "500ml", "Type c-Red")
                                const parts = variant.variantKey.split('-');
                                let baseColor = parts[0]?.trim() || '';
                                let size = parts[1]?.trim() || '';

                                // If color is missing, create a unique default group
                                if (!baseColor) {
                                    baseColor = `Default-${Object.keys(variantMap).length + 1}`;
                                }

                                // Initialize color group if not exists
                                if (!variantMap[baseColor]) {
                                    variantMap[baseColor] = {
                                        image: variant.variantImage || '',
                                        sizes: {},
                                        default: null
                                    };
                                }

                                // Assign image if not yet set
                                if (!variantMap[baseColor].image && variant.variantImage) {
                                    variantMap[baseColor].image = variant.variantImage;
                                }

                                // Case 1: Has size → store under sizes[size]
                                if (size) {
                                    variantMap[baseColor].sizes[size] = {
                                        vid: variant.vid,
                                        variantKey: variant.variantKey,
                                        buy_price: variant.variantSellPrice,
                                        suggestion_sell_price: variant.variantSugSellPrice,
                                        selling_price: '',
                                        width: variant.variantWidth,
                                        height: variant.variantHeight,
                                        weight: variant.variantWeight,
                                        length: variant.variantLength,
                                        image: variant.variantImage
                                    };
                                }
                                // Case 2: No size → store as default
                                else {
                                    variantMap[baseColor].default = {
                                        vid: variant.vid,
                                        variantKey: variant.variantKey,
                                        buy_price: variant.variantSellPrice,
                                        suggestion_sell_price: variant.variantSugSellPrice,
                                        selling_price: '',
                                        width: variant.variantWidth,
                                        height: variant.variantHeight,
                                        weight: variant.variantWeight,
                                        length: variant.variantLength,
                                        image: variant.variantImage
                                    };
                                }
                            });


                            //  Apply Sizes
                            sizesTagInput.setValues(sizes);

                            //  Show Color Previews (no duplicates)
                            $('.color-previews-container').empty();
                            Object.entries(variantMap).forEach(([color, data]) => {
                                const img = data.image || data.default?.image;
                                if (img && !imageSet.has(img)) {
                                    imageSet.add(img);
                                    colorImagePreview(img);
                                }
                            });

                            // Render Variant Table
                            let rows = '';
                            const renderRow = (v, color, sizeLabel) => `
                        <tr>
                            <td class="text-center">
                                <img src="${v.image}" width="60" height="60" style="object-fit:cover;border-radius:6px;"
                                     alt="${color}-${sizeLabel}" referrerpolicy="no-referrer" />
                            </td>
                            <td>${v.variantKey}</td>
                            <td>$${Number(v.buy_price).toFixed(2)}</td>
                            <td>$${Number(v.suggestion_sell_price).toFixed(2)}</td>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                    name="variants[${v.vid}][selling_price]" placeholder="Enter price">
                                <input type="hidden" name="variants[${v.vid}][vid]" value="${v.vid}">
                                <input type="hidden" name="variants[${v.vid}][variant_key]" value="${v.variantKey}">
                                <input type="hidden" name="variants[${v.vid}][buy_price]" value="${v.buy_price}">
                                <input type="hidden" name="variants[${v.vid}][suggestion_sell_price]" value="${v.suggestion_sell_price}">
                                <input type="hidden" name="variants[${v.vid}][width]" value="${v.width}">
                                <input type="hidden" name="variants[${v.vid}][height]" value="${v.height}">
                                <input type="hidden" name="variants[${v.vid}][weight]" value="${v.weight}">
                                <input type="hidden" name="variants[${v.vid}][length]" value="${v.length}">
                                <input type="hidden" name="variants[${v.vid}][color]" value="${color}">
                                <input type="hidden" name="variants[${v.vid}][size]" value="${sizeLabel}">
                                <input type="hidden" name="variants[${v.vid}][image]" value="${v.image}">
                            </td>
                        </tr>`;

                            Object.entries(variantMap).forEach(([color, data]) => {
                                Object.entries(data.sizes).forEach(([size, v]) => {
                                    rows += renderRow(v, color, size);
                                });
                                if (data.default) rows += renderRow(data.default, color, 'Default');
                            });

                            $variantBody.html(rows);

                            // --- ✅ Append single JSON input to send clean data to backend
                            const form = $('form');
                            form.find('input[name="variant_json"]').remove(); // remove old if exist
                            form.append(`<input type="hidden" name="variant_json" value='${JSON.stringify(variantMap)}'>`);
                        } else {
                            $variantSection.hide();
                        }
                    },
                    error: function () {
                        showStatus('Error connecting to CJ API', 'text-danger');
                    }
                });
            });

            //  Color Preview
            function colorImagePreview(image) {
                const container = $('.color-previews-container');
                container.append(createImagePreview(image, 60, 60));
            }

            //  Main Image Upload
            $('#main_image').on('change', function () {
                const container = $('#main_image_preview_wrapper').empty();
                previewFileInput(this, container, 160, 140);
            });

            //  Other Images Upload
            $('#other_images').on('change', function () {
                previewFileInput(this, '.other_image_preview', 100, 100);
            });

            // Color Upload
            $('#colors').on('change', function () {
                previewFileInput(this, '.color-previews-container', 60, 60);
            });

            // Remove Image
            $(document).on('click', '.remove-image', function () {
                const imgUrl = $(this).data('img-url');
                $(this).closest('div').remove();
                $(`input[value="${imgUrl}"]`).remove();
                if ($(this).closest('#main_image_preview_wrapper').length) {
                    $('#main_image').val('');
                    $('#cj_main_image').val('');
                }
            });

            // Calculate Selling Price
            const calculateSellingPrice = () => {
                const regular = parseFloat($('#regular_price').val());
                const sellingPrice = $("#selling_price");
                const rawDiscount = $('#discount').val().trim();
                const discountError = $('#discountError');

                if (!regular || !rawDiscount) return sellingPrice.val('');

                if (!/^\d+(\.\d+)?%$/.test(rawDiscount)) {
                    discountError.text("Discount must be a valid percentage (e.g. 10%).");
                    return sellingPrice.val('');
                } else {
                    discountError.text('');
                }

                const discount = parseFloat(rawDiscount.replace('%', ''));
                if (discount < 0 || discount > 100) {
                    discountError.text("Discount must be between 0% and 100%.");
                    return sellingPrice.val(regular);
                }

                const finalPrice = regular - (regular * (discount / 100));
                sellingPrice.val(finalPrice.toFixed(2));
            };
            $('#discount, #regular_price').on('input', calculateSellingPrice);

            // Dynamic Subcategories
            $('#categoryId').on('change', function () {
                const categoryId = $(this).val();
                const subCategorySelect = $('#subCategoryId');
                if (!categoryId)
                    return subCategorySelect.html('<option value="">Select sub-category</option>');
                $.get(`/get-sub-categories/${categoryId}`, function (res) {
                    subCategorySelect.html('<option value="">Select sub-category</option>');
                    res.forEach(sc => subCategorySelect.append(`<option value="${sc.id}">${sc.name}</option>`));
                });
            });

            // 📝 Summernote Init
            $('#long_description').summernote();
        });
    </script>

@endpush



{{--const variantMap = {}; // color -> { image, sizes: { size: variant } }--}}

{{--product.variants.forEach(variant => {--}}
{{--if (!variant.variantKey) return;--}}
{{--const parts = variant.variantKey.split('-');--}}
{{--const color = parts[0]?.trim();--}}
{{--const size = parts[1]?.trim();--}}

{{--if (!color) return;--}}

{{--if (!variantMap[color]) {--}}
{{--// store first image we find for this color--}}
{{--variantMap[color] = {--}}
{{--image: variant.variantImage,--}}
{{--sizes: {}--}}
{{--};--}}
{{--}--}}

{{--// If this color has no image yet, and this variant has one, use it--}}
{{--if (!variantMap[color].image && variant.variantImage) {--}}
{{--variantMap[color].image = variant.variantImage;--}}
{{--}--}}

{{--// Save size info under this color--}}
{{--if (size) {--}}
{{--variantMap[color].sizes[size] = {--}}
{{--vid: variant.vid,--}}
{{--price: variant.variantSellPrice,--}}
{{--suggest: variant.variantSugSellPrice,--}}
{{--image: variant.variantImage--}}
{{--};--}}
{{--}--}}
{{--});--}}




{{--const productVariants = {--}}
{{--colors: {--}}
{{--"Red": {--}}
{{--image: "https://example.com/red.jpg",--}}
{{--sizes: {--}}
{{--"S": {--}}
{{--vid: "12345",--}}
{{--variantKey: "Red-S",--}}
{{--buy_price: 10.5,--}}
{{--selling_price: 12.99,--}}
{{--suggestion_sell_price: 13.99,--}}
{{--width: 10,--}}
{{--height: 15,--}}
{{--weight: 0.3,--}}
{{--length: 20--}}
{{--},--}}
{{--"M": {--}}
{{--vid: "12346",--}}
{{--variantKey: "Red-M",--}}
{{--buy_price: 11.0,--}}
{{--selling_price: 13.5,--}}
{{--suggestion_sell_price: 14.99,--}}
{{--width: 10,--}}
{{--height: 15,--}}
{{--weight: 0.35,--}}
{{--length: 22--}}
{{--}--}}
{{--}--}}
{{--},--}}
{{--"Blue": {--}}
{{--image: "https://example.com/blue.jpg",--}}
{{--sizes: {} // No sizes, maybe one default variant--}}
{{--}--}}
{{--},--}}
{{--noVariant: {--}}
{{--vid: "99999",--}}
{{--variantKey: "Default",--}}
{{--buy_price: 5.0,--}}
{{--selling_price: 7.0,--}}
{{--suggestion_sell_price: 7.5,--}}
{{--width: 12,--}}
{{--height: 8,--}}
{{--weight: 0.2,--}}
{{--length: 10--}}
{{--}--}}
{{--};--}}



