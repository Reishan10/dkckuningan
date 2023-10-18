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
                            <h5 class="card-title">Ganti Password</h5>
                        </div>
                        <div class="card-body">
                            <form id="form">
                                <div class="row form-group">
                                    <label for="old_password" class="col-sm-3 col-form-label input-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="old_password" name="old_password">
                                        <small class="text-danger errorOldPassword"></small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="password" class="col-sm-3 col-form-label input-label">Password
                                        baru</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password">
                                        <small class="text-danger errorPassword"></small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="password_confirmation"
                                        class="col-sm-3 col-form-label input-label">Konfirmasi
                                        password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                        <small class="text-danger errorConfirmationPassword"></small>
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
                    url: "{{ route('pengaturan.updatePassword') }}",
                    type: "POST",
                    dataType: 'json',
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
                            if (response.errors.old_password) {
                                $('#old_password').addClass('is-invalid');
                                $('.errorOldPassword').html(response.errors.old_password);
                            } else {
                                $('#old_password').removeClass('is-invalid');
                                $('.errorOldPassword').html('');
                            }

                            if (response.errors.password) {
                                $('#password').addClass('is-invalid');
                                $('.errorPassword').html(response.errors.password);
                            } else {
                                $('#password').removeClass('is-invalid');
                                $('.errorPassword').html('');
                            }

                            if (response.errors.password_confirmation) {
                                $('#password_confirmation').addClass('is-invalid');
                                $('.errorConfirmationPassword').html(response.errors
                                    .password_confirmation);
                            } else {
                                $('#password_confirmation').removeClass('is-invalid');
                                $('.errorConfirmationPassword').html('');
                            }
                        } else {
                            if (response.error_password) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.error_password,
                                })
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.success,
                                }).then(function() {
                                    top.location.href =
                                        "{{ route('pengaturan.gantiPassword') }}";
                                });
                            }
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });
        });
    </script>
@endsection
