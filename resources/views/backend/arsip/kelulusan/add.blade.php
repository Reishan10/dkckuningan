@extends('layouts.backend.main')
@section('title', 'Tambah Arsip')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dropify.css') }}">
    <script src="{{ asset('assets/js/dropify.js') }}"></script>

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
                                            <label>Tanggal Terbit <span class="login-danger">*</span></label>
                                            <input class="form-control" type="date" name="tanggal_terbit"
                                                id="tanggal_terbit">
                                            <div class="invalid-feedback errorTanggalTerbit"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>File</label>
                                            <input class="dropify" type="file" name="file" id="file">
                                            <small class="text-danger errorFile"></small>
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
        $('.dropify').dropify();

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('surat-kelulusan.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
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

                            if (response.errors.tanggal_terbit) {
                                $('#tanggal_terbit').addClass('is-invalid');
                                $('.errorTanggalTerbit').html(response.errors.tanggal_terbit);
                            } else {
                                $('#tanggal_terbit').removeClass('is-invalid');
                                $('.errorTanggalTerbit').html('');
                            }

                            if (response.errors.file) {
                                $('#file').addClass('is-invalid');
                                $('.errorFile').html(response.errors.file);
                            } else {
                                $('#file').removeClass('is-invalid');
                                $('.errorFile').html('');
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
