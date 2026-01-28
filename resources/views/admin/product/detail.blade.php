@extends('admin.layouts.master')

@section('title', 'Product Details')

@section('body')
    <div class="row">
        <!-- Page Title & Back Button -->
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Product Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('products.edit', $product->slug) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm ms-1">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- LEFT COLUMN: Images, Status & Key Stats -->
        <div class="col-xl-4">
            <!-- Main Image / Video Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Product Thumbnail</h5>
                    <div class="product-img text-center p-3 border rounded">

                        <img src="{{ $product->image_thumbnail }}" alt="{{ $product->name }}" class="img-fluid d-block mx-auto rounded mb-3" style="max-height: 300px;">

                        @if($product->video_thumbnail)
                            <video class="img-fluid d-block mx-auto rounded" controls style="max-height: 300px;">
                                <source src="{{ $product->video_thumbnail }}" type="video/">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>

                    <!-- Gallery Images -->
                    @if($product->otherImages && $product->otherImages->count() > 0)
                        <div class="mt-4">
                            <h5 class="font-size-14 mb-2">Gallery Images</h5>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                @foreach($product->otherImages as $img)
                                    <a href="{{ $img->image }}" target="_blank" class="border rounded p-1">
                                        <img src="{{ $img->image }}" class="avatar-md object-fit-cover rounded" alt="Product Gallery">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status & ownership Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Status & Owner</h5>
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0">
                            <tbody>
                            <tr>
                                <th scope="row">Status</th>
                                <td>
                                    @if($product->status == 1)
                                        <span class="badge bg-success font-size-12">Published</span>
                                    @else
                                        <span class="badge bg-danger font-size-12">Unpublished</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Owner</th>
                                <td>
                                    @if($product->product_owner == 0)
                                        <span class="badge badge-soft-primary">Own Product</span>
                                    @elseif($product->product_owner == 1)
                                        <span class="badge badge-soft-warning">CJ Dropshipping</span>
                                    @else
                                        <span class="badge badge-soft-info">AliExpress</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Free delivery</th>
                                <td>
                                    <i class="fa {{ $product->is_free_delivery ? 'fa-check-circle text-success' : 'fa-times-circle text-secondary' }}"></i>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Featured</th>
                                <td>
                                    <i class="fa {{ $product->is_featured ? 'fa-check-circle text-success' : 'fa-times-circle text-secondary' }}"></i>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Trending</th>
                                <td>
                                    <i class="fa {{ $product->is_trending ? 'fa-check-circle text-success' : 'fa-times-circle text-secondary' }}"></i>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Total Sold</th>
                                <td>{{ $product->total_sold ?? 0 }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Details, Variants, Description -->
        <div class="col-xl-8">
            <!-- General Info Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between flex-wrap">
                        <div>
                            <h4 class="card-title mb-2">{{ $product->name }}</h4>
                            <p class="text-muted mb-2">SKU: <span class="fw-bold text-dark">{{ $product->sku }}</span></p>
                        </div>
                        <div class="text-end">
                            <h4 class="text-primary mb-0">${{ number_format($product->selling_price, 2) }}</h4>
                            @if($product->original_price > $product->selling_price)
                                <p class="text-muted text-decoration-line-through mb-0 font-size-13">
                                    ${{ number_format($product->original_price, 2) }}
                                </p>
                                <small class="text-danger fw-bold">{{ $product->discount }}% OFF</small>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Category</p>
                            <h6 class="font-size-14">{{ $product->category->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Sub Category</p>
                            <h6 class="font-size-14">{{ $product->subCategory->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6 mt-3">
                            <p class="text-muted mb-1">Stock Quantity</p>
                            <h6 class="font-size-14">{{ $product->quantity }} Units</h6>
                        </div>
                        <div class="col-md-6 mt-3">
                            <p class="text-muted mb-1">Est. Delivery</p>
                            <h6 class="font-size-14">{{ $product->total_day_to_delivery ?? 'N/A' }} Days</h6>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted mb-1">Short Description</p>
                        <p class="text-body">{{ $product->short_description }}</p>
                    </div>

                    <!-- Tags -->
                    @if($product->tags)
                        <div class="mt-3">
                            <p class="text-muted mb-2">Tags</p>
                            @foreach(explode(',', $product->tags) as $tag)
                                <span class="badge badge-soft-info font-size-12">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Variants Section (Only shows if variants exist) -->
            @if($product->variants && $product->variants->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Product Variants ({{ $product->variants_title ?? 'Items' }})</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Image</th>
                                    <th>Variant Key</th>
                                    <th>Buy Price</th>
                                    <th>Suggested</th>
                                    <th>Selling Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->variants as $variant)
                                    <tr>
                                        <td>
                                            @if($variant->image)
                                                <img src="{{ $variant->image }}" alt="" class="rounded avatar-sm object-fit-cover">
                                            @else
                                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                                    <i class="bx bx-image font-size-18"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $variant->variant_key }}</td>
                                        <td>{{ $variant->buy_price ? '$'.number_format($variant->buy_price, 2) : '-' }}</td>
                                        <td>{{ $variant->suggested_price ? '$'.number_format($variant->suggested_price, 2) : '-' }}</td>
                                        <td class="text-success fw-bold">${{ number_format($variant->selling_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Long Description -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Product Description</h5>
                    <div class="product-desc-content">
                        {!! $product->long_description !!}
                    </div>
                </div>
            </div>

            <!-- Meta Data & Policies -->
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#seo" role="tab">SEO / Meta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#policies" role="tab">Policies</a>
                        </li>
                    </ul>

                    <div class="tab-content p-3 text-muted">
                        <!-- SEO Tab -->
                        <div class="tab-pane active" id="seo" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block mb-1">Meta Title</strong>
                                    <span>{{ $product->meta_title ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block mb-1">Meta Keywords</strong>
                                    <span>{{ $product->meta_keywords ?? 'N/A' }}</span>
                                </div>
                                <div class="col-12">
                                    <strong class="d-block mb-1">Meta Description</strong>
                                    <p>{{ $product->meta_description ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Policies Tab -->
                        <div class="tab-pane" id="policies" role="tabpanel">
                            @if($product->product_policy_id)
                                @php
                                    $policyIds = json_decode($product->product_policy_id, true) ?? [];
                                    // Assuming you are passing $productPolicies to view or accessing via relationship.
                                    // If strict relationship isn't set, we might need to rely on what was passed to controller
                                @endphp
                                <ul class="list-unstyled">
                                    {{-- If you have a relationship set up in model: $product->policies --}}
                                    {{-- Otherwise, just listing IDs or requiring controller change --}}
                                    @if(isset($productPolicies))
                                        @foreach($productPolicies as $policy)
                                            @if(in_array($policy->id, $policyIds))
                                                <li class="mb-2"><i class="mdi mdi-circle-medium text-primary me-1"></i> {{ $policy->policy }}</li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li>Policies are attached (IDs: {{ implode(', ', $policyIds) }})</li>
                                    @endif
                                </ul>
                            @else
                                <p>No specific policies attached.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
