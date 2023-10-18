<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>@yield('title') - DKC Kuningan</title>
    <link rel="shortcut icon" href="{{ asset('assets') }}/img/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>

<body>

    <div class="main-wrapper">

        @include('layouts.backend.header')
        @include('layouts.backend.sidebar')

        @yield('content')
    </div>

    <script src="{{ asset('assets') }}/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/js/feather.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/apexcharts.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/chart-data.js"></script>
    <script src="{{ asset('assets') }}/js/script.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
