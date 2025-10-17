@extends('website.layouts.master')

@section('title', 'Home')



@section('body')
    <!-- START SECTION BANNER -->
    <div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
        <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item background_bg active" data-img-src="{{asset('/')}}website/assets/images/banner16.jpg">
                    <div class="banner_slide_content banner_content_inner">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-10">
                                    <div class="banner_content overflow-hidden">
                                        <h2 class="staggered-animation" data-animation="slideInLeft" data-animation-delay="0.5s">LED 75 INCH F58</h2>
                                        <h5 class="mb-3 mb-sm-4 staggered-animation font-weight-light" data-animation="slideInLeft" data-animation-delay="1s">Get up to <span class="text_default">50%</span> off Today Only!</h5>
                                        <a class="btn btn-fill-out staggered-animation text-uppercase" href="shop-left-sidebar.html" data-animation="slideInLeft" data-animation-delay="1.5s">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item background_bg" data-img-src="{{asset('/')}}website/assets/images/banner17.jpg">
                    <div class="banner_slide_content banner_content_inner">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 col-10">
                                    <div class="banner_content overflow-hidden">
                                        <h2 class="staggered-animation" data-animation="slideInLeft" data-animation-delay="0.5s">Smart Phones</h2>
                                        <h5 class="mb-3 mb-sm-4 staggered-animation font-weight-light" data-animation="slideInLeft" data-animation-delay="1s"><span class="text_default">50%</span> off in all products</h5>
                                        <a class="btn btn-fill-out staggered-animation text-uppercase" href="shop-left-sidebar.html" data-animation="slideInLeft" data-animation-delay="1.5s">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item background_bg" data-img-src="{{asset('/')}}website/assets/images/banner18.jpg">
                    <div class="banner_slide_content banner_content_inner">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 col-10">
                                    <div class="banner_content overflow-hidden">
                                        <h2 class="staggered-animation" data-animation="slideInLeft" data-animation-delay="0.5s">Beat Headphone</h2>
                                        <h5 class="mb-3 mb-sm-4 staggered-animation font-weight-light" data-animation="slideInLeft" data-animation-delay="1s">Taking your Viewing Experience to Next Level</h5>
                                        <a class="btn btn-fill-out staggered-animation text-uppercase" href="shop-left-sidebar.html" data-animation="slideInLeft" data-animation-delay="1.5s">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev"><i class="ion-chevron-left"></i></a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next"><i class="ion-chevron-right"></i></a>
        </div>
    </div>
    <!-- END SECTION BANNER -->
    <!-- END MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION BANNER -->
        <div class="section pb_20 small_pt">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img src="{{asset('/')}}website/assets/images/shop_banner_img7.jpg" alt="shop_banner_img7">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img src="{{asset('/')}}website/assets/images/shop_banner_img8.jpg" alt="shop_banner_img8">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img src="{{asset('/')}}website/assets/images/shop_banner_img9.jpg" alt="shop_banner_img9">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION BANNER -->

        <!-- START SECTION CATEGORIES -->
        <div class="section small_pb small_pt">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="heading_s4 text-center">
                            <h2>Top Categories</h2>
                        </div>
                        <p class="text-center leads">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim Nullam nunc varius.</p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="cat_slider cat_style1 mt-4 mt-md-0 carousel_slider owl-carousel owl-theme nav_style5" data-loop="true" data-dots="false" data-nav="true" data-margin="30" data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "576":{"items": "4"}, "768":{"items": "5"}, "991":{"items": "6"}, "1199":{"items": "7"}}'>
                            @foreach($globalCategories as $category)
                                <div class="item">
                                    <div class="categories_box">
                                        <a href="{{ route('category.products', $category->slug) }}">
                                            <img src="{{ $category->image }}" height="120" width="80" class="rounded-2" alt="cat_img1"/>
                                            <span>{{ $category->name }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION CATEGORIES -->

        <!-- START SECTION SHOP -->
        <div class="section small_pb small_pt">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="heading_s1 text-center">
                            <h2>Exclusive Products</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($trendingProducts->take(12) as $product)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="product">
                                <div class="">
                                    <a href="{{ route('product.detail', $product->slug) }}">
                                        <img src="{{ $product->main_image }}" height="220" class="rounded-2 w-100" alt="el_img1">
                                    </a>
                                    <div class="mt-2">
                                        <ul class="list_none pr_action_btn">
                                            <li class="add-to-cart">
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
                                                    onclick="addToWishlist('{{ $product->slug }}')"
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
                    @endforeach
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION BANNER -->
        <div class="section pb_20 small_pt">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img src="{{asset('/')}}website/assets/images/shop_banner_img11.png" alt="shop_banner_img11">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION BANNER -->

        <!-- START SECTION SHOP -->
        <div class="section small_pt">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="heading_s1 text-center">
                            <h2>Trending Products</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($trendingProducts->take(12) as $product)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="product">
                                <div class="">
                                    <a href="{{ route('product.detail', $product->slug) }}">
                                        <img src="{{ $product->main_image }}" height="220" class="rounded-2 w-100" alt="el_img1">
                                    </a>
                                    <div class="mt-2">
                                        <ul class="list_none pr_action_btn">
                                            <li class="add-to-cart">
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
                                                    onclick="event.preventDefault(); addToWishlist('{{ $product->slug }}')"
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
                    @endforeach
                </div>
            </div>
        </div>

        <!-- END SECTION SHOP -->

        <!-- START SECTION TESTIMONIAL -->
        <div class="section bg_redon">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="heading_s1 text-center">
                            <h2>Our Client Say!</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="testimonial_wrap testimonial_style1 carousel_slider owl-carousel owl-theme nav_style2" data-nav="true" data-dots="false" data-center="true" data-loop="true" data-autoplay="true" data-items='1'>
                            <div class="testimonial_box">
                                <div class="testimonial_desc">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem.</p>
                                </div>
                                <div class="author_wrap">
                                    <div class="author_img">
                                        <img src="{{asset('/')}}website/assets/images/user_img1.jpg" alt="user_img1" />
                                    </div>
                                    <div class="author_name">
                                        <h6>Lissa Castro</h6>
                                        <span>Designer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial_box">
                                <div class="testimonial_desc">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem.</p>
                                </div>
                                <div class="author_wrap">
                                    <div class="author_img">
                                        <img src="{{asset('/')}}website/assets/images/user_img2.jpg" alt="user_img2" />
                                    </div>
                                    <div class="author_name">
                                        <h6>Alden Smith</h6>
                                        <span>Designer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial_box">
                                <div class="testimonial_desc">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem.</p>
                                </div>
                                <div class="author_wrap">
                                    <div class="author_img">
                                        <img src="{{asset('/')}}website/assets/images/user_img3.jpg" alt="user_img3" />
                                    </div>
                                    <div class="author_name">
                                        <h6>Daisy Lana</h6>
                                        <span>Designer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial_box">
                                <div class="testimonial_desc">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem.</p>
                                </div>
                                <div class="author_wrap">
                                    <div class="author_img">
                                        <img src="{{asset('/')}}website/assets/images/user_img4.jpg" alt="user_img4" />
                                    </div>
                                    <div class="author_name">
                                        <h6>John Becker</h6>
                                        <span>Designer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION TESTIMONIAL -->

        <!-- START SECTION CLIENT LOGO -->
        <div class="section small_pt">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="client_logo carousel_slider owl-carousel owl-theme" data-dots="false" data-margin="30" data-loop="true" data-autoplay="true" data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "5"}}'>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo1.png" alt="cl_logo"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo2.png" alt="cl_logo"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo3.png" alt="cl_logo"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo4.png" alt="cl_logo"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo5.png" alt="cl_logo"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo6.png" alt="cl_logo"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img src="{{asset('/')}}website/assets/images/cl_logo7.png" alt="cl_logo"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION CLIENT LOGO -->
    </div>
    <!-- END MAIN CONTENT -->
@endsection

