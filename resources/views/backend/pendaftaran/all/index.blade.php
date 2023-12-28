@extends('layouts.backend.main')
@section('title', 'Pendaftaran')
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
                                            <th>Email</th>
                                            <th>No Telepon</th>
                                            <th>Golongan</th>
                                            <th>Pangkalan</th>
                                            <th>Status</th>
                                            <th>Berkas</th>
                                            <th>Kompres</th>
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

        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Nomor Tanda Anggota</td>
                                <td>:</td>
                                <td id="nta"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td id="name"></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td id="email"></td>
                            </tr>
                            <tr>
                                <td>No Telepon</td>
                                <td>:</td>
                                <td id="no_telepon"></td>
                            </tr>
                            <tr>
                                <td>Tempat, Tanggal Lahir</td>
                                <td>:</td>
                                <td id="ttl"></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td id="alamat"></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td id="jenis_kelamin"></td>
                            </tr>
                            <tr>
                                <td>Kwartir Ranting</td>
                                <td>:</td>
                                <td id="kwaran"></td>
                            </tr>
                            <tr>
                                <td>Gugus Depan</td>
                                <td>:</td>
                                <td id="gudep"></td>
                            </tr>
                            <tr>
                                <td>Pangkalan</td>
                                <td>:</td>
                                <td id="pangkalan"></td>
                            </tr>
                            <tr>
                                <td>Golongan</td>
                                <td>:</td>
                                <td id="golongan"></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td id="status"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                    ajax: "{{ route('pendaftaran.semua.index') }}",
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
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'berkas',
                            name: 'berkas',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'persentase',
                            name: 'persentase',
                            orderable: false,
                            searchable: false
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
                            url: "{{ route('pendaftaran.semua.index') }}",
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
                                data: 'status',
                                name: 'status',
                            },
                            {
                                data: 'berkas',
                                name: 'berkas',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'persentase',
                                name: 'persentase',
                                orderable: false,
                                searchable: false
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

                // Detail Data
                $('body').on('click', '#btnDetail', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        type: "POST",
                        url: "{{ url('/pendaftaran/semua/detail/"+id+"') }}",
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#nta').text(response.pendaftaran.nta);
                            $('#name').text(response.pendaftaran.user.name);
                            $('#email').text(response.pendaftaran.user.email);
                            $('#no_telepon').text(response.pendaftaran.user.no_telepon);

                            var tanggalLahir = response.pendaftaran.tanggal_lahir;

                            var tanggalLahirArray = tanggalLahir.split("-");
                            var tanggal = tanggalLahirArray[2];
                            var bulan = tanggalLahirArray[1];
                            var tahun = tanggalLahirArray[0];

                            var namaBulan = [
                                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                "Juli", "Agustus", "September", "Oktober", "November",
                                "Desember"
                            ];

                            var namaBulanLahir = namaBulan[parseInt(bulan) - 1];

                            var tanggalLahirString = tanggal + " " + namaBulanLahir + " " + tahun;
                            $('#ttl').text(response.pendaftaran.tempat_lahir + ", " +
                                tanggalLahirString);

                            $('#alamat').text(response.pendaftaran.alamat);
                            $('#jenis_kelamin').text(response.pendaftaran.jenis_kelamin);
                            $('#kwaran').text(response.pendaftaran.kwaran);
                            $('#gudep').text(response.pendaftaran.gudep);
                            $('#pangkalan').text(response.pendaftaran.pangkalan);
                            $('#golongan').text(response.pendaftaran.golongan.name);

                            var statusText = '';
                            var badgeClass = '';

                            switch (response.pendaftaran.status) {
                                case 1:
                                    badgeClass = 'badge-soft-info';
                                    statusText = 'Dalam Proses';
                                    break;
                                case 2:
                                    badgeClass = 'badge-soft-success';
                                    statusText = 'Terima';
                                    break;
                                case 3:
                                    badgeClass = 'badge-soft-danger';
                                    statusText = 'Tolak';
                                    break;
                                default:
                                    badgeClass = 'badge-soft-secondary';
                                    statusText = 'Unknown';
                            }

                            $('#status').html('<span class="badge ' + badgeClass + '">' +
                                statusText + '</span>');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                thrownError);
                        }
                    })
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
                                url: "{{ url('pendaftaran/semua/terima') }}/" + id,
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
                                url: "{{ url('pendaftaran/semua/tolak') }}/" + id,
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
                                url: "{{ url('pendaftaran/semua/delete') }}/" + id,
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
