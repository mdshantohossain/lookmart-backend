@extends('admin.layouts.master')

@section('title', 'Create Product Reviews')

@section('body')
    <div class="row">
        <div class="col-xl-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Create Reviews</h3>
                        <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>

                    @foreach($errors->all() as $error)
                        <p class="text-danger">{{ $error }}</p>
                    @endforeach

                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf

                        {{-- Custom Product Selector --}}
                        <div class="mb-3 position-relative" id="product_selector_box">
                            <label class="form-label">Select Product <span class="text-danger">*</span></label>

                            {{-- Selected Product Preview (always visible) --}}
                            <div id="selected_product_preview"
                                 class="border rounded p-2 d-flex align-items-center mb-2"
                                 style="min-height:50px; cursor:pointer;">
                                <span class="text-muted">Click to select a product</span>
                            </div>

                            {{-- Hidden input --}}
                            <input type="hidden" name="product_id" id="product_id">

                            {{-- Product dropdown --}}
                            <div id="product_dropdown" class="border rounded position-absolute bg-white shadow-sm w-100 mt-1"
                                 style="max-height:300px; overflow-y:auto; display:none; z-index:1000;">

                                {{-- Search bar --}}
                                <div class="p-2">
                                    <input type="text" id="product_search" class="form-control" placeholder="Search by name or SKU...">
                                </div>

                                {{-- Product items --}}
                                <div id="product_list">
                                    @foreach ($products as $product)
                                        <div class="product-item d-flex align-items-center p-2 mb-1"
                                             data-id="{{ $product->id }}"
                                             data-name="{{ strtolower($product->name) }}"
                                             data-sku="{{ strtolower($product->sku ?? '') }}"
                                             data-img="{{ asset($product->main_image) }}"
                                             style="cursor:pointer; transition: background 0.2s;">
                                            <img src="{{ asset($product->main_image) }}"
                                                 alt="{{ $product->name }}"
                                                 width="40" height="40" style="border-radius:50%; object-fit:cover; border:1px solid #ddd;" class="me-2">
                                            <div>
                                                <strong>{{ substr($product->name, 0 , 100) }}</strong><br>
                                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('product_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Review rows --}}
                        <div id="review-container">
                            <div class="review-row border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                        <input type="text" name="reviews[0][name]" class="form-control" placeholder="Reviewer name">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Rating<span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="5" name="reviews[0][rating]" class="form-control" placeholder="1-5">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="reviews[0][date]" class="form-control">
                                    </div>


                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Message</label>
                                        <textarea name="reviews[0][message]" class="form-control" rows="3" placeholder="Write a message"></textarea>
                                    </div>


                                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="add-review" class="btn btn-outline-primary btn-sm">Add Another Review</button>
                            <button type="submit" class="btn btn-success">Save All Reviews</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const preview = document.getElementById('selected_product_preview');
            const dropdown = document.getElementById('product_dropdown');
            const hiddenInput = document.getElementById('product_id');
            const searchInput = document.getElementById('product_search');
            const productList = document.getElementById('product_list');
            const productItems = Array.from(productList.querySelectorAll('.product-item'));

            // Show dropdown on preview click
            preview.addEventListener('click', () => {
                dropdown.style.display = 'block';
                searchInput.focus();
            });

            // Hide dropdown if clicked outside
            document.addEventListener('click', function(e) {
                if (!preview.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Search filter with "No products found"
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                let visibleCount = 0;

                productItems.forEach(item => {
                    const name = item.dataset.name;
                    const sku = item.dataset.sku;
                    if (name.includes(term) || sku.includes(term)) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show "No products found" message
                let noResult = productList.querySelector('.no-result');
                if (visibleCount === 0) {
                    if (!noResult) {
                        const div = document.createElement('div');
                        div.classList.add('no-result', 'text-center', 'text-muted', 'p-2');
                        div.textContent = 'No products found';
                        productList.appendChild(div);
                    }
                } else if (noResult) {
                    noResult.remove();
                }
            });

            // Select product
            productItems.forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const sku = this.dataset.sku;
                    const img = this.dataset.img;

                    hiddenInput.value = id;

                    preview.innerHTML = `
                <img src="${img}" width="40" height="40" style="border-radius:50%; object-fit:cover; border:1px solid #ddd;" class="me-2">
                <div><strong>${name}</strong><br><small class="text-muted">SKU: ${sku || 'N/A'}</small></div>
            `;

                    dropdown.style.display = 'none';
                });
            });

            // Dynamic review rows
            let reviewIndex = 1;
            document.getElementById('add-review').addEventListener('click', function () {
                const container = document.getElementById('review-container');
                const newRow = document.createElement('div');
                newRow.classList.add('review-row', 'border', 'rounded', 'p-3', 'mb-3');
                newRow.innerHTML = `
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" name="reviews[${reviewIndex}][name]" class="form-control" placeholder="Reviewer name">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Rating<span class="text-danger">*</span></label>
                    <input type="number" min="1" max="5" name="reviews[${reviewIndex}][rating]" class="form-control" placeholder="1-5">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="reviews[${reviewIndex}][date]" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="reviews[${reviewIndex}][message]" class="form-control" rows="3" placeholder="Write a message"></textarea>
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                </div>
            </div>
        `;
                container.appendChild(newRow);
                reviewIndex++;
            });

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('.review-row').remove();
                }
            });

        });
    </script>
@endsection
