
<!-- JAVASCRIPT -->
<script src="https://cdn.lookmartbd.com/assets/libs/jquery/jquery.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/libs/simplebar/simplebar.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/libs/node-waves/waves.min.js"></script>
<!-- apexcharts -->
<script src="https://cdn.lookmartbd.com/assets/libs/apexcharts/apexcharts.min.js"></script>
<!-- dashboard init -->
<script src="https://cdn.lookmartbd.com/assets/js/pages/dashboard.init.js"></script>
<!-- App js -->
<script src="https://cdn.lookmartbd.com/assets/js/app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

<!-- Required datatable js -->
<script src="https://cdn.lookmartbd.com/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Datatable init js -->
<script src="https://cdn.lookmartbd.com/assets/js/pages/datatables.init.js"></script>

<!-- Responsive examples -->
<script src="https://cdn.lookmartbd.com/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!-- Buttons examples -->
<script src="https://cdn.lookmartbd.com/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.lookmartbd.com/assets/js/tailwindcss-tag-input.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- form advanced init -->
<script src="https://cdn.lookmartbd.com/assets/js/pages/form-advanced.init.js"></script>

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
</script>

<script>
    function confirmDelete(event, formId) {
        if (confirm('Are you sure to delete this one?')) {
            event.preventDefault();
            document.getElementById(formId).submit();
        }
    }
</script>

@stack('scripts')
