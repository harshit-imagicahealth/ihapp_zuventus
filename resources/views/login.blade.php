<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ env('APP_NAME') }}</title>

        <link rel="stylesheet" href="{{ asset('public/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css?v=') . time() }}">

    </head>

    <body>

        <div class="login_bg">
            <div class="container">
                <div class="card login-card mx-auto border-0">
                    <div class="card-body p-md-5 p-4">
                        <div class="text-center">
                            <img class="logo-img img-fluid" src="{{ asset('public/images/logo.png') }}"
                                alt="INSPIRE Logo">
                        </div>
                        <form method="POST" action="{{ route('loginuser') }}">
                            @csrf
                            <div class="input-wrap">
                                <i class="fa-solid fa-user"></i>
                                <input type="employee_code" id="employee_code" name="employee_code"
                                    placeholder="Employee Code" class="form-control">
                            </div>

                            <div class="input-wrap">
                                <i class="fa-solid fa-lock"></i>

                                <input type="password" id="password" name="password" placeholder="Password"
                                    class="form-control" style="padding-right: 40px;">

                                <!-- Eye Icon -->
                                <i id="togglePassword" class="fa-solid fa-eye toggle-password">
                                </i>
                            </div>

                            <button type="submit" class="btn btn-inspire w-100 mt-2">
                                LOGIN
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/js/sweetalert2@11.js') }}"></script>
        <script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>
        <script>
            // toggle password script
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            // error message script
            let error = "{{ session('error') }}";
            if (error) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            }

            // submit form script
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
