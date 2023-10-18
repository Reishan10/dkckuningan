<div class="col-lg-3 col-md-4">
    <ul class="inbox-menu">
        <li class="{{ request()->routeIs('pengaturan.profile') ? ' active' : '' }}">
            <a href="{{ route('pengaturan.profile') }}"><i class="fas fa-user"></i> Profile</a>
        </li>
        <li class="{{ request()->routeIs('pengaturan.gantiPassword') ? ' active' : '' }}">
            <a href="{{ route('pengaturan.gantiPassword') }}"><i class="fas fa-lock"></i> Ganti Password</a>
        </li>
        <li class="{{ request()->routeIs('pengaturan.nonaktifAkun') ? ' active' : '' }}">
            <a href="{{ route('pengaturan.nonaktifAkun') }}"><i class="fas fa-times"></i> Nonaktifkan Akun</a>
        </li>
    </ul>
</div>
