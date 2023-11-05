@extends('layouts.backend.main')
@section('title', 'Soal')
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
                    <div class="card card-table comman-shadow">
                        <div class="card-body">

                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">@yield('title')</h3>
                                    </div>
                                    @if (auth()->user()->type != 'Juri')
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="{{ route('soal.create') }}" class="btn btn-primary"> <i
                                                    class="fas fa-plus"></i></a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table border-0 table-hover table-center mb-0 table-striped datatable"
                                    id="datatable">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>No</th>
                                            <th>Persyaratan</th>
                                            <th>Keterangan</th>
                                            <th>Bobot</th>
                                            <th>Golongan</th>
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
                    ajax: "{{ route('soal.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'persyaratan',
                            name: 'persyaratan',
                            render: function(data) {
                                return data.length > 50 ? data.substr(0, 50) + '...' : data;
                            }
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan',
                            render: function(data) {
                                return data.length > 50 ? data.substr(0, 50) + '...' : data;
                            }
                        },
                        {
                            data: 'bobot_nilai',
                            name: 'bobot_nilai'
                        },
                        {
                            data: 'golongan',
                            name: 'golongan'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false
                        }
                    ]
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
                                url: "{{ url('soal/delete') }}/" + id,
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
