<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{asset('/')}}admin/assets/images/favicon.ico" />

<!-- Bootstrap Css -->
<link href="{{asset('/')}}admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{asset('/')}}admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{asset('/')}}admin/assets/css/app.min.css"  rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{asset('/')}}admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/')}}admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="{{asset('/')}}admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/')}}admin/assets/css/tailwindcss-tag-input.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/')}}admin/assets/css/multi-checkbox-select.css" rel="stylesheet" type="text/css" />

<!-- App js -->
<script src="{{asset('/')}}admin/assets/js/plugin.js"></script>

<style>
    /* Customize toastr success */
    .toast-success {
        background-color: #556ee6 !important; /* Bootstrap green */
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

    .custom-border{
        border-color: #cad1d9 !important;
    }
    .bg-custom-checkbox {
        background-color: #edf3fb;
    }
</style>

@stack('links')

