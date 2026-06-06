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
        <link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css?v=') . time() }}">
        @stack('css')
    </head>

    <body>

        <div class="main_loader">
            <span class="loader"></span>
        </div>

        <div class="px-4 pt-4">
            <nav class="navbar navbar-expand-lg bg-body-tertiary px-4">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('dr.journey.dashboard') }}">
                        <img class="logo" src="{{ asset('public/images/logo.png') }}" alt="logo" width="125">
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="navbarSupportedContent" class="navbar-collapse justify-content-end collapse">
                        <ul class="navbar-nav mb-lg-0 mb-2">
                            {{-- @if (auth()->user()->employee_code == '600000') --}}
                            {{-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}">
                                Home
                            </a>
                        </li> --}}
                            {{-- @endif --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dr.journey.dashboard') ? 'active' : '' }}"
                                    href="{{ route('dr.journey.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            @if (auth()->user()->employee_code == '600000')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dr.journey.create') ? 'active' : '' }}"
                                        href="{{ route('dr.journey.create') }}">
                                        Start
                                    </a>
                                </li>
                            @endif

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
