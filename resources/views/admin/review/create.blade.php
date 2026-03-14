@extends('admin.layouts.master')

@section('title', 'Create Product Reviews')

@push('css')
    <style>
        .product-suggestion:hover { background-color: #f1f5f9; }
        .preview-image-wrapper {
            position: relative; width: 80px; height: 80px;
            border: 1px solid #ddd; border-radius: 6px;
            overflow: hidden; display: inline-block;
        }
        .preview-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .preview-image-wrapper .remove-btn {
            position: absolute; top: 2px; right: 2px;
            background: rgba(255, 0, 0, 0.8); color: white;
            border-radius: 50%; width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; cursor: pointer; z-index: 10;
        }
    </style>
@endpush

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Create Reviews</h4>
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

                    {{-- CSV IMPORT SECTION --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h4 class="card-title mb-0">Add Manual Reviews</h4>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="downloadSampleCSV()" class="btn btn-sm btn-info text-white">
                                <i class="fa fa-download"></i> Sample CSV
                            </button>
                            <label class="btn btn-sm btn-primary mb-0" style="cursor: pointer;">
                                <i class="fa fa-file-csv"></i> Import CSV
                                <input type="file" id="csv_file" accept=".csv" style="display: none;">
                            </label>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data" id="reviewForm">
                        @csrf

                        {{-- Product Selector --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Product <span class="text-danger">*</span></label>
                            <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}">

                            <div class="position-relative" id="search_box_container">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    <input type="text" id="product_search_input" class="form-control" placeholder="Type Product Name or SKU..." autocomplete="off">
                                </div>
                                <div id="search_results_dropdown" class="position-absolute bg-white shadow-lg rounded w-100 border mt-1" style="display:none; z-index: 1050; max-height: 300px; overflow-y: auto;"></div>
                            </div>

                            <div id="selected_product_preview" class="d-none align-items-center justify-content-between p-2 border rounded bg-light border-success">
                                <div class="d-flex align-items-center">
                                    <img id="preview_img" src="" class="rounded avatar-sm me-3" style="object-fit:cover;" alt="search product image" />
                                    <div>
                                        <h6 id="preview_name" class="font-size-14 mb-0 text-dark">Product Name</h6>
                                        <small class="text-muted">SKU: <span id="preview_sku"></span></small>
                                    </div>
                                </div>
                                <button type="button" id="remove_product_btn" class="btn btn-sm btn-outline-danger"><i class="fa fa-times"></i> Change</button>
                            </div>
                        </div>

                        <hr>

                        {{-- REVIEW CONTAINER --}}
                        <div id="review-container">
                            {{-- Rows will be added here via JS (Initial row added on load) --}}
                        </div>

                        <div class="d-flex flex-wrap gap-2 justify-content-between mt-4">
                            <button type="button" id="add-review" class="btn btn-outline-primary waves-effect waves-light">
                                <i class="fa fa-plus me-1"></i> Add Another Review
                            </button>
                            <button type="submit" class="btn btn-success waves-effect waves-light px-4">
                                <i class="fa fa-save me-1"></i> Save Reviews
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // --- 1. CSV HELPER FUNCTIONS ---

            // Generate sample CSV for download
            function downloadSampleCSV() {
                const csvContent = "data:text/csv;charset=utf-8,"
                    + "Name,Rating,Date,Status,Message\n"
                    + "John Doe,5,2023-10-01,Published,Great product loved it!\n"
                    + "Jane Smith,4,2023-10-05,Published,Good quality but shipping was slow.";

                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "sample_reviews.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            // CSV Parser (Handles comma separation)
            function parseCSV(text) {
                const lines = text.split('\n');
                const result = [];

                for(let i = 1; i < lines.length; i++) {
                    if(!lines[i]) continue;

                    // Regex to handle commas inside quotes
                    const currentLine = lines[i].match(/(".*?"|[^",\s]+)(?=\s*,|\s*$)/g);

                    // Simple fallback if regex fails or simple CSV
                    const simpleSplit = lines[i].split(',');

                    // You might need to adjust logic depending on CSV complexity
                    // Assuming simple format: Name,Rating,Date,Status,Message

                    // Use simple split for now, assuming no commas in message or message is last
                    // Ideally, use a library like PapaParse for robust CSV parsing

                    // Let's assume standard format:
                    // Name = 0, Rating = 1, Date = 2, Status = 3, Message = 4 (rest of string)

                    const obj = {
                        name: simpleSplit[0],
                        rating: simpleSplit[1],
                        date: simpleSplit[2],
                        status: simpleSplit[3],
                        message: simpleSplit.slice(4).join(',') // Rejoin message if it had commas
                    };

                    result.push(obj);
                }
                return result;
            }

            document.addEventListener('DOMContentLoaded', function() {

                let reviewIndex = 0;
                const container = document.getElementById('review-container');

                // --- 2. ROW CREATION LOGIC (Reusable) ---

                function addReviewRow(data = null) {
                    const newRow = document.createElement('div');
                    newRow.classList.add('review-row', 'card', 'border', 'shadow-none', 'mb-3');

                    // Defaults
                    let name = '', rating = '5', date = "{{ date('Y-m-d') }}", status = '1', message = '';

                    if(data) {
                        name = data.name.replace(/['"]+/g, '').trim(); // Remove quotes
                        rating = data.rating ? data.rating.trim() : '5';
                        date = data.date ? data.date.trim() : "{{ date('Y-m-d') }}";
                        // Map status text to ID
                        let statusText = data.status ? data.status.toLowerCase().trim() : 'published';
                        status = (statusText === 'unpublished' || statusText === '0') ? '0' : '1';
                        message = data.message ? data.message.replace(/^"|"$/g, '').trim() : '';
                    }

                    newRow.innerHTML = `
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                            <h5 class="font-size-14 mb-0 text-primary">Review #${reviewIndex + 1}</h5>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fa fa-trash"></i> Remove</button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="reviews[${reviewIndex}][name]" class="form-control" value="${name}" placeholder="e.g. Jane Doe" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Rating <span class="text-danger">*</span></label>
                                    <select name="reviews[${reviewIndex}][rating]" class="form-select">
                                        <option value="5" ${rating == '5' ? 'selected' : ''}>5 Stars</option>
                                        <option value="4" ${rating == '4' ? 'selected' : ''}>4 Stars</option>
                                        <option value="3" ${rating == '3' ? 'selected' : ''}>3 Stars</option>
                                        <option value="2" ${rating == '2' ? 'selected' : ''}>2 Stars</option>
                                        <option value="1" ${rating == '1' ? 'selected' : ''}>1 Star</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="reviews[${reviewIndex}][date]" class="form-control" value="${date}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="reviews[${reviewIndex}][status]" class="form-select">
                                        <option value="1" ${status == '1' ? 'selected' : ''}>Published</option>
                                        <option value="0" ${status == '0' ? 'selected' : ''}>Unpublished</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Review Images <small class="text-muted">(Max 3)</small></label>
                                    <input type="file" name="reviews[${reviewIndex}][images][]" class="form-control review-image-input" multiple accept="image/*">
                                    <div class="image-preview-container d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Review Message</label>
                                    <textarea name="reviews[${reviewIndex}][message]" class="form-control" rows="3" placeholder="Write feedback...">${message}</textarea>
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(newRow);
                    reviewIndex++;
                }

                // Add initial empty row
                addReviewRow();

                // Button Click Event
                document.getElementById('add-review').addEventListener('click', () => addReviewRow());

                // Remove Row Event
                container.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-row')) {
                        e.target.closest('.review-row').remove();
                    }
                });

                // --- 3. CSV UPLOAD EVENT ---
                const csvInput = document.getElementById('csv_file');

                csvInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if(!file) return;

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const text = e.target.result;
                        const data = parseCSV(text);

                        if(data.length > 0) {
                            // Clear existing rows (Optional: Comment out this line to append instead)
                            // container.innerHTML = ''; reviewIndex = 0;

                            // Iterate and add rows
                            data.forEach(row => {
                                // Basic validation
                                if(row.name && row.name.toLowerCase() !== 'name') { // skip header if logic missed it
                                    addReviewRow(row);
                                }
                            });

                            alert(`${data.length} reviews imported! Please select images manually if needed.`);
                        }
                    };

                    reader.readAsText(file);
                    // Reset input so same file can be selected again if needed
                    csvInput.value = '';
                });


                // --- 4. IMAGE PREVIEW (Kept Same) ---
                const handleImagePreview = (inputElement) => {
                    const previewContainer = inputElement.nextElementSibling;
                    previewContainer.innerHTML = '';
                    const files = inputElement.files;
                    const maxImages = 3;

                    if (files.length > maxImages) {
                        alert(`You can only upload a maximum of ${maxImages} images.`);
                        const dt = new DataTransfer();
                        for (let i = 0; i < maxImages; i++) dt.items.add(files[i]);
                        inputElement.files = dt.files;
                    }

                    Array.from(inputElement.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('preview-image-wrapper');
                            wrapper.innerHTML = `<img src="${e.target.result}" alt="${e.target.result}"/><span class="remove-btn" data-index="${index}">&times;</span>`;
                            previewContainer.appendChild(wrapper);
                        }
                        reader.readAsDataURL(file);
                    });
                };

                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('review-image-input')) handleImagePreview(e.target);
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-btn')) {
                        const wrapper = e.target.closest('.preview-image-wrapper');
                        const input = wrapper.parentElement.previousElementSibling;
                        const indexToRemove = parseInt(e.target.dataset.index);
                        const dt = new DataTransfer();
                        const files = input.files;
                        for (let i = 0; i < files.length; i++) {
                            if (i !== indexToRemove) dt.items.add(files[i]);
                        }
                        input.files = dt.files;
                        handleImagePreview(input);
                    }
                });

                // --- 5. PRODUCT SEARCH (Kept Same) ---
                const searchInput = document.getElementById('product_search_input');
                const resultsDropdown = document.getElementById('search_results_dropdown');
                const hiddenInput = document.getElementById('product_id');
                const searchContainer = document.getElementById('search_box_container');
                const previewContainer = document.getElementById('selected_product_preview');
                const removeBtn = document.getElementById('remove_product_btn');
                let debounceTimer;

                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();
                    if (query.length < 2) { resultsDropdown.style.display = 'none'; return; }
                    debounceTimer = setTimeout(() => { fetchProducts(query); }, 400);
                });

                function fetchProducts(query) {
                    resultsDropdown.style.display = 'block';
                    resultsDropdown.innerHTML = '<div class="p-2 text-center text-muted">Searching...</div>';
                    fetch("{{ route('products.search') }}", {
                        method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ search: query })
                    })
                        .then(r => r.json()).then(renderResults)
                        .catch(e => resultsDropdown.innerHTML = '<div class="p-2 text-center text-danger">Error</div>');
                }

                function renderResults(products) {
                    if (products.length === 0) { resultsDropdown.innerHTML = '<div class="p-2 text-center text-muted">No products found</div>'; return; }
                    let html = '';
                    products.forEach(p => {
                        html += `<div class="product-suggestion d-flex align-items-center p-2 border-bottom" data-id="${p.id}" data-name="${p.name}" data-sku="${p.sku||'N/A'}" data-img="${p.image_thumbnail}" style="cursor:pointer;">
                            <img src="${p.image_thumbnail}" class="rounded avatar-xs me-2" style="object-fit:cover;">
                            <div><div class="fw-bold font-size-13 text-dark">${p.name}</div><small class="text-muted">SKU: ${p.sku||'N/A'}</small></div>
                        </div>`;
                    });
                    resultsDropdown.innerHTML = html;
                    document.querySelectorAll('.product-suggestion').forEach(item => item.addEventListener('click', function() { selectProduct(this.dataset); }));
                }

                function selectProduct(data) {
                    hiddenInput.value = data.id;
                    document.getElementById('preview_img').src = data.img;
                    document.getElementById('preview_name').textContent = data.name;
                    document.getElementById('preview_sku').textContent = data.sku;
                    searchContainer.classList.add('d-none');
                    previewContainer.classList.remove('d-none');
                    previewContainer.classList.add('d-flex');
                    resultsDropdown.style.display = 'none';
                    searchInput.value = '';
                }

                removeBtn.addEventListener('click', function() {
                    hiddenInput.value = '';
                    previewContainer.classList.add('d-none');
                    previewContainer.classList.remove('d-flex');
                    searchContainer.classList.remove('d-none');
                });

                document.addEventListener('click', function(e) {
                    if (!searchContainer.contains(e.target)) resultsDropdown.style.display = 'none';
                });
            });
        </script>
    @endpush
@endsection
