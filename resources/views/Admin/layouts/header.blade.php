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
        <link rel="stylesheet" href="{{ asset('public/css/admin.css') }}">

    </head>

    <body>

        <nav class="sidebar">
            <div class="sidebar-header d-flex justify-content-center">
                <img src="{{ asset('public/images/logo.png') }}" alt="" width="80">
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a class="{{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a class="{{ in_array(Route::currentRouteName(), ['admin.userlist', 'admin.user.add', 'admin.user.edit']) ? 'active' : '' }}"
                        href="{{ route('admin.userlist') }}">
                        <i class="fas fa-users"></i>
                        <span>Employee</span>
                    </a>
                </li>


                <li>
                    <a class="{{ in_array(Route::currentRouteName(), ['admin.dr.journey.request.list', 'admin.dr.journey.request.invite', 'admin.dr.journey.request.execute', 'admin.dr.journey.request.edit']) ? 'active' : '' }}"
                        href="{{ route('admin.dr.journey.request.list') }}">
                        <i class="fa-regular fa-headphones"></i>
                        <span>Dr. Journey Frame Request</span>
                    </a>
                </li>

                <li>
                    <a class="{{ in_array(Route::currentRouteName(), ['admin.logout']) ? 'active' : '' }}"
                        href="{{ route('admin.logout') }}">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>

        </nav>

        <div class="main-content">
            <header class="header">
                <div class="header-left">
                    <button id="sidebarToggle" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h3>@stack('title')</h3>
                </div>

                <div class="header-right">
                    <div class="user-info">
                        <span>Admin User</span>
                    </div>
                </div>
            </header>
