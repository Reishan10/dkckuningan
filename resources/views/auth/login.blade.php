<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>Login - DKC Kuningan</title>

    <link rel="shortcut icon" href="{{ asset('assets_frontend') }}/images/logo_garudaku.png" />

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/icons/flags/flags.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{ asset('assets_frontend') }}/images/logo_garudaku.png" alt="Logo">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Selamat datang di Pramuka Garuda Kuningan</h1>
                            <h2>Sign in</h2>

                            <form id="form">
                                <div class="form-group">
                                    <label>Email / No Telepon <span class="login-danger">*</span></label>
                                    <input class="form-control" type="text" name="username" id="username" autofocus>
                                    <span class="text-danger text-sm errorUsername"></span>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <div class="password-input">
                                        <input class="form-control pass-input" name="password" id="password"
                                            type="password">
                                        <span class="profile-views toggle-password feather-eye-off"></span>
                                    </div>
                                    <span class="text-danger text-sm errorPassword"></span>
                                </div>

                                <p class="account-subtitle">Belum punya akun? <a
                                        href="{{ route('register') }}">Daftar</a>
                                </p>
                                <div class="forgotpass">
                                    <div class="remember-me">
                                        <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Ingat saya
                                            <input type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    {{-- <a href="forgot-password.html">Lupa Password?</a> --}}
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" id="login"
                                        type="submit">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets') }}/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/js/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    url: "{{ route('login') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#login').attr('disabled', 'disabled');
                        $('#login').text('Proses...');
                    },
                    complete: function() {
                        $('#login').removeAttr('disabled');
                        $('#login').html('Login');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.username) {
                                $('.errorUsername').html(response.errors.username);
                            } else {
                                $('.errorUsername').html('');
                            }

                            if (response.errors.password) {
                                $('.errorPassword').html(response.errors.password);
                            } else {
                                $('.errorPassword').html('');
                            }


                        } else if (response.NoUsername) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: response.NoUsername.message
                            });
                        } else if (response.NonActiveUsername) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: response.NonActiveUsername.message
                            });
                        } else if (response.WrongPassword) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: response.WrongPassword.message
                            });
                        } else {
                            window.location.href = response.redirect;
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
</body>

</html>
