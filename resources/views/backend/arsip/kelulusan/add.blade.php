@extends('layouts.backend.main')
@section('title', 'Tambah Arsip')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('surat-kelulusan.index') }}">Arsip</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form id="form">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <input type="hidden" name="id" id="id">
                                            <label>Surat <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="name" id="name">
                                            <div class="invalid-feedback errorName"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Tahun <span class="login-danger">*</span></label>
                                            <input class="form-control"type="number" id="tahun" name="tahun"
                                                min="1900" max="2100" value="{{ date('Y') }}">
                                            <div class="invalid-feedback errorTahun"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Lokasi Terbit <span class="login-danger">*</span></label>
                                            <input class="form-control"type="text" id="lokasi" name="lokasi"
                                                value="Kuningan">
                                            <div class="invalid-feedback errorLokasi"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Tanggal Penetapan <span class="login-danger">*</span></label>
                                            <input class="form-control" type="date" name="tanggal_penetapan"
                                                id="tanggal_penetapan" value="{{ date('Y-m-d') }}">
                                            <div class="invalid-feedback errorTanggalPenetapan"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Nomor Lampiran <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="nomor_lampiran"
                                                id="nomor_lampiran">
                                            <div class="invalid-feedback errorNomorLampiran"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Tamggal Lampiran <span class="login-danger">*</span></label>
                                            <input class="form-control" type="date" name="tanggal_lampiran"
                                                id="tanggal_lampiran">
                                            <div class="invalid-feedback errorTanggalLampiran"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Tentang Lampiran <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="tentang_lampiran"
                                                id="tentang_lampiran">
                                            <div class="invalid-feedback errorTentangLampiran"></div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="student-submit text-end">
                                            <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('surat-kelulusan.store') }}",
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
                            if (response.errors.name) {
                                $('#name').addClass('is-invalid');
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('#name').removeClass('is-invalid');
                                $('.errorName').html('');
                            }

                            if (response.errors.tahun) {
                                $('#tahun').addClass('is-invalid');
                                $('.errorTahun').html(response.errors.tahun);
                            } else {
                                $('#tahun').removeClass('is-invalid');
                                $('.errorTahun').html('');
                            }

                            if (response.errors.lokasi) {
                                $('#lokasi').addClass('is-invalid');
                                $('.errorLokasi').html(response.errors.lokasi);
                            } else {
                                $('#lokasi').removeClass('is-invalid');
                                $('.errorLokasi').html('');
                            }

                            if (response.errors.tanggal_penetapan) {
                                $('#tanggal_penetapan').addClass('is-invalid');
                                $('.errorTanggalPenetapan').html(response.errors
                                    .tanggal_penetapan);
                            } else {
                                $('#tanggal_penetapan').removeClass('is-invalid');
                                $('.errorTanggalPenetapan').html('');
                            }

                            if (response.errors.nomor_lampiran) {
                                $('#nomor_lampiran').addClass('is-invalid');
                                $('.errorNomorLampiran').html(response.errors.nomor_lampiran);
                            } else {
                                $('#nomor_lampiran').removeClass('is-invalid');
                                $('.errorNomorLampiran').html('');
                            }

                            if (response.errors.tanggal_lampiran) {
                                $('#tanggal_lampiran').addClass('is-invalid');
                                $('.errorTanggalLampiran').html(response.errors.tanggal_lampiran);
                            } else {
                                $('#tanggal_lampiran').removeClass('is-invalid');
                                $('.errorTanggalLampiran').html('');
                            }

                            if (response.errors.tentang_lampiran) {
                                $('#tentang_lampiran').addClass('is-invalid');
                                $('.errorTentangLampiran').html(response.errors.tentang_lampiran);
                            } else {
                                $('#tentang_lampiran').removeClass('is-invalid');
                                $('.errorTentangLampiran').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('surat-kelulusan.index') }}";
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });
        })
    </script>
@endsection
