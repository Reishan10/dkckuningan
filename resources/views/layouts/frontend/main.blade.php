<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>@yield('title') - DKC KUNINGAN</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets_frontend') }}/images/dkc_kuningan.png" />

    <!-- Css -->
    <link href="{{ asset('assets_frontend') }}/libs/tiny-slider/tiny-slider.css" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets_frontend') }}/css/bootstrap.css" class="theme-opt" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets_frontend') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets_frontend') }}/libs/@iconscout/unicons/css/line.css" type="text/css" rel="stylesheet" />
    <!-- Style Css-->
    <link href="{{ asset('assets_frontend') }}/css/style.min.css" class="theme-opt" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>

<body>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>
    </div>
    <!-- Loader -->


    @include('layouts.frontend.header')

    @yield('content')

    <!-- Footer Start -->
    <footer class="footer footer-bar">
        <div class="footer-py-30">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="text-sm-start">
                            <p class="mb-0">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Dewan Kerja Cabang <i class="mdi mdi-heart text-danger"></i>
                                by <a href="https://shreethemes.in/" target="_blank" class="text-reset">Kuningan</a>.
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                        <ul class="list-unstyled social-icon foot-social-icon text-sm-end mb-0">
                            <li class="list-inline-item mb-0"><a href="javascript:void(0)" class="rounded"><i
                                        data-feather="facebook" class="fea icon-sm fea-social"></i></a></li>
                            <li class="list-inline-item mb-0"><a href="javascript:void(0)" class="rounded"><i
                                        data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                            <li class="list-inline-item mb-0"><a href="javascript:void(0)" class="rounded"><i
                                        data-feather="twitter" class="fea icon-sm fea-social"></i></a></li>
                            <li class="list-inline-item mb-0"><a href="javascript:void(0)" class="rounded"><i
                                        data-feather="linkedin" class="fea icon-sm fea-social"></i></a></li>
                        </ul><!--end icon-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </div>
    </footer><!--end footer-->
    <!-- Footer End -->

    <!-- Javascript -->
    <script src="{{ asset('assets_frontend') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets_frontend') }}/libs/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets_frontend') }}/libs/tiny-slider/min/tiny-slider.js"></script>
    <script src="{{ asset('assets_frontend') }}/js/plugins.init.js"></script>
    <script src="{{ asset('assets_frontend') }}/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
