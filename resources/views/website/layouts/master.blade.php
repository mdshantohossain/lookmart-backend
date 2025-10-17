<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="Anil z" name="author">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords" content="ecommerce, electronics store, Fashion store, furniture store, minimal, online store, retail, shopping, ecommerce store">
    <meta name="theme-color" content="#FF324D">
    @stack('meta')
    <!-- SITE TITLE -->
    <title> @yield('title') </title>

    @include('website.layouts.links')

</head>
<body>

<!-- LOADER -->
{{--<div class="preloader">--}}
{{--    <div class="lds-ellipsis">--}}
{{--        <span></span>--}}
{{--        <span></span>--}}
{{--        <span></span>--}}
{{--    </div>--}}
{{--</div>--}}
<!-- END LOADER -->

{{--<!-- Home Popup Section -->--}}
{{--<div class="modal fade subscribe_popup" id="onload-popup" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-body">--}}
{{--                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>--}}
{{--                </button>--}}
{{--                <div class="row g-0">--}}
{{--                    <div class="col-sm-7">--}}
{{--                        <div class="popup_content  text-start">--}}
{{--                            <div class="popup-text">--}}
{{--                                <div class="heading_s1">--}}
{{--                                    <h3>Subscribe Newsletter and Get 25% Discount!</h3>--}}
{{--                                </div>--}}
{{--                                <p>Subscribe to the newsletter to receive updates about new product-page.</p>--}}
{{--                            </div>--}}
{{--                            <form method="post">--}}
{{--                                <div class="form-group mb-3">--}}
{{--                                    <input name="email" required type="email" class="form-control" placeholder="Enter Your Email">--}}
{{--                                </div>--}}
{{--                                <div class="form-group mb-3">--}}
{{--                                    <button class="btn btn-fill-out btn-block text-uppercase" title="Subscribe" type="submit">Subscribe</button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                            <div class="chek-form">--}}
{{--                                <div class="custome-checkbox">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox3" value="">--}}
{{--                                    <label class="form-check-label" for="exampleCheckbox3"><span>Don't show this popup again!</span></label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-5">--}}
{{--                        <div class="background_bg h-100" data-img-src="{{asset('/')}}website/assets/images/popup_img3.jpg"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<!-- End Screen Load Popup Section -->--}}

@include('website.layouts.header')




@yield('body')

@include('website.layouts.footer')

<a href="#" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a>


@include('website.layouts.scripts')

</body>
</html>
