
<!-- Latest jQuery -->
<script src="{{asset('/')}}website/assets/js/jquery-3.7.0.min.js"></script>
<!-- popper min js -->
<script src="{{asset('/')}}website/assets/js/popper.min.js"></script>
<!-- Latest compiled and minified Bootstrap -->
<script src="{{asset('/')}}website/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- owl-carousel min js  -->
<script src="{{asset('/')}}website/assets/owlcarousel/js/owl.carousel.min.js"></script>
<!-- magnific-popup min js  -->
<script src="{{asset('/')}}website/assets/js/magnific-popup.min.js"></script>
<!-- waypoints min js  -->
<script src="{{asset('/')}}website/assets/js/waypoints.min.js"></script>
<!-- parallax js  -->
<script src="{{asset('/')}}website/assets/js/parallax.js"></script>
<!-- countdown js  -->
<script src="{{asset('/')}}website/assets/js/jquery.countdown.min.js"></script>
<!-- imagesloaded js -->
<script src="{{asset('/')}}website/assets/js/imagesloaded.pkgd.min.js"></script>
<!-- isotope min js -->
<script src="{{asset('/')}}website/assets/js/isotope.min.js"></script>
<!-- jquery.dd.min js -->
<script src="{{asset('/')}}website/assets/js/jquery.dd.min.js"></script>
<!-- slick js -->
<script src="{{asset('/')}}website/assets/js/slick.min.js"></script>
<!-- elevatezoom js -->
<script src="{{asset('/')}}website/assets/js/jquery.elevatezoom.js"></script>
<!-- scripts js -->
<script src="{{asset('/')}}website/assets/js/scripts.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    toastr.options = {
        // "closeButton": true,
        "progressBar": false,
        "positionClass": "toast-bottom-left",
        "timeOut": "3000",
    };
    @if(session('success'))
    toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
    toastr.error(@json(session('error')));
    @endif
    @if(session('warning'))
    toastr.warning(@json(session('warning')));
    @endif
</script>

<script>
    function addToCart( slug ) {
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
                        btn.classList.add('action-complete-style');
                        btn.setAttribute('disabled', true);
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

    function addToWishlist( slug ) {
        const userId = @json(auth()->id());
        if(userId) {
            $.ajax({
                method: 'POST',
                url: '{{ route('wishlist.store') }}',
                data: {
                    slug: slug,
                    _token: '{{ csrf_token() }}'
                },
                success: (data) => {

                    if(data.success) {
                        let wishlistCount = document.querySelector('#wishlist');
                        wishlistCount.textContent = Number(wishlistCount.textContent) + 1;

                        document.querySelectorAll(`[data-slug="wishlist-${slug}"]`)
                            .forEach(btn => {
                                btn.classList.add('action-complete-style');
                                btn.style.pointerEvents = 'none';
                                btn.setAttribute('disabled', true);
                            });
                        toastr.success(data.success);
                    }
                    if(data.warning) {
                        toastr.warning(data.warning)
                    }
                },
                error: (error) => {
                    console.error(error)
                }
            });
        } else {
            window.location = @json(url('/login'))
        }
    }
</script>


@stack('scripts')
