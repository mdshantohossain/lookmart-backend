@extends('admin.layouts.master')

@section('title', 'Product Create')

@section('body')
    <div class="row">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Add Product</h4>
                    <div class="page-title-right">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
            </div>
        </div>

        @error('cj_id')
        <div class="row mx-auto">
            <div class=" alert alert-danger">
                <span class="text-danger">You have already created this product</span>
            </div>
        </div>
        @enderror

        <!-- CJ Import Section (Distinct & Mobile Friendly) -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card bg-light border shadow-sm">
                    <div class="card-body p-3">
                        <label for="cj_search" class="form-label fw-bold text-primary">
                            <i class="fa fa-cloud-download-alt me-1"></i> Import from CJ Dropshipping
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cj_search" value="{{ old('sku') }}" placeholder="Enter CJ ID, SKU or Product Name">
                            <button type="button" class="btn btn-primary" id="btnSearchCJ">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                        <div class="mt-2">
                            <p id="cj_status" class="mb-0 fw-bold small"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mx-auto">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Basic Information</h4>
                        <p class="card-title-desc">Fill all information below</p>

                        {{-- Hidden values --}}
                        <input type="hidden" id="cj_id" name="cj_id" value="{{ old('cj_id') }}" />
                        <input type="hidden" id="buy_price" name="buy_price" value="{{ old('buy_price') }}" />

                        <div class="row g-3">

                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="categoryId" class="form-label">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-select" id="categoryId">
                                    <option value="">Select</option>
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
                                <label for="subCategoryId" class="form-label">Sub category</label>
                                <select name="sub_category_id" id="subCategoryId" class="form-select">
                                    <option value="">Select</option>
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
                            <div class="col-md-12">
                                <label for="name" class="form-label">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name') }}" placeholder="Enter product name">
                                @error('name')
                                <span id="name_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Pricing -->
                            <div class="col-md-4">
                                <label for="original_price" class="form-label">Original Price</label>
                                <input type="number" step="0.01" class="form-control" id="original_price"
                                       name="original_price" value="{{ old('original_price') }}" placeholder="0.00">
                                @error('original_price')
                                <span id="original_price_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="text" class="form-control" id="discount" name="discount"
                                       value="{{ old('discount') }}" placeholder="0%">
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
                                       name="selling_price" value="{{ old('selling_price') }}" placeholder="0.00">
                                @error('selling_price')
                                <span id="selling_price_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Suggestion price -->
                            <div id="suggestionPrice"></div>

                            <!-- SKU -->
                            <div class="col-md-6">
                                <label for="sku" class="form-label">
                                    SKU <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="sku" name="sku"
                                       value="{{ old('sku') }}" placeholder="Enter product unique SKU">
                                @error('sku')
                                <span id="sku_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                       value="{{ old('quantity') }}" min="1" placeholder="Enter product quantity">
                                @error('quantity')
                                <span id="quantity_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Main Image -->
                            <div class="col-md-4">
                                <label for="thumbnail" class="form-label">
                                    Product Thumbnail <span class="text-danger">*</span>
                                    <sub class="text-sm text-muted">(image/video)</sub>
                                </label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                       accept="image/*,video/*" />
                                @error('thumbnail')
                                   <span id="thumbnail_error_message" class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="thumbnail_preview_wrapper" class="mt-2">
                                    @if(old('thumbnail'))
                                        <img src="{{ old('thumbnail') }}" style="max-width:120px;" alt="main image" />
                                        <input type="hidden" name="thumbnail" value="{{ old('thumbnail') }}">
                                    @endif
                                </div>
                            </div>

                            <!-- Other Images -->
                            <div class="col-md-8">
                                <label for="other_images" class="form-label">
                                    Gallery Images<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="other_images"
                                       name="other_images[]" multiple accept="image/*">
                                @error('other_images')
                                <span id="other_image_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="other_image_preview">
                                {{--  if have old other images  --}}
                                    @if(old('other_images'))
                                        @foreach(old('other_images') as $otherImage)
                                            <div class="position-relative d-inline-block" style="width:100px;height:100px;margin:5px;">
                                                <input type="hidden" value="{{ $otherImage }}" name="other_images[]">
                                                <img src="{{ $otherImage }}" class="img-thumbnail rounded-2"
                                                     style="width:100px;height:100px;object-fit:cover;border-radius:8px;"
                                                     loading="lazy" alt="product" referrerpolicy="no-referrer" />
                                                <i data-img-url="{{ $otherImage }}"
                                                   class="position-absolute remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
                                                   style="cursor:pointer; top:5px; right:-2px"></i>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Variants Title -->
                            <div class="col-md-4">
                                <label for="variants_title" class="form-label">Variants Title</label>
                                <input type="text" class="form-control" id="variants_title"
                                       name="variants_title" value="{{ old('variants_title') }}"
                                       placeholder="Select color, Size, etc">
                            </div>

                            <div class="col-md-4">
                                <label for="total_sold" class="form-label">Total Sold</label>
                                <input type="text" class="form-control" id="total_sold"
                                       name="total_sold" value="{{ old('total_sold') }}"
                                       placeholder="Product sold count">
                            </div>

                            <div class="col-md-4">
                                <label for="total_day_to_delivery" class="form-label">Total day for delivery</label>
                                <input type="text" class="form-control" id="total_day_to_delivery"
                                       name="total_day_to_delivery" value="{{ old('total_day_to_delivery') }}"
                                       placeholder="Total delivery day">
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
                                        @if(old('variants'))
                                          @foreach(old('variants') as  $key => $variant)
                                              <tr class="variant-item">
                                                  <td>
                                                      <input type="file" name="variants[{{ $key }}][image]" accept="image/*" class="form-control form-control-sm variant-image-input mb-1" />

                                                      <div class="variant-preview position-relative mt-1" style="width:60px;">
                                                          <img src="{{ $variant['image'] }}" class="img-thumbnail" width="60" height="60" style="object-fit:cover;border-radius:6px;" alt="${v.variantKey}" />
                                                          <i class="fa fa-times-circle text-danger remove-variant-image"
                                                             style="cursor:pointer; position:absolute; top:-2px; right:-2px;">
                                                          </i>
                                                          <input type="hidden" name="variants[{{ $key }}][image]" value="{{ $variant['image'] }}">
                                                      </div>

                                                  </td>
                                                  <td><input type="text" name="variants[{{ $key }}][variant_key]" value="{{ $variant['variant_key'] }}" class="form-control form-control-sm" placeholder="Color - Size" /></td>
                                                  <td><input type="number" step="0.01" name="variants[{{ $key }}][buy_price]" value="{{ $variant['buy_price'] }}" class="form-control form-control-sm" placeholder="0.00" /></td>
                                                  <td><input type="number" step="0.01" name="variants[{{ $key }}][suggested_price]" value="{{ $variant['suggested_price'] }}" class="form-control form-control-sm" placeholder="0.00" readonly /></td>
                                                  <td><input type="number" step="0.01" name="variants[{{ $key }}][selling_price]" value="{{ $variant['selling_price'] }}" class="form-control form-control-sm" placeholder="0.00" /></td>
                                                  <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button></td>
                                              </tr>
                                          @endforeach
                                        @endif
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
                                          rows="3" placeholder="Enter product's short description">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                <span id="short_description_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Long Description -->
                            <div class="col-md-12">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea class="form-control" id="long_description" name="long_description"
                                          rows="5" placeholder="Enter product long description">{{ old('long_description') }}</textarea>
                                @error('long_description')
                                <span id="long_description_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12">
                                <label for="tags" class="form-label">Product Tags</label>
                                <input type="text" class="form-control tags" id="tags" name="tags"
                                       value="{{ old('tags') }}" placeholder="e.g. electronics, mobile, laptop">
                                @error('tags')
                                <span id="tags_error_message" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div><!-- row end -->
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
                                    <input type="text" class="form-control meta-title" id="meta_title"
                                           name="meta_title" value="{{ old('meta_title') }}"
                                           placeholder="Enter product meta title">
                                </div>

                                <div class="mb-3">
                                    <label for="metakeywords" class="form-label">Meta Keywords</label>
                                    <input id="metakeywords" name="meta_keywords" type="text"
                                           class="form-control" value="{{ old('meta_keywords') }}"
                                           placeholder="Meta Keywords">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description"
                                              placeholder="Enter product meta description">{{ old('meta_description') }}</textarea>
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
                                    <option value="{{ $productPolicy->id }}">
                                        {{ $productPolicy->policy }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Visibility -->
                        <div class="col-md-12 mb-3">
                            <p class="mb-1 fs-5">Product Visibility & Status</p>

                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="is_featured"
                                       name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="form-check-label">Featured Product</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_trending"
                                       name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}>
                                <label for="is_trending" class="form-check-label">Trending Product</label>
                            </div>
                        </div>

                        <!-- Product Owner -->
                        <div class="mb-3">
                            <label class="form-label d-block">Product Owner</label>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="own"
                                               name="product_owner" value="0"
                                            {{ old('product_owner', '0') === '0' ? 'checked' : '' }}>
                                        <label for="own" class="form-check-label">Own</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="cj_dropshipping"
                                               name="product_owner" value="1"
                                            {{ old('product_owner') === '1' ? 'checked' : '' }}>
                                        <label for="cj_dropshipping" class="form-check-label">CJ Dropshipping</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="aliexpress"
                                               name="product_owner" value="2"
                                            {{ old('product_owner') === '2' ? 'checked' : '' }}>
                                        <label for="aliexpress" class="form-check-label">AliExpress</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Status -->
                        <div>
                            <label class="form-label d-block">Product Status</label>

                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" id="status_published"
                                       name="status" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                                <label for="status_published" class="form-check-label">Publish</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" id="status_unpublished"
                                       name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                                <label for="status_unpublished" class="form-check-label">Unpublish</label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100">Create Product</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#main_image').on('change', function () {
            $('#thumbnail_error_message').empty();
        });

        $('#other_images').on('change', function () {
            $('#other_image_error_message').empty();
        });

        $('#short_description').on('keyup', function () {
            $('#short_description_error_message').empty();
        })

        $('#selling_price').on('keyup', function () {
            $('#selling_price_error_message').empty();
        });

        $('#sku').on('keyup', function () {
            $('#sku_error_message').empty();
        });

        $('#quantity').on('keyup', function () {
            $('#quantity_error_message').empty();
        });

        $('#categoryId').on('change', function () {
            $('#category_error_message').empty();
        });
        $('#name').on('keyup', function () {
            $('#name_error_message').empty();
        })

        // Handle Add Variant manually
        $(document).on('click', '#addVariantBtn', function () {
            const rowCount = $('#variantTableBody tr').length;
            const newRow = `
                  <tr class="variant-item">
                    <td>
                      <input type="file" name="variants[${rowCount + 1}][image]" accept="image/*" class="form-control form-control-sm variant-image-input mb-1" />

                    </td>
                    <td><input type="text" name="variants[${rowCount + 1}][variant_key]" class="form-control form-control-sm" placeholder="Color - Size" /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][buy_price]" class="form-control form-control-sm" placeholder="0.00" /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][suggested_price]" class="form-control form-control-sm" placeholder="0.00" readonly /></td>
                    <td><input type="number" step="0.01" name="variants[${rowCount + 1}][selling_price]" class="form-control form-control-sm" placeholder="0.00" /></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button></td>
                  </tr>`;
            $('#variantTableBody').append(newRow);
        });

        // preview uploaded variant image
        $(document).on('change', '.variant-image-input', function () {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    $(this).after(`
                     <div class="variant-preview position-relative mt-1" style="width:60px;">
                        <img src="${e.target.result}" class="img-thumbnail" width="60" height="60" style="object-fit:cover;border-radius:6px;" alt="variant image" />
                        <i class="fa fa-times-circle text-danger remove-variant-image"
                           style="cursor:pointer; position:absolute; top:-5px; right:-5px;">
                        </i>
                    </div>`)
                };
                reader.readAsDataURL(file);
            }
        });

        // remove variant image
        $(document).on('click', '.remove-variant-image', function () {
            const wrapper = $(this).closest('.variant-preview');
            wrapper.prev('.variant-image-input').val(''); // reset file input
            wrapper.remove();
        });

        // remove variant row
        $(document).on('click', '.remove-variant', function () {
            $(this).closest('tr').remove();
        });

        $(document).ready(function () {
            // tag Inputs
            const metaKeywords = createTagInput('#metakeywords');
            const tags = createTagInput('#tags');

            const metaOldValues = "{{ old('meta_keywords') }}";

            if(metaOldValues) {
                metaKeywords.setValues(metaOldValues);
            }

            const oldTags = "{{ old('tags') }}";
            if(oldTags) {
                tags.setValues(oldTags);
            }

            // helper: Status Message
            const showStatus = (msg, type = '') => {
                const status = $('#cj_status');
                status.removeClass('text-danger text-success').text(msg);
                if (type) status.addClass(type);
            };

            // helper: Image Preview
            const createImagePreview = (src, width = 100, height = 100, hiddenInputName = null) => {
                let html = `
        <div class="position-relative d-inline-block" style="width:${width}px;height:${height}px;margin:5px;">
            <img src="${src}" class="img-thumbnail rounded-2"
                 style="width:${width}px;height:${height}px;object-fit:cover;border-radius:8px;"
                 loading="lazy" alt="product" referrerpolicy="no-referrer" />
            <i data-img-url="${src}"
               class="position-absolute remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
               style="cursor:pointer; top:5px; right:-2px"></i>
        </div>`;
                if (hiddenInputName) {
                    const id = hiddenInputName.replace(/\[\]/g, '');
                    html += `<input type="hidden" id="${id}" name="${hiddenInputName}" value="${src}">`;
                }
                return html;
            };

            const resetPreviews = () => {
                $('#thumbnail_preview_wrapper').empty();
                $('.other_image_preview').empty();
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

            // reset input error
            const resetInputErrors = () => {
                $('#category_error_message, #variants_title_error_message, #sub_category_error_message, #name_error_message, #selling_price_error_message, #original_price_error_message, #quantity_error_message, #sku_error_message, #thumbnail_error_message, #other_image_error_message, #short_description_error_message, #long_description_error_message').empty();
            }

            // CJ Product Search
            $('#btnSearchCJ').on('click', function () {
                const query = $('#cj_search').val().trim();
                if (!query) return showStatus('SKU is required', 'text-danger');

                showStatus('Searching CJ product...');

                // reset previous inputs error
                resetInputErrors();

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
                        showStatus('CJ product imported successfully', 'text-success');

                        resetPreviews();

                        // Basic Info
                        $('#name').val(product.productNameEn ?? '');
                        $('#cj_id').val(product.pid);
                        $('#buy_price').val(product.sellPrice);
                        $('#sku').val(product.productSku ?? '').attr('readonly', true);
                        $('#long_description').summernote('code', product.description ?? '');

                        // Suggested Price Info
                        $('#suggestionPrice').html(`
                    <div class="alert alert-info mb-2">
                        <p><strong>Buy Price:</strong> $${product.sellPrice ?? 0}</p>
                        <p><strong>Suggested Price:</strong> $${product.suggestSellPrice ?? 0}</p>
                    </div>`);

                        // Main Image
                        if (product.productImageSet?.length) {
                            $('#thumbnail_preview_wrapper').append(
                                createImagePreview(product.productImageSet[0], 160, 140, "thumbnail")
                            );
                        }

                        // Other Images
                        if (product.productImageSet?.length > 1) {
                            const container = $('.other_image_preview').empty();
                            product.productImageSet.slice(1).forEach((img, index) => {
                                container.append(createImagePreview(img, 100, 100, `other_images[${index+1000}]`));
                            });
                        }

                        // VARIANTS
                        const $variantSection = $('#variantSection');
                        const $variantBody = $('#variantTableBody');
                        $variantBody.empty();

                        if (product.variants && product.variants.length > 0) {
                            $variantSection.show();
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
                                        variantSku: variant.variantSku,
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
                                        variantSku: variant.variantSku,
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

                            // Render Variant Table
                            let rows = '';
                            const renderRow = (v) => `
                                 <tr class="variant-item">
                                      <td>
                                         <input type="file" name="variants[${v.vid}][image]" accept="image/*" class="form-control form-control-sm variant-image-input" />

                                          <div class="variant-preview position-relative mt-1" style="width:60px;">
                                             <img src="${v.image}" class="img-thumbnail" width="60" height="60" style="object-fit:cover;border-radius:6px;" alt="${v.variantKey}" />
                                                <i class="fa fa-times-circle text-danger remove-variant-image"
                                                   style="cursor:pointer; position:absolute; top:-2px; right:-2px;">
                                                </i>
                                                <input type="hidden" name="variants[${v.vid}][image]" value="${v.image}">
                                          </div>

                                      </td>
                                      <td>
                                        <input type="text" name="variants[${v.vid}][variant_key]" class="form-control form-control-sm" value="${v.variantKey}" readonly />
                                        <input type="hidden" name="variants[${v.vid}][sku]" value="${v.variantSku}"  />
                                      </td>
                                      <td>
                                        <input type="number" step="0.01" name="variants[${v.vid}][buy_price]" class="form-control form-control-sm" value="${v.buy_price}" readonly />
                                      </td>
                                      <td>
                                        <input type="number" step="0.01" name="variants[${v.vid}][suggested_price]" class="form-control form-control-sm" value="${v.suggestion_sell_price}" readonly />
                                      </td>
                                      <td>
                                        <input type="number" step="0.01" name="variants[${v.vid}][selling_price]" class="form-control form-control-sm" placeholder="0.00" />
                                      </td>
                                      <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-variant">

<i class="fa fa-trash"></i>
</button>
                                      </td>
                                    <td class="d-none">
                                    <input type="hidden" name="variants[${v.vid}][vid]" value="${v.vid}" />
                                    <input type="hidden" name="variants[${v.vid}][sku]" value="${v.variantSku}" />
                                    </td>
                                 </tr>`;

                            Object.entries(variantMap).forEach(([color, data]) => {
                                Object.entries(data.sizes).forEach(([size, v]) => {
                                    rows += renderRow(v);
                                });
                                if (data.default) rows += renderRow(data.default);
                            });

                            $variantBody.html(rows);
                        } else {
                            $variantSection.hide();
                        }
                    },
                    error: function () {
                        showStatus('Error connecting to CJ API', 'text-danger');
                    }
                });
            });

            // thumbnail Upload
            $('#thumbnail').on('change', function () {
                const file = this.files[0];
                const previewWrapper = $('#thumbnail_preview_wrapper');
                previewWrapper.empty();

                if (!file) return;

                const type = file.type;

                if (type.startsWith('image/')) {
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
                }

                // if video
                else if (type.startsWith('video/')) {
                    const videoURL = URL.createObjectURL(file);
                    previewWrapper.html(`
                                <div class="position-relative d-inline-block" style="width:160px;height:140px;margin:5px;">
                                    <video width="150" height="140" controls class="rounded-2" style="border-radius:8px;">
                                        <source src="${videoURL}" type="${file.type}" />
                                        Your browser does not support video.
                                    </video>
                                    <i data-img-url="${videoURL}"
                                       class="position-absolute remove-image fa fa-times-circle fa-lg bg-light rounded-circle text-danger"
                                       style="cursor:pointer; top:5px; right:-2px">
                                    </i>
                                </div>
                    `);
                }

                // invalid file
                else {
                    previewWrapper.html(`<p class="text-danger">Invalid file format</p>`);
                }
            });

            //  Other Images Upload
            $('#other_images').on('change', function () {
                previewFileInput(this, '.other_image_preview', 100, 100);
            });

            // Remove Image
            $(document).on('click', '.remove-image', function () {
                const imgUrl = $(this).data('img-url');

                $(`input[value="${imgUrl}"]`).remove();

                if ($(this).parents('#thumbnail_preview_wrapper').length) {
                    $('#thumbnail').val('');
                }

                $(this).closest('div').remove();
            });

            // Calculate Selling Price
            const calculateSellingPrice = () => {
                const regular = parseFloat($('#original_price').val());
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

            $('#discount, #original_price').on('input', calculateSellingPrice);

            // Dynamic Subcategories
            $('#categoryId').on('change', function () {
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
