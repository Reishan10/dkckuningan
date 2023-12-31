<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                @if (auth()->user()->type != 'Peserta')
                    <li class="{{ request()->routeIs(['dashboard.index']) ? 'active' : '' }}">
                        <a href="{{ route('dashboard.index') }}"><i class="feather-grid"></i> <span>Dashboard</span></a>
                    </li>
                    <li class="{{ request()->routeIs(['riwayat.index']) ? 'active' : '' }}">
                        <a href="{{ route('riwayat.index') }}"><i class="fas fa-clock"></i> <span>Riwayat
                                Pendaftar</span></a>
                    </li>
                    <li
                        class="{{ request()->routeIs(['timeline.index', 'timeline.create', 'timeline.edit']) ? 'active' : '' }}">
                        <a href="{{ route('timeline.index') }}"><i class="fas fa-clipboard-list"></i> <span>Time
                                Line</span></a>
                    </li>
                @endif
                @if (auth()->user()->type != 'Juri' && auth()->user()->type != 'Peserta')
                    <li
                        class="{{ request()->routeIs(['konten.index', 'konten.create', 'konten.edit']) ? 'active' : '' }}">
                        <a href="{{ route('konten.index') }}"><i class="fa fa-newspaper"></i> <span>Konten</span></a>
                    </li>
                @endif
                @if (auth()->user()->type != 'Peserta' && auth()->user()->type != 'Kwarcab')
                    <li
                        class="submenu {{ request()->routeIs(['pendaftaran.semua.index', 'pendaftaran.siaga.index', 'pendaftaran.penggalang.index', 'pendaftaran.penegak.index', 'pendaftaran.pandega.index', 'penilaian.all.index', 'penilaian.all.nilai', 'penilaian.all.nilai.edit', 'penilaian.all.detail', 'penilaian.siaga.index', 'penilaian.siaga.nilai', 'penilaian.siaga.nilai.edit', 'penilaian.siaga.detail', 'penilaian.penggalang.index', 'penilaian.penggalang.nilai', 'penilaian.penggalang.nilai.edit', 'penilaian.penggalang.detail', 'penilaian.penegak.index', 'penilaian.penegak.nilai', 'penilaian.penegak.nilai.edit', 'penilaian.penegak.detail', 'penilaian.pandega.index', 'penilaian.pandega.nilai', 'penilaian.pandega.nilai.edit', 'penilaian.pandega.detail']) ? 'active' : '' }}">
                        <a href="javascript:void(0);"><i class="fas fa-graduation-cap"></i>
                            <span>{{ auth()->user()->type == 'Juri' ? 'Penilaian' : 'Pendaftaran' }}</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li
                                class="submenu {{ request()->routeIs(['pendaftaran.semua.index', 'penilaian.all.index', 'penilaian.all.nilai', 'penilaian.all.nilai.edit', 'penilaian.all.detail']) ? 'active' : '' }}">
                                <a href="javascript:void(0);"> <span> Semua Data</span> <span
                                        class="menu-arrow"></span></a>
                                <ul>
                                    @if (auth()->user()->type != 'Juri')
                                        <li
                                            class="{{ request()->routeIs(['pendaftaran.semua.index']) ? 'active' : '' }}">
                                            <a href="{{ route('pendaftaran.semua.index') }}">Administrasi</a>
                                    @endif
                            </li>
                            <li
                                class="{{ request()->routeIs(['penilaian.all.index', 'penilaian.all.nilai', 'penilaian.all.nilai.edit', 'penilaian.all.detail']) ? 'active' : '' }}">
                                <a href="{{ route('penilaian.all.index') }}">Interview</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ request()->routeIs(['pendaftaran.siaga.index', 'penilaian.siaga.index', 'penilaian.siaga.nilai', 'penilaian.siaga.nilai.edit', 'penilaian.siaga.detail']) ? 'active' : '' }}">
                        <a href="javascript:void(0);"> <span> Siaga</span> <span class="menu-arrow"></span></a>
                        <ul>
                            @if (auth()->user()->type != 'Juri')
                                <li class="{{ request()->routeIs(['pendaftaran.siaga.index']) ? 'active' : '' }}">
                                    <a href="{{ route('pendaftaran.siaga.index') }}">Administrasi</a>
                                </li>
                            @endif
                            <li
                                class="{{ request()->routeIs(['penilaian.siaga.index', 'penilaian.siaga.nilai', 'penilaian.siaga.nilai.edit', 'penilaian.siaga.detail']) ? 'active' : '' }}">
                                <a href="{{ route('penilaian.siaga.index') }}">Interview</a></li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ request()->routeIs(['pendaftaran.penggalang.index', 'penilaian.penggalang.index', 'penilaian.penggalang.nilai', 'penilaian.penggalang.nilai.edit', 'penilaian.penggalang.detail']) ? 'active' : '' }}">
                        <a href="javascript:void(0);"> <span> Penggalang</span> <span class="menu-arrow"></span></a>
                        <ul>
                            @if (auth()->user()->type != 'Juri')
                                <li class="{{ request()->routeIs(['pendaftaran.penggalang.index']) ? 'active' : '' }}">
                                    <a href="{{ route('pendaftaran.penggalang.index') }}">Administrasi</a>
                                </li>
                            @endif
                            <li
                                class="{{ request()->routeIs(['penilaian.penggalang.index', 'penilaian.penggalang.nilai', 'penilaian.penggalang.nilai.edit', 'penilaian.penggalang.detail']) ? 'active' : '' }}">
                                <a href="{{ route('penilaian.penggalang.index') }}">Interview</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ request()->routeIs(['pendaftaran.penegak.index', 'penilaian.penegak.index', 'penilaian.penegak.nilai', 'penilaian.penegak.nilai.edit', 'penilaian.penegak.detail']) ? 'active' : '' }}">
                        <a href="javascript:void(0);"> <span> Penegak</span> <span class="menu-arrow"></span></a>
                        <ul>
                            @if (auth()->user()->type != 'Juri')
                                <li class="{{ request()->routeIs(['pendaftaran.penegak.index']) ? 'active' : '' }}">
                                    <a href="{{ route('pendaftaran.penegak.index') }}">Administrasi</a>
                                </li>
                            @endif
                            <li
                                class="{{ request()->routeIs(['penilaian.penegak.index', 'penilaian.penegak.nilai', 'penilaian.penegak.nilai.edit', 'penilaian.penegak.detail']) ? 'active' : '' }}">
                                <a href="{{ route('penilaian.penegak.index') }}">Interview</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ request()->routeIs(['pendaftaran.pandega.index', 'penilaian.pandega.index', 'penilaian.pandega.nilai', 'penilaian.pandega.nilai.edit', 'penilaian.pandega.detail']) ? 'active' : '' }}">
                        <a href="javascript:void(0);"> <span> Pandega</span> <span class="menu-arrow"></span></a>
                        <ul>
                            @if (auth()->user()->type != 'Juri')
                                <li class="{{ request()->routeIs(['pendaftaran.pandega.index']) ? 'active' : '' }}">
                                    <a href="{{ route('pendaftaran.pandega.index') }}">Administrasi</a>
                                </li>
                            @endif
                            <li
                                class="{{ request()->routeIs(['penilaian.pandega.index', 'penilaian.pandega.nilai', 'penilaian.pandega.nilai.edit', 'penilaian.pandega.detail']) ? 'active' : '' }}">
                                <a href="{{ route('penilaian.pandega.index') }}">Interview</a>
                            </li>
                        </ul>
                    </li>
            </ul>
            </li>

            <li
                class="submenu {{ request()->routeIs(['soal.index', 'soal.create', 'soal.edit', 'soal.indexSiaga', 'soal.createSiaga', 'soal.editSiaga', 'soal.indexPenggalang', 'soal.createPenggalang', 'soal.editPenggalang', 'soal.indexPenegak', 'soal.createPenegak', 'soal.editPenegak', 'soal.indexPandega', 'soal.createPandega', 'soal.editPandega']) ? 'active' : '' }}">
                <a href="#"><i class="fas fa-clipboard"></i> <span> Kriteria Penilaian</span> <span
                        class="menu-arrow"></span></a>
                <ul>
                    <li class="{{ request()->routeIs(['soal.index', 'soal.create', 'soal.edit']) ? 'active' : '' }}">
                        <a href="{{ route('soal.index') }}">Semua Data</a>
                    </li>
                    <li
                        class="{{ request()->routeIs(['soal.indexSiaga', 'soal.createSiaga', 'soal.editSiaga']) ? 'active' : '' }}">
                        <a href="{{ route('soal.indexSiaga') }}">Siaga</a>
                    </li>
                    <li
                        class="{{ request()->routeIs(['soal.indexPenggalang', 'soal.createPenggalang', 'soal.editPenggalang']) ? 'active' : '' }}">
                        <a href="{{ route('soal.indexPenggalang') }}">Penggalang</a>
                    </li>
                    <li
                        class="{{ request()->routeIs(['soal.indexPenegak', 'soal.createPenegak', 'soal.editPenegak']) ? 'active' : '' }}">
                        <a href="{{ route('soal.indexPenegak') }}">Penegak</a>
                    </li>
                    <li
                        class="{{ request()->routeIs(['soal.indexPandega', 'soal.createPandega', 'soal.editPandega']) ? 'active' : '' }}">
                        <a href="{{ route('soal.indexPandega') }}">Pandega</a>
                    </li>
                </ul>
            </li>
            @endif
            @if (auth()->user()->type == 'Panitia')
                <li class="{{ request()->routeIs(['golongan.index']) ? 'active' : '' }}">
                    <a href="{{ route('golongan.index') }}"><i class="fas fa-box"></i> <span>Golongan</span></a>
                </li>
            @endif
            @if (auth()->user()->type != 'Peserta' && auth()->user()->type != 'Kwarcab')
                <li
                    class="submenu {{ request()->routeIs(['surat-kelulusan.index', 'surat-kelulusan.create', 'surat-kelulusan.edit', 'berkas-pendaftaran.index', 'berkas-pendaftaran.create', 'berkas-pendaftaran.edit', 'berkas-lain.index', 'berkas-lain.create', 'berkas-lain.edit']) ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-clipboard"></i> <span> Arsip</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{ request()->routeIs(['surat-kelulusan.index']) ? 'active' : '' }}"><a
                                href="{{ route('surat-kelulusan.index') }}">Surat Kelulusan</a></li>
                        <li class="{{ request()->routeIs(['berkas-pendaftaran.index']) ? 'active' : '' }}"><a
                                href="{{ route('berkas-pendaftaran.index') }}">Berkas Pendaftaran</a></li>
                        <li class="{{ request()->routeIs(['berkas-lain.index']) ? 'active' : '' }}"><a
                                href="{{ route('berkas-lain.index') }}">Lain-lain</a></li>
                    </ul>
                </li>
            @endif
            @if (auth()->user()->type == 'Panitia')
                <li
                    class="submenu {{ request()->routeIs(['pengguna.semua.index', 'pengguna.semua.create', 'pengguna.semua.edit', 'pengguna.juri.index', 'pengguna.juri.create', 'pengguna.juri.edit', 'pengguna.kwarcab.index', 'pengguna.kwarcab.create', 'pengguna.kwarcab.edit', 'pengguna.peserta.index', 'pengguna.peserta.create', 'pengguna.peserta.edit']) ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-users"></i> <span> Pengguna</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{ request()->routeIs(['pengguna.semua.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.semua.index') }}">Semua Data</a>
                        </li>
                        <li class="{{ request()->routeIs(['pengguna.juri.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.juri.index') }}">Juri</a>
                        </li>
                        <li class="{{ request()->routeIs(['pengguna.kwarcab.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.kwarcab.index') }}">Kwarcab</a>
                        </li>
                        <li class="{{ request()->routeIs(['pengguna.peserta.index']) ? 'active' : '' }}">
                            <a href="{{ route('pengguna.peserta.index') }}">
                                Peserta</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li
                class="{{ request()->routeIs(['pengaturan.profile', 'pengaturan.gantiPassword', 'pengaturan.nonaktifAkun']) ? 'active' : '' }}">
                <a href="{{ route('pengaturan.profile') }}"><i class="fas fa-cog"></i>
                    <span>Pengaturan</span></a>
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
