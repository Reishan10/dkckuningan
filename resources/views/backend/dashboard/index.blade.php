@extends('layouts.backend.main')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Selamat Datang {{ auth()->user()->name }}!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Students</h6>
                                    <h3>50055</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets') }}/img/icons/dash-icon-01.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Awards</h6>
                                    <h3>50+</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets') }}/img/icons/dash-icon-02.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Department</h6>
                                    <h3>30+</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets') }}/img/icons/dash-icon-03.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Revenue</h6>
                                    <h3>$505</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets') }}/img/icons/dash-icon-04.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h5 class="card-title">Pendaftar Tahun Ini</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="golongan"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h5 class="card-title">Peserta Laki-laki dan Perempuan</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="gender"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h5 class="card-title">Pendaftar Tiap Tahun</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="pendaftar_year"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h5 class="card-title">Pendaftar Golongan</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="pendaftar_year_golongan"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var colors = ['#ff5722', '#009688', '#e91e63', '#9c27b0', '#ffc107', '#03a9f4', '#8bc34a', '#607d8b'];

        var options = {
            series: [{
                data: [{{ $siaga }}, {{ $penggalang }}, {{ $penegak }}, {{ $pandega }}]
            }],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
                }
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: ['Siaga', 'Penggalang', 'Penegak', 'Pandega'],
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return Math.floor(value);
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#golongan"), options);
        chart.render();

        var donutChart = {
            chart: {
                height: 350,
                type: 'donut',
                toolbar: {
                    show: false,
                }
            },
            labels: ['Laki-laki', 'Perempuan'],
            series: [{{ $laki_laki }}, {{ $perempuan }}],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        }
        var donut = new ApexCharts(document.querySelector("#gender"), donutChart);
        donut.render();

        var options = {
            series: [],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
                }
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: [],
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return Math.floor(value);
                    }
                }
            }
        };

        var chartData = @json($data_pendaftar);

        var years = [...new Set(chartData.map(item => item.year))];

        var seriesData = {};

        chartData.forEach(function(item) {
            var year = item.year;
            var golongan = item.golongan_name;
            var total = item.total;

            if (!seriesData[golongan]) {
                seriesData[golongan] = {
                    name: golongan,
                    data: []
                };
            }

            seriesData[golongan].data[years.indexOf(year)] = total;
        });

        options.xaxis.categories = years;

        options.series = Object.values(seriesData);

        var chart = new ApexCharts(document.querySelector("#pendaftar_year"), options);
        chart.render();

        var sCol = {
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false,
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [],

            xaxis: {
                categories: [],
            },
            yaxis: {
                title: {
                    text: '(count)'
                },
                labels: {
                    formatter: function(value) {
                        return Math.floor(value);
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return +val + " count";
                    }
                }
            }
        };

        var categories = [];
        var dataSeries = {};

        @foreach ($data as $item)
            var year = '{{ $item->year }}';
            var golongan = '{{ $item->golongan_name }}';
            var total = {{ $item->total }};

            if (!categories.includes(year)) {
                categories.push(year);
            }

            if (!dataSeries[golongan]) {
                dataSeries[golongan] = {
                    name: golongan,
                    data: []
                };
            }

            dataSeries[golongan].data[categories.indexOf(year)] = total;
        @endforeach

        sCol.xaxis.categories = categories;
        sCol.series = Object.values(dataSeries);

        var chart = new ApexCharts(document.querySelector("#pendaftar_year_golongan"), sCol);
        chart.render();
    </script>
@endsection
