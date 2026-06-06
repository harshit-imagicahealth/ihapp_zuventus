<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ env('APP_NAME') }}</title>

        <link rel="stylesheet" href="{{ asset('public/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">

    </head>

    <body>

        <div class="login_bg">
            <div class="container">
                <div class="card login-card mx-auto border-0">
                    <div class="card-body p-md-5 p-4">
                        <div class="text-danger text-center">
                            <span>Link Will Be Live Soon.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/js/sweetalert2@11.js') }}"></script>
        <script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>

        <script>
            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            $(document).on('submit', 'form', function(e) {
                let employee = $('#employee_code').val().trim();
                let password = $('#password').val().trim();

                if (employee === '') {
                    e.preventDefault();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Please enter Employee Code',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return false;
                }

                if (password === '') {
                    e.preventDefault();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Please enter Password',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return false;
                }
            });
        </script>

    </body>

</html>
