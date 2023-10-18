@extends('layouts.backend.main')
@section('title', 'Pengaturan')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">@yield('title')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                @include('backend.pengaturan.sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Profile</h5>
                        </div>
                        <div class="card-body">
                            <form id="form">
                                <div class="row form-group">
                                    <input type="hidden" name="id" id="id" value="{{ auth()->user()->id }}">
                                    <label for="foto" class="col-sm-3 col-form-label input-label">Foto</label>
                                    <div class="col-sm-9">
                                        <div class="d-flex align-items-center">
                                            <img class="rounded-circle" alt="{{ auth()->user()->name }}" id="fotoPreview"
                                                style="width: 100px; height: 100px; margin-right: 15px"
                                                src="{{ auth()->user()->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . auth()->user()->name : asset('storage/avatar/' . auth()->user()->avatar) }}">
                                            <button type="button" class="btn btn-danger btn-sm mt-2" id="hapusFoto">Hapus
                                                Foto</button>
                                        </div>
                                        <small style="display: block; margin-top: 10px;">Maksimal foto 5 Mb.</small>
                                        <input type="file" name="foto" id="foto" class="form-control"
                                            onchange="previewFoto(this)">
                                        <small class="text-danger errorFoto"></small>

                                    </div>

                                </div>
                                <div class="row form-group">
                                    <label for="name" class="col-sm-3 col-form-label input-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ auth()->user()->name }}">
                                        <small class="text-danger errorName"></small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="email" class="col-sm-3 col-form-label input-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ auth()->user()->email }}">
                                        <small class="text-danger errorEmail"></small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="no_telepon" class="col-sm-3 col-form-label input-label">No Telepon</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="no_telepon" name="no_telepon"
                                            value="{{ auth()->user()->no_telepon }}">
                                        <small class="text-danger errorNoTelepon"></small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function previewFoto(input) {
            var fotoPreview = document.getElementById('fotoPreview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
                    url: "{{ url('/pengaturan/profile/"+id+"') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#simpan').attr('disable', 'disabled');
                        $('#simpan').text('Proses...');
                    },
                    complete: function() {
                        $('#simpan').removeAttr('disable');
                        $('#simpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.foto) {
                                $('.errorFoto').html(response.errors.foto);
                            } else {
                                $('.errorFoto').html('');
                            }

                            if (response.errors.name) {
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('.errorName').html('');
                            }

                            if (response.errors.email) {
                                $('.errorEmail').html(response.errors.email);
                            } else {
                                $('.errorEmail').html('');
                            }

                            if (response.errors.no_telepon) {
                                $('.errorNoTelepon').html(response.errors.no_telepon);
                            } else {
                                $('.errorNoTelepon').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.success,
                            }).then(function() {
                                top.location.href = "{{ route('pengaturan.profile') }}";
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            // Hapus Foto
            $('body').on('click', '#hapusFoto', function() {
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
                            url: "{{ url('/pengaturan/hapus-foto') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sukses',
                                        text: response.success,
                                    }).then(function() {
                                        top.location.href =
                                            "{{ route('pengaturan.profile') }}";
                                    });
                                } else {
                                    $('.errorFoto').html(response.error);
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

        });
    </script>
@endsection
