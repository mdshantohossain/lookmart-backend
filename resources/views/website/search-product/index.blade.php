@extends('website.layouts.master')

@section('title', $query)

@section('body')
    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row align-items-center mb-4 pb-1">
                            <div class="col-12">
                                <div class="product_header">
                                    <div class="product_header_left">
                                        <div class="custom_select">
                                            <select class="form-control form-control-sm">
                                                <option value="order">Default sorting</option>
                                                <option value="popularity">Sort by popularity</option>
                                                <option value="date">Sort by newness</option>
                                                <option value="price">Sort by price: low to high</option>
                                                <option value="price-desc">Sort by price: high to low</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row shop_container">
                            @forelse($products as $product)
                                <div class="col-md-4 col-6 ">
                                    <div class="product">
                                        <div class="">
                                            <a href="{{ route('product-page.detail', $product->id) }}">
                                                <img src="{{ $product->main_image }}" height="220" class="rounded-2" width="100%" alt="product_img1" />
                                            </a>
                                            {{--                                        <div class="product_action_box">--}}
                                            {{--                                            <ul class="list_none pr_action_btn">--}}
                                            {{--                                                <li class="add-to-cart"><a href="#"><i class="icon-basket-loaded"></i> Add To Cart</a></li>--}}
                                            {{--                                                <li><a href="shop-compare.html" class="popup-ajax"><i class="icon-shuffle"></i></a></li>--}}
                                            {{--                                                <li><a href="shop-quick-view.html" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>--}}
                                            {{--                                                <li><a href="#"><i class="icon-heart"></i></a></li>--}}
                                            {{--                                            </ul>--}}
                                            {{--                                        </div>--}}
                                        </div>
                                        <div class="product_info">
                                            <h6 class="product_title"><a href="{{ route('product-page.detail', $product->id) }}">{{ $product->name }}</a></h6>
                                            <div class="product_price">
                                                <span class="price">Tk.{{ $product->selling_price }}</span>
                                                <del>Tk.{{ $product->regular_price }}</del>
                                                @if($product->discount)
                                                    <div class="on_sale">
                                                        <span>{{ $product->discount }} Off</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="rating_wrap">
                                                <div class="rating">
                                                    <div class="product_rate" style="width:80%"></div>
                                                </div>
                                                <span class="rating_num">(21)</span>
                                            </div>
                                            <div class="row flex-column mt-2">
                                                <button
                                                    @if(isProductInCart($product->id))
                                                        disabled
                                                    @endif
                                                    type="button"
                                                    class="d-block btn btn-sm btn-pink mb-1 text-white"
                                                    style="background: #FF324D"
                                                    onclick="addToCart({{ $product->id }})"
                                                    id="cartAddBtn{{$product->id}}"
                                                >{{ isProductInCart($product->id) ? 'Added in cart' : 'Add to cart' }}</button>

                                                <a href="{{ route('direct.checkout', $product->id) }}" class=" btn btn-sm btn-dark ms-0">Order Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <h1 class="text-center text-secondary fs-4">Doesn't have any product</h1>
                            @endforelse
                        </div>
                        @if(count($products) !== 0)
                            <div class="row">
                                <div class="col-12">
                                    <ul class="pagination mt-3 justify-content-center pagination_style1">
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#"><i class="linearicons-arrow-right"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                        <div class="sidebar">
                            <div class="widget">
                                <h5 class="widget_title">Categories</h5>
                                <ul class="widget_categories">
                                    @foreach($globalCategories->take(5) as $category)
                                        <li><a href="{{ route('category.product-page', $category->id) }}"><span class="categories_name">{{ $category->name }}</span><span class="categories_num">({{ count($category->products) }})</span></a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="widget">
                                <h5 class="widget_title">Brand</h5>
                                <ul class="list_brand">
                                    <li>
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox" id="Arrivals" value="">
                                            <label class="form-check-label" for="Arrivals"><span>New Arrivals</span></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox" id="Lighting" value="">
                                            <label class="form-check-label" for="Lighting"><span>Lighting</span></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox" id="Tables" value="">
                                            <label class="form-check-label" for="Tables"><span>Tables</span></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox" id="Chairs" value="">
                                            <label class="form-check-label" for="Chairs"><span>Chairs</span></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox" id="Accessories" value="">
                                            <label class="form-check-label" for="Accessories"><span>Accessories</span></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            {{--                            <div class="widget">--}}
                            {{--                                <div class="shop_banner">--}}
                            {{--                                    <div class="banner_img overlay_bg_20">--}}
                            {{--                                        <img src="assets/images/sidebar_banner_img.jpg" alt="sidebar_banner_img">--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="shop_bn_content2 text_white">--}}
                            {{--                                        <h5 class="text-uppercase shop_subtitle">New Collection</h5>--}}
                            {{--                                        <h3 class="text-uppercase shop_title">Sale 30% Off</h3>--}}
                            {{--                                        <a href="#" class="btn btn-white rounded-0 btn-sm text-uppercase">Shop Now</a>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
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

        document.querySelector('#search').value = "{{ $query }}";

        function addToCart( productId) {

            $.ajax({
                method: 'POST',
                url: '/cart-add-via-ajax',
                data: {
                    product_id: productId,
                    qty: 1,
                    _token: '{{ csrf_token() }}'
                },
                success: (data) => {
                    if(data.success) {
                        const btn = document.querySelector('#cartAddBtn'+ productId);
                        btn.setAttribute('disabled', true);
                        btn.innerText = "Added To Cart";
                        toastr.success(data.success)
                    }
                    if(data.warning) {
                        toastr.warning(data.warning)
                    }
                },
                error: (error) => {
                    console.error(error)
                }
            });

        }
    </script>
@endpush
