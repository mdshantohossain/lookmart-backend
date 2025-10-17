<!-- START FOOTER -->
<footer class="bg_gray">
    <div class="footer_top small_pt pb_20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="widget">
                        <div class="footer_logo">
                            <a href="javascript: void(0)">
                                <img
                                    src="{{ $app?->logo ?? asset('website/assets/images/logo_dark.png')}}"
                                    style="width: 160px; height: 80px; object-fit: fill; border-radius: 12px"
                                    alt="app logo"
                                />
                            </a>
                        </div>
                        <p class="mb-3">{{ $app?->description }}</p>
                        <ul class="contact_info">
                            <li>
                                <i class="ti-location-pin"></i>
                                <p>{{ $app?->address ?? '123 Street, Old Trafford, NewYork, USA' }}</p>
                            </li>
                            <li>
                                <i class="ti-email"></i>
                                <a href="javascript: void(0)">{{ $app?->email }}</a>
                            </li>
                            <li>
                                <i class="ti-mobile"></i>
                                <p>{{ $app?->phone }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="widget">
                        <h6 class="widget_title">My Account</h6>
                        <ul class="widget_links">
                            <li><a href="{{ route('user.profile') }}">My Account</a></li>
                            <li><a href="#">Discount</a></li>
                            <li><a href="#">Orders History</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="widget">
                        <h6 class="widget_title">Social</h6>
                        <ul class="social_icons">
                            <li><a href="#" class="sc_facebook"><i class="ion-social-facebook"></i></a></li>
                            <li><a href="#" class="sc_twitter"><i class="ion-social-twitter"></i></a></li>
                            <li><a href="#" class="sc_youtube"><i class="ion-social-youtube-outline"></i></a></li>
                            <li><a href="#" class="sc_instagram"><i class="ion-social-instagram-outline"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="middle_footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="shopping_info">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="icon_box icon_box_style2">
                                    <div class="icon">
                                        <i class="flaticon-shipped"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h5>Free Delivery</h5>
                                        <p>Phasellus blandit massa enim elit of passage varius nunc.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="icon_box icon_box_style2">
                                    <div class="icon">
                                        <i class="flaticon-money-back"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h5>30 Day Returns Guarantee</h5>
                                        <p>Phasellus blandit massa enim elit of passage varius nunc.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="icon_box icon_box_style2">
                                    <div class="icon">
                                        <i class="flaticon-support"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h5>27/4 Online Support</h5>
                                        <p>Phasellus blandit massa enim elit of passage varius nunc.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom_footer border-top-tran">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-center text-md-start mb-md-0">© 2020 All Rights Reserved by Bestwebcreator</p>
                </div>
                <div class="col-lg-6">
                    <ul class="footer_payment text-center text-md-end">
                        <li><a href="#"><img src="{{asset('/')}}website/assets/images/visa.png" alt="visa"></a></li>
                        <li><a href="#"><img src="{{asset('/')}}website/assets/images/discover.png" alt="discover"></a></li>
                        <li><a href="#"><img src="{{asset('/')}}website/assets/images/master_card.png" alt="master_card"></a></li>
                        <li><a href="#"><img src="{{asset('/')}}website/assets/images/paypal.png" alt="paypal"></a></li>
                        <li><a href="#"><img src="{{asset('/')}}website/assets/images/amarican_express.png" alt="amarican_express"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- END FOOTER -->
