@extends('admin.layouts.master')

@section('title', 'Edit Product Reviews')

@push('css')
    <style>
        .preview-image-wrapper {
            position: relative; width: 70px; height: 70px;
            border: 1px solid #ddd; border-radius: 6px;
            overflow: hidden; display: inline-block;
            background: #f8f9fa;
        }
        .preview-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .preview-image-wrapper .remove-btn {
            position: absolute; top: 2px; right: 2px;
            background: rgba(255, 0, 0, 0.9); color: white;
            border-radius: 50%; width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; cursor: pointer; z-index: 10;
        }
        /* Highlight existing vs new images slightly */
        .existing-img { border-color: #556ee6; }
    </style>
@endpush

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Reviews</h4>
                <div class="page-title-right">
                    <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reviews.update', $product->id) }}" enctype="multipart/form-data" id="reviewForm">
                        @csrf
                        @method('PUT')

                        {{-- Hidden Container for Deleted IDs --}}
                        <div id="deleted_data_container"></div>

                        {{-- PRODUCT INFO (Read Only) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Product</label>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="d-flex align-items-center p-2 border rounded bg-light">
                                <img src="{{ asset($product->thumbnail ?? 'assets/images/no-img.png') }}"
                                     class="rounded avatar-sm me-3" style="object-fit:cover;" alt="product" />
                                <div>
                                    <h6 class="font-size-14 mb-0 text-dark">{{ $product->name }}</h6>
                                    <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- REVIEWS LIST --}}
                        <div id="review-container">

                            {{-- LOOP EXISTING REVIEWS --}}
                            @foreach($product->reviews as $index => $review)
                                <div class="review-row card border shadow-none mb-3" id="review-row-{{ $review->id }}">

                                    {{-- Hidden ID for updating --}}
                                    <input type="hidden" name="reviews[{{ $index }}][id]" value="{{ $review->id }}">

                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <h5 class="font-size-14 mb-0 text-primary">Review #{{ $index + 1 }}</h5>
                                        {{-- Remove Whole Review Button --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-existing-review"
                                                data-id="{{ $review->id }}">
                                            <i class="fa fa-trash"></i> Remove
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-3">
                                            {{-- Name --}}
                                            <div class="col-md-4">
                                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="reviews[{{ $index }}][name]" class="form-control" value="{{ $review->name }}" required>
                                            </div>

                                            {{-- Rating --}}
                                            <div class="col-md-2">
                                                <label class="form-label">Rating</label>
                                                <select name="reviews[{{ $index }}][rating]" class="form-select">
                                                    @foreach(range(5, 1) as $r)
                                                        <option value="{{ $r }}" {{ $review->rating == $r ? 'selected' : '' }}>{{ $r }} Stars</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Date --}}
                                            <div class="col-md-3">
                                                <label class="form-label">Date</label>
                                                <input type="date" name="reviews[{{ $index }}][date]" class="form-control" value="{{ $review->date }}">
                                            </div>

                                            {{-- Status --}}
                                            <div class="col-md-3">
                                                <label class="form-label">Status</label>
                                                <select name="reviews[{{ $index }}][status]" class="form-select">
                                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Published</option>
                                                    <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Unpublished</option>
                                                </select>
                                            </div>

                                            {{-- IMAGES SECTION --}}
                                            <div class="col-12">
                                                <label class="form-label mb-1">Images</label>

                                                {{-- 1. Show Existing Images --}}
                                                <div class="d-flex flex-wrap gap-2 mb-2 existing-images-area">
                                                    @if($review->images && count($review->images) > 0)
                                                        @foreach($review->images as $img)
                                                            <div class="preview-image-wrapper existing-img">
                                                                <img src="{{ asset($img->image) }}" alt="img">
                                                                {{-- Remove Image Button --}}
                                                                <span class="remove-btn remove-db-image" data-image-id="{{ $img->id }}">&times;</span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>

                                                {{-- 2. Upload New Images --}}
                                                <input type="file" name="reviews[{{ $index }}][new_images][]" class="form-control review-image-input" multiple accept="image/*">
                                                <div class="image-preview-container d-flex flex-wrap gap-2 mt-2"></div>
                                            </div>

                                            {{-- Message --}}
                                            <div class="col-12">
                                                <label class="form-label">Message</label>
                                                <textarea name="reviews[{{ $index }}][message]" class="form-control" rows="2">{{ $review->message }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- ACTIONS --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-between mt-4">
                            <button type="button" id="add-review" class="btn btn-outline-primary">
                                <i class="fa fa-plus me-1"></i> Add Review
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const reviewContainer = document.getElementById('review-container');
                const deletedContainer = document.getElementById('deleted_data_container');
                // Start index after existing count to avoid conflict
                let reviewIndex = {{ count($product->reviews) }};

                // --- 1. REMOVE EXISTING REVIEW ---
                // Appends <input type="hidden" name="remove_reviews[]" value="ID">
                reviewContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-existing-review')) {
                        const btn = e.target.closest('.remove-existing-review');
                        const reviewId = btn.dataset.id;
                        const row = btn.closest('.review-row');

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'remove_reviews[]';
                        input.value = reviewId;
                        deletedContainer.appendChild(input);

                        row.remove();
                    }
                });

                // --- 2. REMOVE EXISTING IMAGE ---
                // Appends <input type="hidden" name="remove_review_images[]" value="ID">
                reviewContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-db-image')) {
                        const btn = e.target;
                        const imageId = btn.dataset.imageId;
                        const wrapper = btn.closest('.preview-image-wrapper');

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'remove_review_images[]'; // The exact name you asked for
                        input.value = imageId;
                        deletedContainer.appendChild(input);

                        wrapper.remove();
                    }
                });

                // --- 3. ADD NEW REVIEW ---
                document.getElementById('add-review').addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.classList.add('review-row', 'card', 'border', 'shadow-none', 'mb-3');

                    newRow.innerHTML = `
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                            <h5 class="font-size-14 mb-0 text-success">New Review</h5>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-new-row"><i class="fa fa-trash"></i> Remove</button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="reviews[${reviewIndex}][name]" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Rating</label>
                                    <select name="reviews[${reviewIndex}][rating]" class="form-select">
                                        <option value="5" selected>5 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="2">2 Stars</option>
                                        <option value="1">1 Star</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="reviews[${reviewIndex}][date]" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="reviews[${reviewIndex}][status]" class="form-select">
                                        <option value="1" selected>Published</option>
                                        <option value="0">Unpublished</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Images <small class="text-muted">(Max 5)</small></label>
                                    <input type="file" name="reviews[${reviewIndex}][new_images][]" class="form-control review-image-input" multiple accept="image/*">
                                    <div class="image-preview-container d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message</label>
                                    <textarea name="reviews[${reviewIndex}][message]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    `;
                    reviewContainer.appendChild(newRow);
                    reviewIndex++;
                });

                // Remove NEW row (from DOM only)
                reviewContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-new-row')) {
                        e.target.closest('.review-row').remove();
                    }
                });

                // --- 4. PREVIEW LOGIC (For New Uploads) ---
                const handleImagePreview = (inputElement) => {
                    const previewContainer = inputElement.nextElementSibling;
                    previewContainer.innerHTML = '';
                    const files = inputElement.files;
                    const maxImages = 5;

                    if (files.length > maxImages) {
                        alert(`Maximum ${maxImages} images allowed.`);
                        const dt = new DataTransfer();
                        for (let i = 0; i < maxImages; i++) dt.items.add(files[i]);
                        inputElement.files = dt.files;
                    }

                    Array.from(inputElement.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('preview-image-wrapper');
                            wrapper.innerHTML = `
                                <img src="${e.target.result}">
                                <span class="remove-btn remove-preview" data-index="${index}">&times;</span>
                            `;
                            previewContainer.appendChild(wrapper);
                        }
                        reader.readAsDataURL(file);
                    });
                };

                reviewContainer.addEventListener('change', function(e) {
                    if (e.target.classList.contains('review-image-input')) handleImagePreview(e.target);
                });

                // Remove Preview Item
                reviewContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-preview')) {
                        const wrapper = e.target.closest('.preview-image-wrapper');
                        const input = wrapper.parentElement.previousElementSibling;
                        const idx = parseInt(e.target.dataset.index);

                        const dt = new DataTransfer();
                        const files = input.files;
                        for (let i = 0; i < files.length; i++) {
                            if (i !== idx) dt.items.add(files[i]);
                        }
                        input.files = dt.files;
                        handleImagePreview(input);
                    }
                });

            });
        </script>
    @endpush
@endsection
