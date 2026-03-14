@extends('admin.layouts.master')

@section('title', 'Product Reviews')

@push('css')
    <style>
        /* Product Info Card Styles */
        .product-header-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        /* Review Card Styles */
        .review-card {
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            border-left: 4px solid transparent;
            height: 100%; /* Equal height columns */
        }
        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .review-card.published { border-left-color: #34c38f; } /* Green */
        .review-card.unpublished { border-left-color: #f46a6a; } /* Red */

        /* Star Ratings */
        .star-filled { color: #f1b44c; }
        .star-empty { color: #e2e2e2; }

        /* Review Images */
        .review-gallery-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .review-gallery-img:hover {
            transform: scale(1.1);
            border-color: #adb5bd;
        }

        /* Status Button Customization */
        .btn-status {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 15px;
            border-radius: 50px; /* Pill shape */
        }
    </style>
@endpush

@section('body')
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Reviews for</h4>
                <div class="page-title-right">
                    <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            <!-- Product Details -->
            <div class="text-center text-md-start">
                <h4 class="text-dark fw-bold mb-1">{{ $product->name }}</h4>
                <p class="text-muted mb-2 font-size-13">SKU: <span class="fw-medium text-dark">{{ $product->sku ?? 'N/A' }}</span></p>

                <!-- Stats Badge -->
                <div class="d-flex justify-content-center justify-content-md-start gap-2 mt-2">
                    <span class="badge badge-soft-primary font-size-12 p-2">
                        <i class="fa fa-comment-dots me-1"></i> {{ $product->reviews->count() }} Reviews
                    </span>
                    <span class="badge badge-soft-warning font-size-12 p-2">
                        <i class="fa fa-star me-1"></i> {{ number_format($product->reviews->avg('rating'), 1) }} Average
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 1: PRODUCT INFO (Top) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            <img src="{{ $product->thumbnail }}"
                                 alt="{{ $product->name }}" class="product-header-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: REVIEWS LIST -->
    <div class="row">
        <div class="col-12">
            @if($product->reviews->count() > 0)
                <div class="row g-3">
                    @foreach($product->reviews as $review)
                        <div class="col-xl-6 col-12"> <!-- 2 Columns on Desktop, 1 on Mobile -->
                            <div class="card review-card shadow-sm mb-0 {{ $review->status ? 'published' : 'unpublished' }}">
                                <div class="card-body d-flex flex-column h-100">

                                    <!-- Header: Name, Date, Rating -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-light text-primary font-size-16 fw-bold">
                                                    {{ strtoupper(substr($review->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="font-size-14 mb-0 fw-bold text-dark">{{ $review->name }}</h6>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($review->date)->format('d M, Y') }}</small>
                                            </div>
                                        </div>

                                        <div class="text-end text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star font-size-12 {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <div class="mb-3 p-2 rounded flex-grow-1" style="background-color: #f8f9fa;">
                                        <p class="mb-0 text-secondary font-size-13">
                                            @if($review->message)
                                                "{{ Str::limit($review->message, 180) }}"
                                            @else
                                                <em class="text-muted font-size-12">No text review provided.</em>
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Images -->
                                    @if($review->images && count($review->images) > 0)
                                        <div class="mb-3">
                                            <div class="d-flex gap-2">
                                                @foreach($review->images as $img)
                                                    <a href="{{ $img->image }}" target="_blank">
                                                        <img src="{{ $img->image }}" class="review-gallery-img" alt="Review Image">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center p-5">
                            <div class="mb-3">
                                <div class="avatar-lg mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-comment-off-outline display-4 text-muted"></i>
                                </div>
                            </div>
                            <h4 class="text-dark">No Reviews Yet</h4>
                            <p class="text-muted">There are no reviews associated with this product.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
