<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ env('APP_NAME') }}</title>

        <link rel="stylesheet" href="{{ asset('public/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/cropper.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">

        <style>
            * {
                margin: 0px;
                padding: 0px;
                box-sizing: border-box;
            }

            a {
                font-family: 'Outfit';
                font-size: 14px;
                line-height: 1.7;
                color: #666666;
                margin: 0px;
                transition: all 0.4s;
                -webkit-transition: all 0.4s;
                -o-transition: all 0.4s;
                -moz-transition: all 0.4s;
            }

            .tab-card img {
                border-radius: 35px !important;
            }

            /* MOBILE ONLY */
            @media (max-width: 767px) {
                /* Hide image on mobile */
                /* .tab-img {
                    display: none !important;
                } */
                /* Card styling */
                /* .tab-card {
                    background: #ffffff;
                    padding: 22px 10px;
                    border-radius: 16px;
                    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.12);
                    transition: all 0.3s ease;
                }

                .tab-card:active {
                    transform: scale(0.96);
                } */
                /* ICON CONTAINER */
                /* .tab-card .tab-icon {
                    font-size: 26px;
                    color: #ffffff;
                    width: 56px;
                    height: 56px;
                    line-height: 56px;
                    border-radius: 50%;
                    display: inline-block;
                    background: linear-gradient(135deg, #0d6efd, #3b82f6);
                    box-shadow: 0 6px 16px rgba(13, 110, 253, 0.45);
                    transition: all 0.3s ease;
                } */
                /* Icon hover */
                /* .tab-card:hover .tab-icon {
                    transform: rotate(-3deg) scale(1.05);
                    box-shadow: 0 10px 22px rgba(13, 110, 253, 0.6);
                } */
                /* Title */
                /* .tab-title {
                    font-size: 14px;
                    font-weight: 600;
                    color: #222;
                    margin-top: 10px;
                    line-height: 1.2;
                } */
            }
        </style>
    </head>

    <body>
        <div class="main_loader">
            <span class="loader"></span>
        </div>

        <div class="container px-4 pt-4">
            <nav class="navbar navbar-expand-lg bg-body-tertiary px-4">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><img class="logo"
                            src="{{ asset('public/images/logo.png') }}" alt="logo" width="150"></a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="navbarSupportedContent" class="navbar-collapse justify-content-end collapse">
                        <ul class="navbar-nav mb-lg-0 mb-2">
                            {{-- <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li> --}}
                            {{-- 
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('create') ? 'active' : '' }}"
                                    href="{{ route('create') }}">
                                    Start
                                </a>
                            </li> --}}

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('logout') ? 'active' : '' }}"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container mt-5">

            <div class="row">
                <div class="col-md-12 col-12 mb-4">
                    <a class="text-decoration-none" href="{{ route('dr.journey.dashboard') }}">
                        <div class="tab-card text-center">

                            <img class="border-redius img-fluid mb-2 shadow-lg"
                                src="{{ asset('public/images/buttons/Btn2.png') }}" width="70%">

                        </div>
                    </a>
                </div>
            </div>

        </div>
        <script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/js/sweetalert2@11.js') }}"></script>
        <script src="{{ asset('public/js/flatpickr.js') }}"></script>
        <script src="{{ asset('public/js/cropper.min.js') }}"></script>
        <script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>

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
