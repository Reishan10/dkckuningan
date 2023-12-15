<div class="header">

    <div class="header-left">
        <a href="index.html" class="logo">
            <img src="{{ asset('assets_frontend') }}/images/logo_garudaku.png" alt="Logo"> Garudaku
        </a>
        {{-- <a href="index.html" class="logo logo-small">
            <img src="{{ asset('assets') }}/img/logo-small.png" alt="Logo" width="30" height="30">
        </a> --}}
    </div>
    <div class="menu-toggle">
        <a href="javascript:void(0);" id="toggle_btn">
            <i class="fas fa-bars"></i>
        </a>
    </div>

    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>

    <ul class="nav user-menu">

        @if (auth()->user()->type == 'Peserta')
            <li class="nav-item dropdown noti-dropdown me-2">
                <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets') }}/img/icons/header-icon-05.svg" alt="">
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <span class="notification-title">Notifikasi</span>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            @foreach ($notifikasi as $row)
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="rounded-circle"
                                                    src="{{ $row->sender->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . $row->sender->name : asset('storage/avatar/' . $row->sender->avatar) }}"
                                                    width="31" alt="{{ $row->sender->name }}">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span
                                                        class="noti-title">{{ $row->sender->name }}</span><br>
                                                    <span class="noti-title">{{ $row->message }}<span>
                                                </p>
                                                <span
                                                    class="notification-time">{{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
        @endif

        <li class="nav-item dropdown has-arrow new-user-menus">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img class="rounded-circle"
                        src="{{ auth()->user()->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . auth()->user()->name : asset('storage/avatar/' . auth()->user()->avatar) }}"
                        width="31" alt="{{ auth()->user()->name }}">
                    <div class="user-text">
                        <h6>{{ auth()->user()->name }}</h6>
                        <p class="text-muted mb-0">{{ auth()->user()->type }}</p>
                    </div>
                </span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="{{ auth()->user()->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . auth()->user()->name : asset('storage/avatar/' . auth()->user()->avatar) }}"
                            alt="{{ auth()->user()->name }}" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{ auth()->user()->name }}</h6>
                        <p class="text-muted mb-0">{{ auth()->user()->type }}</p>
                    </div>
                </div>
                <a class="dropdown-item" href="{{ route('pengaturan.profile') }}">Pengaturan</a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</div>
