@extends('website.layouts.master')

@section('title', $title ?? 'Products Page')

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
                                <div class="col-xm-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                                    <div class="product">
                                        <div class="">
                                            <a href="{{ route('product.detail', $product->slug) }}">
                                                <img src="{{ $product->main_image }}" height="220" class="rounded-2 w-100" alt="el_img1">
                                            </a>
                                            <div class="mt-2">
                                                <ul class="list_none pr_action_btn">
                                                    <li class="add-to-cart" >
                                                        <a
                                                            href="javascript:void(0)"
                                                            class="{{ isProductInCart($product->id) ? 'action-complete-style': '' }}"
                                                            onclick="event.preventDefault(); addToCart('{{ $product->slug }}')"
                                                            data-slug="{{ $product->slug }}"
                                                        >
                                                            <i class="icon-basket-loaded"></i>
                                                            Add To Cart
                                                        </a>
                                                    </li>
                                                    <li><a href="shop-quick-view.html" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                                                    <li>
                                                        <a
                                                            href="javascript:void(0)"
                                                            onclick="event.preventDefault(); addToWishlist('{{$product->slug }}')"
                                                            data-slug="wishlist-{{ $product->slug }}"
                                                            class="{{ isWishlist($product->id) ? 'action-complete-style': '' }}"
                                                        ><i class="icon-heart"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product_info">
                                            <h6 class="product_title"><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h6>
                                            <div class="product_price">
                                                <span class="price">Tk.{{ $product->selling_price }}</span>
                                                <del>Tk.{{ $product->regular_price }}</del>
                                                @if($product->discount)
                                                    <div class="on_sale bg-danger p-2 absolute top-2 left-5">
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
                                                <a href="{{ route('direct.checkout', $product->slug) }}" class="btn btn-sm btn-dark ms-0">Order Now</a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            @empty
                                <h1 class="text-center text-secondary fs-4">Doesn't have any product</h1>
                            @endforelse
                        </div>
                            <div class="row">
                            <div class="col-12">
                                {{ $products->links('website.vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                        <div class="sidebar">
                            <div class="widget">
                                <h5 class="widget_title">Categories</h5>
                                <ul class="widget_categories">
{{--                                    @foreach($categories as $category)--}}
{{--                                        <li><a href="{{ route('category.products', $category->slug) }}"><span class="categories_name">{{ $category->name }}</span><span class="categories_num">({{ count($category['products']) }})</span></a></li>--}}
{{--                                    @endforeach--}}
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

