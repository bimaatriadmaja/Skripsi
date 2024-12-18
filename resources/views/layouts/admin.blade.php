<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PG Dwi Putra</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="pg dwi putra" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo_title.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">
                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="{{ route('admin.index') }}" id="site-logo-inner">
                            <img class="" id="logo_header_1" alt=""
                                src="{{ asset('assets/images/logo.png') }}"
                                data-light="{{ asset('images/logo/logo.png') }}"
                                data-dark="{{ asset('images/logo/logo.png') }}">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Beranda Utama</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('admin.index') }}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Dashboard</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('admin.karyawan') }}" class="">
                                        <div class="icon"><i class="bi bi-person-lines-fill"></i></div>
                                        <div class="text">Data Karyawan</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.jenis-genteng.index') }}" class="">
                                        <div class="icon"><i class="bi bi-cash-stack"></i></div>
                                        <div class="text">Kalkulasi Gaji</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.laporan-keseluruhan.index') }}" class="">
                                        <div class="icon"><i class="bi bi-file-earmark"></i></div>
                                        <div class="text">Laporan Keseluruhan</div>
                                    </a>
                                </li>                                
                                <li class="menu-item">
                                    <a href="{{ route('admin.settings.index') }}" class="">
                                        <div class="icon"><i class="bi bi-person-fill"></i></div>
                                        <div class="text">Profil Saya</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a href="{{ route('logout') }}" class=""
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <div class="icon"><i class="icon-log-out"></i></div>
                                            <div class="text">Logout</div>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section-content-right">
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>
                            </div>
                            <div class="header-grid">
                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                                                    <span class="text-tiny">Admin</span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="{{ route('admin.settings.index') }}" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Akun saya</div>
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}"
                                                    id="logout-form-2">
                                                    @csrf
                                                    <a href="{{ route('logout') }}" class="user-item"
                                                        onclick="event.preventDefault();document.getElementById('logout-form-2').submit();">
                                                        <div class="icon">
                                                            <i class="icon-log-out"></i>
                                                        </div>
                                                        <div class="body-title-2">Log out</div>
                                                    </a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        @yield('content')
                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 PG Dwi Putra</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>