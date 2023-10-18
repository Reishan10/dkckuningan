<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                <li class="{{ request()->routeIs(['dashboard.index']) ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}"><i class="feather-grid"></i> <span>Dashboard</span></a>
                </li>
                <li class="{{ request()->routeIs(['konten.index']) ? 'active' : '' }}">
                    <a href="{{ route('konten.index') }}"><i class="fa fa-newspaper"></i> <span>Konten</span></a>
                </li>
                <li
                    class="submenu {{ request()->routeIs(['pendaftaran.semua.index', 'pendaftaran.siaga.index', 'pendaftaran.penggalang.index', 'pendaftaran.penegak.index', 'pendaftaran.pandega.index']) ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-graduation-cap"></i> <span> Pendaftar</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{ request()->routeIs(['pendaftaran.semua.index']) ? 'active' : '' }}">
                            <a href="{{ route('pendaftaran.semua.index') }}">Semua Data</a>
                        </li>
                        <li class="{{ request()->routeIs(['pendaftaran.siaga.index']) ? 'active' : '' }}">
                            <a href="{{ route('pendaftaran.siaga.index') }}">Siaga</a>
                        </li>
                        <li class="{{ request()->routeIs(['pendaftaran.penggalang.index']) ? 'active' : '' }}">
                            <a href="{{ route('pendaftaran.penggalang.index') }}">Penggalang</a>
                        </li>
                        <li class="{{ request()->routeIs(['pendaftaran.penegak.index']) ? 'active' : '' }}">
                            <a href="{{ route('pendaftaran.penegak.index') }}">Penegak</a>
                        </li>
                        <li class="{{ request()->routeIs(['pendaftaran.pandega.index']) ? 'active' : '' }}">
                            <a href="{{ route('pendaftaran.pandega.index') }}">Pandega</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs(['timeline.index']) ? 'active' : '' }}">
                    <a href="{{ route('timeline.index') }}"><i class="fas fa-clipboard-list"></i> <span>Time Line</span></a>
                </li>
                <li class="{{ request()->routeIs(['soal.index']) ? 'active' : '' }}">
                    <a href="{{ route('soal.index') }}"><i class="fas fa-clipboard"></i> <span>Soal</span></a>
                </li>
                <li class="{{ request()->routeIs(['golongan.index']) ? 'active' : '' }}">
                    <a href="{{ route('golongan.index') }}"><i class="fas fa-box"></i> <span>Golongan</span></a>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-clipboard"></i> <span> Arsip</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="teachers.html">Teacher List</a></li>
                        <li><a href="teacher-details.html">Teacher View</a></li>
                        <li><a href="add-teacher.html">Teacher Add</a></li>
                        <li><a href="edit-teacher.html">Teacher Edit</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Hasil Penilaian</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="teachers.html">Teacher List</a></li>
                        <li><a href="teacher-details.html">Teacher View</a></li>
                        <li><a href="add-teacher.html">Teacher Add</a></li>
                        <li><a href="edit-teacher.html">Teacher Edit</a></li>
                    </ul>
                </li>
                <li
                    class="submenu {{ request()->routeIs(['pengguna.semua.index', 'pengguna.semua.create', 'pengguna.semua.edit', 'pengguna.juri.index', 'pengguna.juri.create', 'pengguna.juri.edit', 'pengguna.panitia.index', 'pengguna.panitia.create', 'pengguna.panitia.edit', 'pengguna.peserta.index', 'pengguna.peserta.create', 'pengguna.peserta.edit']) ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-users"></i> <span> Pengguna</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{ request()->routeIs(['pengguna.semua.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.semua.index') }}">Semua Data</a>
                        </li>
                        <li class="{{ request()->routeIs(['pengguna.juri.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.juri.index') }}">Juri</a>
                        </li>
                        <li class="{{ request()->routeIs(['pengguna.panitia.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.panitia.index') }}">Panitia</a>
                        </li>
                        <li class="{{ request()->routeIs(['pengguna.peserta.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.peserta.index') }}">
                                Peserta</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="{{ request()->routeIs(['pengaturan.profile', 'pengaturan.gantiPassword', 'pengaturan.nonaktifAkun']) ? 'active' : '' }}">
                    <a href="{{ route('pengaturan.profile') }}"><i class="fas fa-cog"></i> <span>Pengaturan</span></a>
                </li>
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="fas fa-sign-out-alt"></i> <span>Keluar</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
