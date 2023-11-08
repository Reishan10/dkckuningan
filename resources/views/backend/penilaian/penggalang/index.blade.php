@extends('layouts.backend.main')
@section('title', 'Penilaian Tahap Akhir')
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
                                        <a href="{{ route('penilaian.penggalang.print') }}" target="_blank"
                                            class="btn btn-primary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="{{ route('penilaian.penggalang.printPDF') }}" target="_blank"
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
                                            <th>Email</th>
                                            <th>No Telepon</th>
                                            <th>Golongan</th>
                                            <th>Pangkalan</th>
                                            <th>Nilai</th>
                                            <th>Tahap 1</th>
                                            <th>Tahap 2</th>
                                            <th>Berkas</th>
                                            <th class="text-end">Aksi</th>
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
                    ajax: "{{ route('penilaian.penggalang.index') }}",
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
                            data: 'email',
                            name: 'email'
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
                            data: 'nilai',
                            name: 'nilai'
                        },
                        {
                            data: 'status_1',
                            name: 'status_1'
                        },
                        {
                            data: 'status_2',
                            name: 'status_2'
                        },
                        {
                            data: 'berkas',
                            name: 'berkas'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
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
                            url: "{{ route('penilaian.penggalang.index') }}",
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
                                data: 'email',
                                name: 'email'
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
                                data: 'nilai',
                                name: 'nilai'
                            },
                            {
                                data: 'status_1',
                                name: 'status_1'
                            },
                            {
                                data: 'status_2',
                                name: 'status_2'
                            },
                            {
                                data: 'berkas',
                                name: 'berkas'
                            },
                            {
                                data: 'aksi',
                                name: 'aksi',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });
                });

                // Terima Data
                $('body').on('click', '#btnTerima', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Ubah Status',
                        text: "Apakah anda yakin?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Terima!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "{{ url('penilaian/penggalang/terima') }}/" + id,
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sukses',
                                            text: response.success,
                                        });
                                        table.ajax.reload();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: response.error,
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.error(xhr.status + "\n" + xhr.responseText +
                                        "\n" + thrownError);
                                }
                            });
                        }
                    });
                });

                // Tolak Data
                $('body').on('click', '#btnTolak', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Ubah Status',
                        text: "Apakah anda yakin?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Tolak!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "{{ url('penilaian/penggalang/tolak') }}/" + id,
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sukses',
                                            text: response.success,
                                        });
                                        table.ajax.reload();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: response.error,
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.error(xhr.status + "\n" + xhr.responseText +
                                        "\n" + thrownError);
                                }
                            });
                        }
                    });
                });

                 // Pertimbangkan Data
                 $('body').on('click', '#btnPertimbangkan', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Ubah Status',
                        text: "Apakah anda yakin?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Pertimbangkan!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "{{ url('penilaian/penggalang/pertimbangkan') }}/" + id,
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sukses',
                                            text: response.success,
                                        });
                                        table.ajax.reload();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: response.error,
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.error(xhr.status + "\n" + xhr.responseText +
                                        "\n" + thrownError);
                                }
                            });
                        }
                    });
                });


                // Hapus Data
                $('body').on('click', '#btnHapus', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Hapus',
                        text: "Apakah anda yakin?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('penilaian/semua/delete') }}/" + id,
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sukses',
                                            text: response.success,
                                        });
                                        table.ajax.reload();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: response.error,
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.error(xhr.status + "\n" + xhr.responseText +
                                        "\n" + thrownError);
                                }
                            });
                        }
                    });
                });
            })
        </script>
    @endsection
