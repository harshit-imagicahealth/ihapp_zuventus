<script src="{{ asset('public/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/sweetalert2@11.js') }}"></script>
<script src="{{ asset('public/js/flatpickr.js') }}"></script>
<script src="{{ asset('public/js/cropper.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>

@stack('script')

<script>
    $('.main_loader').show();
    $(document).ready(function() {
        setTimeout(function() {
            $('.main_loader').hide();
        }, 1000);
    });
</script>

</body>

</html>
