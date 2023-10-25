<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Register - DKC Kuningan</title>

    <link rel="shortcut icon" href="{{ asset('assets') }}/img/favicon.png">

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
                            <h1>Sign Up</h1>
                            <p class="account-subtitle">Masukan detail data anda untuk mendaftar</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Nama <span class="login-danger">*</span></label>
                                    <input class="form-control " type="text" name="name" id="name"
                                        value="{{ old('name') }}" autofocus>
                                    @error('name')
                                        <span class="text-danger text-sm">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" id="email"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger text-sm">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>No Telepon <span class="login-danger">*</span></label>
                                    <input class="form-control" type="number" name="no_telepon" id="no_telepon"
                                        value="{{ old('no_telepon') }}">
                                    @error('no_telepon')
                                        <span class="text-danger text-sm">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <div class="password-input">
                                        <input class="form-control pass-input" name="password" id="password"
                                            type="password" value="{{ old('password') }}">
                                        <span class="profile-views toggle-password feather-eye-off"></span>
                                    </div>
                                    @error('password')
                                        <span class="text-danger text-sm">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi password <span class="login-danger">*</span></label>
                                    <div class="password-input">
                                        <input class="form-control" type="password" name="password_confirmation"
                                            id="password_confirmation" value="{{ old('password_confirmation') }}">
                                        <span class="profile-views toggle-password feather-eye-off"></span>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="text-danger text-sm">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class=" dont-have">Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block" type="submit">Register</button>
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
</body>

</html>
