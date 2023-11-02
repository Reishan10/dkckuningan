@extends('layouts.backend.main')
@section('title', 'Edit Pengguna')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('pengguna.kwarcab.index') }}">kwarcab Data</a></li>
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
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <label>Nama <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="name" id="name"
                                                value="{{ $user->name }}">
                                            <small class="text-danger errorName"></small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" id="email"
                                                value="{{ $user->email }}">
                                            <small class="text-danger errorEmail"></small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>No Telepon <span class="login-danger">*</span></label>
                                            <input class="form-control" type="number" name="no_telepon" id="no_telepon"
                                                value="{{ $user->no_telepon }}">
                                            <small class="text-danger errorNoTelepon"></small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="status" class="col-sm-2">Status</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="status" name="status"
                                                    {{ $user->active_status == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status" id="status-label">
                                                    {{ $user->active_status == '0' ? 'Aktif' : 'Tidak aktif' }}</label>
                                            </div>
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
        const statusCheckbox = document.getElementById('status');
        const statusLabel = document.getElementById('status-label');

        function toggleLabel() {
            if (statusCheckbox.checked) {
                statusLabel.textContent = 'Aktif';
            } else {
                statusLabel.textContent = 'Tidak aktif';
            }
        }

        window.addEventListener('DOMContentLoaded', toggleLabel);
        statusCheckbox.addEventListener('change', toggleLabel);

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();

                const statusValue = statusCheckbox.checked ? 0 : 1;

                $.ajax({
                    data: $(this).serialize() + "&status=" + statusValue,
                    url: "{{ url('pengguna/kwarcab/update/"+id+"') }}",
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
                            if (response.errors.name) {
                                $('#name').addClass('is-invalid');
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('#name').removeClass('is-invalid');
                                $('.errorName').html('');
                            }
                            if (response.errors.email) {
                                $('#email').addClass('is-invalid');
                                $('.errorEmail').html(response.errors.email);
                            } else {
                                $('#email').removeClass('is-invalid');
                                $('.errorEmail').html('');
                            }

                            if (response.errors.no_telepon) {
                                $('#no_telepon').addClass('is-invalid');
                                $('.errorNoTelepon').html(response.errors.no_telepon);
                            } else {
                                $('#no_telepon').removeClass('is-invalid');
                                $('.errorNoTelepon').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('pengguna.kwarcab.index') }}";
                            });
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
