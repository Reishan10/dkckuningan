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
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <button type="button" class="btn btn-primary" id="btnTambah">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
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

        <!-- Golongan modal -->
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="form">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modalLabel"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group local-forms mb-3">
                                <input type="hidden" name="id" id="id">
                                <label>Persyaratan <span class="login-danger">*</span></label>
                                <textarea name="persyaratan" id="persyaratan" rows="2" class="form-control"></textarea>
                                <div class="invalid-feedback errorPersyaratan"></div>
                            </div>
                            <div class="form-group local-forms mb-3">
                                <label>Keterangan <span class="login-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan" rows="2" class="form-control"></textarea>
                                <div class="invalid-feedback errorKeterangan"></div>
                            </div>
                            <div class="form-group local-forms mb-3">
                                <label>Bobot <span class="login-danger">*</span></label>
                                <input class="form-control" type="number" name="bobot_nilai" id="bobot_nilai">
                                <div class="invalid-feedback errorBobotNilai"></div>
                            </div>
                            <div class="form-group local-forms mb-3">
                                <label>Golongan <span class="login-danger">*</span></label>
                                <select name="golongan" id="golongan" class="form-control">
                                    <option value="">Pilih Golongan</option>
                                    @foreach ($golongan as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback errorGolongan"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


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


                // Tambah Data
                $('#btnTambah').click(function() {
                    $('#id').val('');
                    $('#modalLabel').html("Tambah Soal");
                    $('#modal').modal('show');
                    $('#form').trigger("reset");

                    $('#persyaratan').removeClass('is-invalid');
                    $('.errorPersyaratan').html('');

                    $('#keterangan').removeClass('is-invalid');
                    $('.errorKeterangan').html('');

                    $('#bobot_nilai').removeClass('is-invalid');
                    $('.errorBobotNilai').html('');

                    $('#golongan').removeClass('is-invalid');
                    $('.errorGolongan').html('');
                });

                // Edit Data
                $('body').on('click', '#btnEdit', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        type: "GET",
                        url: "soal/" + id + "/edit",
                        dataType: "json",
                        success: function(response) {
                            $('#modalLabel').html("Edit Soal");
                            $('#simpan').val("edit-modal");
                            $('#modal').modal('show');

                            $('#persyaratan').removeClass('is-invalid');
                            $('.errorPersyaratan').html('');

                            $('#keterangan').removeClass('is-invalid');
                            $('.errorKeterangan').html('');

                            $('#bobot_nilai').removeClass('is-invalid');
                            $('.errorBobotNilai').html('');

                            $('#golongan').removeClass('is-invalid');
                            $('.errorGolongan').html('');

                            $('#id').val(response.id);
                            $('#persyaratan').val(response.persyaratan);
                            $('#keterangan').val(response.keterangan);
                            $('#bobot_nilai').val(response.bobot_nilai);
                            $('#golongan').val(response.golongan_id);
                        }
                    });
                });

                $('#form').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        data: $(this).serialize(),
                        url: "{{ route('soal.store') }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend: function() {
                            $('#simpan').attr('disabled', 'disabled');
                            $('#simpan').text('Proses...');
                        },
                        complete: function() {
                            $('#simpan').removeAttr('disabled');
                            $('#simpan').html('Simpan');
                        },
                        success: function(response) {
                            if (response.errors) {
                                if (response.errors.persyaratan) {
                                    $('#persyaratan').addClass('is-invalid');
                                    $('.errorPersyaratan').html(response.errors.persyaratan);
                                } else {
                                    $('#persyaratan').removeClass('is-invalid');
                                    $('.errorPersyaratan').html('');
                                }

                                if (response.errors.keterangan) {
                                    $('#keterangan').addClass('is-invalid');
                                    $('.errorKeterangan').html(response.errors.keterangan);
                                } else {
                                    $('#keterangan').removeClass('is-invalid');
                                    $('.errorKeterangan').html('');
                                }

                                if (response.errors.bobot_nilai) {
                                    $('#bobot_nilai').addClass('is-invalid');
                                    $('.errorBobotNilai').html(response.errors.bobot_nilai);
                                } else {
                                    $('#bobot_nilai').removeClass('is-invalid');
                                    $('.errorBobotNilai').html('');
                                }

                                if (response.errors.golongan) {
                                    $('#golongan').addClass('is-invalid');
                                    $('.errorGolongan').html(response.errors.golongan);
                                } else {
                                    $('#golongan').removeClass('is-invalid');
                                    $('.errorGolongan').html('');
                                }
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: 'Data berhasil disimpan',
                                });
                                $('#modal').modal('hide');
                                $('#form').trigger("reset");
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                                thrownError);
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
