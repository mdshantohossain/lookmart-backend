@extends('admin.layouts.master')

@section('title', $product->name)

@section('body')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Product Details</h4>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">Back</a>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    {{-- Left Side: Main Image + Gallery --}}
                    <div class="col-lg-5 col-md-6 text-center">
                        <img src="{{ asset($product->main_image) }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded shadow-sm mb-3"
                             style="max-height: 400px; object-fit: contain;" />

                        @if($product->otherImages && $product->otherImages->count())
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                @foreach($product->otherImages as $image)
                                    <img src="{{ asset($image->image) }}"
                                         class="img-thumbnail"
                                         style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $image->image}}" />
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Right Side: Product Info --}}
                    <div class="col-lg-7 col-md-6">
                        <h3 class="fw-bold">{{ $product->name }}</h3>
                        <p class="text-muted mb-1">SKU: <strong>{{ $product->sku }}</strong></p>
                        <p class="text-muted mb-1">Category: <strong>{{ $product->category->name }}</strong></p>
                        <p class="text-muted mb-1">Sub Category: <strong>{{ $product->subCategory->name }}</strong></p>

                        <div class="my-3">
                            <span class="h4 text-success">${{ $product->selling_price }}</span>
                            @if($product->regular_price)
                                <span class="text-muted text-decoration-line-through ms-2">${{ $product->regular_price }}</span>
                            @endif
                            @if($product->discount)
                                <span class="badge bg-danger ms-2">{{ $product->discount }} OFF</span>
                            @endif
                        </div>

                        <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $product->status ? 'Active' : 'Inactive' }}
                        </span>
                        </p>

                        <p><strong>Trending:</strong>
                            {!! $product->is_trending ? '<span class="badge bg-warning">Trending</span>' : '—' !!}
                        </p>

                        @if($product->colors)
                            <p><strong>Colors:</strong>
                                @foreach(json_decode($product->colors, true) as $color)
                                    <span class="badge border" style="background: {{ $color }};">&nbsp;&nbsp;</span>
                                @endforeach
                            </p>
                        @endif

                        @if($product->sizes)
                            <p><strong>Sizes:</strong> {{ implode(', ', json_decode($product->sizes, true)) }}</p>
                        @endif

                        @if($product->tags)
                            <p><strong>Tags:</strong>
                                @foreach(json_decode($product->tags, true) as $tag)
                                    <span class="badge bg-info text-dark">{{ $tag }}</span>
                                @endforeach
                            </p>
                        @endif

                        @if($product->video)
                            <div class="mt-3">
                                <strong>Product Video:</strong>
                                <div class="ratio ratio-16x9 mt-2">
                                    <iframe src="{{ $product->video }}" allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Full Descriptions --}}
                <div class="mt-4">
                    <h5>Short Description</h5>
                    <p>{{ $product->short_description ?? 'N/A' }}</p>

                    <h5>Long Description</h5>
                    <div>{!! nl2br(e($product->long_description)) !!}</div>
                </div>

                {{-- Policies --}}
                @if($product->product_policies)
                    <div class="mt-4">
                        <h5>Product Policies</h5>
                        <ul>
                            @foreach(json_decode($product->product_policies, true) as $policy)
                                <li>{{ $policy }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
