@extends('layouts.backend.main')
@section('title', 'Riwayat Pendaftar')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="col-md-4">
                            <button id="filter" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">@yield('title')</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('pendaftaran.semua.print') }}" target="_blank"
                                            class="btn btn-primary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="{{ route('pendaftaran.semua.printPDF') }}" target="_blank"
                                            class="btn btn-primary">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table border-0 table-hover table-center mb-0 table-striped" id="datatable">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>No</th>
                                            <th>NTA</th>
                                            <th>Nama</th>
                                            <th>No Telepon</th>
                                            <th>Golongan</th>
                                            <th>Pangkalan</th>
                                            <th>Tahap 1</th>
                                            <th>Tahap 2</th>
                                            <th>Berkas</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('riwayat.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nta',
                            name: 'nta'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'no_telepon',
                            name: 'no_telepon'
                        },
                        {
                            data: 'golongan',
                            name: 'golongan'
                        },
                        {
                            data: 'pangkalan',
                            name: 'pangkalan'
                        },
                        {
                            data: 'status_1',
                            name: 'status_1',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status_2',
                            name: 'status_2',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'berkas',
                            name: 'berkas',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#filter').click(function() {
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();

                    table.destroy();
                    table = $('#datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('riwayat.index') }}",
                            data: {
                                start_date: start_date,
                                end_date: end_date
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'nta',
                                name: 'nta'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'no_telepon',
                                name: 'no_telepon'
                            },
                            {
                                data: 'golongan',
                                name: 'golongan'
                            },
                            {
                                data: 'pangkalan',
                                name: 'pangkalan'
                            },
                            {
                                data: 'status_1',
                                name: 'status_1',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'status_2',
                                name: 'status_2',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'berkas',
                                name: 'berkas',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });
                });
            })
        </script>
    @endsection
