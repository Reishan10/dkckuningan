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
                            <h5 class="card-title">Nonaktif Akun</h5>
                        </div>
                        <div class="card-body">
                            <div class="row form-group">
                                <p class="alert alert-warning">Apakah anda yakin ingin menonaktifkan akun ?</p>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-danger" id="btnNonaktif">Nonaktifkan Akun</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '#btnNonaktif', function() {
                Swal.fire({
                    title: 'Nonaktifkan Akun',
                    text: "Apakah anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Nonaktifkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pengaturan.updateStatus') }}",
                            type: "POST",
                            beforeSend: function() {
                                $('#simpan').attr('disable', 'disabled');
                                $('#simpan').text('Proses...');
                            },
                            complete: function() {
                                $('#simpan').removeAttr('disable');
                                $('#simpan').html('Simpan');
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.success,
                                }).then(function() {
                                    logoutUser();
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.error(xhr.status + "\n" + xhr.responseText +
                                    "\n" +
                                    thrownError);
                            }
                        });
                    }
                });
            });
        });

        function logoutUser() {
            var logoutForm = document.getElementById('logout-form');
            logoutForm.submit();
        }
    </script>
@endsection
