@extends('layouts.backend.main')
@section('title', 'Edit Konten')
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
                                <li class="breadcrumb-item"><a href="{{ route('konten.index') }}">Konten</a></li>
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
                                            <input type="hidden" name="id" id="id" value="{{ $konten->id }}">
                                            <label>Judul <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="title" id="title"
                                                value="{{ $konten->title }}">
                                            <small class="text-danger errorTitle"></small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Konten <span class="login-danger">*</span></label>
                                            <textarea name="content" id="content" rows="2" class="form-control">{{ $konten->content }}</textarea>
                                            <small class="text-danger errorContent"></small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Foto</label>
                                            <input class="dropify" type="file" name="image" id="image"
                                                value="{{ $konten->image }}"
                                                data-default-file="{{ asset('storage/konten/' . $konten->image) }}">
                                            <small class="text-danger errorImage"></small>
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
                    url: "{{ route('konten.store') }}",
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
                            if (response.errors.title) {
                                $('.errorTitle').html(response.errors.title);
                            } else {
                                $('.errorTitle').html('');
                            }

                            if (response.errors.content) {
                                $('.errorContent').html(response.errors.content);
                            } else {
                                $('.errorContent').html('');
                            }

                            if (response.errors.image) {
                                $('.errorImage').html(response.errors.image);
                            } else {
                                $('.errorImage').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('konten.index') }}";
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
