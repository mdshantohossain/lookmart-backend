@extends('website.layouts.master')

@section('title', 'Wishlist')

@section('body')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Wishlist</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
{{--                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>--}}
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item active">Wishlist</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive wishlist_table">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-stock-status">Stock Status</th>
                                    <th class="product-add-to-cart"></th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($wishlists as $wishlist)
                                    <tr>
                                        <td class="product-thumbnail"><a href="#">
                                                <img src="{{ $wishlist->product->main_image }}" width="120" height="100" class="rounded-2" alt="product1" />
                                            </a></td>
                                        <td class="product-name" data-title="Product">
                                            <a href="{{ route('product.detail', $wishlist->product->slug) }}">{{ $wishlist->product->name  }}</a>
                                        </td>
                                        <td class="product-price" data-title="Price">TK.{{ $wishlist->product->selling_price }}</td>
                                        <td class="product-stock-status" data-title="Stock Status">
                                            <span class="badge rounded-pill {{ $wishlist->product->quantity >= 1 ? 'text-bg-success' : 'text-bg-danger' }}">{{ $wishlist->product->quantity >= 1 ? 'In Stock' : 'Out Of Stock' }}</span>
                                        </td>
                                        <td class="product-add-to-cart">
                                            <button
                                                onclick="addToCartFromWishlist('{{ $wishlist->product->slug }}')"
                                                type="button" class="btn btn-fill-out"
                                                    @if(isProductInCart($wishlist->product->id))
                                                        disabled
                                                    @endif
                                                data-slug="{{ $wishlist->product->slug }}"
                                            ><i class="icon-basket-loaded"></i>{{ isProductInCart($wishlist->product->id) ? 'Already in cart' : 'Add to Cart' }}</button>
                                        </td>
                                        <td class="product-remove" data-title="Remove">
                                            <a href="javascript: void(0)" onclick="event.preventDefault(); document.getElementById('wishlistDeleteForm').submit()"><i class="ti-close"></i></a>
                                            <form action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST" id="wishlistDeleteForm">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="card card-body">
                                        <h4 class="text-center text-muted">You didn't create any wishlist.</h4>
                                    </div>
                                @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->
    </div>
    <!-- END MAIN CONTENT -->
@endsection

@push('scripts')
    <script>
        function addToCartFromWishlist( slug ) {
            $.ajax({
                method: 'POST',
                url: '/cart-add-via-ajax',
                data: {
                    slug: slug,
                    qty: 1,
                    _token: '{{ csrf_token() }}'
                },
                success: (data) => {
                    if(data.success) {
                        document.querySelectorAll(`[data-slug="${slug}"]`).forEach(btn => {
                            btn.setAttribute('disabled', true);
                            btn.innerHTML = 'Already in cart'
                            btn.style.pointerEvents = 'none';
                        });
                        toastr.success(data.success);
                    }
                    if(data.warning) {
                        toastr.warning(data.warning);
                    }
                },
                error: (error) => {
                    console.error(error)
                }
            });
        }
    </script>
@endpush
