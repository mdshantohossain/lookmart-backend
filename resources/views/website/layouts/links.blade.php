<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="{{ $app?->favicon ?? asset('/website/assets/images/favicon.png')}}">
<!-- Animation CSS -->
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/animate.css">
<!-- Latest Bootstrap min CSS -->
<link rel="stylesheet" href="{{asset('/')}}website/assets/bootstrap/css/bootstrap.min.css">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
<!-- Icon Font CSS -->
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/all.min.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/ionicons.min.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/themify-icons.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/linearicons.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/flaticon.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/simple-line-icons.css">
<!--- owl carousel CSS-->
<link rel="stylesheet" href="{{asset('/')}}website/assets/owlcarousel/css/owl.carousel.min.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/owlcarousel/css/owl.theme.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/owlcarousel/css/owl.theme.default.min.css">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/magnific-popup.css">
<!-- Slick CSS -->
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/slick.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/slick-theme.css">
<!-- Style CSS -->
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/style.css">
<link rel="stylesheet" href="{{asset('/')}}website/assets/css/responsive.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Customize toastr success */
    .toast-success {
        background-color: #FF324D !important; /* Bootstrap green */
        font-size: 15px;
    }

    /* Customize toastr error */
    .toast-error {
        background-color: #dc3545 !important; /* Bootstrap red */
        font-size: 15px;
    }

    /* Add some border radius and shadow */
    .toast {
        border-radius: 6px !important;
        border: 0 !important;
    }
    .star_rating i {
        font-size: 14px; /* or 12px, 10px, etc. */
    }
    @stack('links')
</style>

@vite('resources/js/app.js')

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-106310707-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-106310707-1', { 'anonymize_ip': true });
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G6MPNF0KNC"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-G6MPNF0KNC');
</script>


<!-- Hotjar Tracking Code for bestwebcreator.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2073024,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<!-- Start of StatCounter Code -->
<script>

    var sc_project=11921154;
    var sc_security="6c07f98b";
    var scJsHost = (("https:" == document.location.protocol) ?
        "https://secure." : "http://www.");


    document.write("<sc"+"ript src='" +scJsHost +"statcounter.com/counter/counter.js'></"+"script>");
</script>
<!-- End of StatCounter Code -->
