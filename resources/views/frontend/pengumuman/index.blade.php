@extends('layouts.frontend.main')
@section('title', 'Pengumuman')
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title mb-0"> Pengumuman </h4>
                    </div>
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->

    <!-- Start -->
    <section class="section overflow-hidden"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') center center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h6 class="text-primary">KWARTIR CABANG KABUPATEN KUNINGAN</h6>
                        <h4 class="title mb-4">Pengumuman Hasil Pramuka Garuda</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-md-12 mt-4 pt-2">
                    @if ($pendaftaran != '')
                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0">
                                <tr>
                                    <th>Pengumuman</th>
                                    <td>:</td>
                                    <td>
                                        @if ($pendaftaran->status == 1)
                                            @if ($pendaftaran->status_2 == 1)
                                                <span class="badge rounded-pill bg-primary"><strong>Seleksi tahap
                                                        administrasi
                                                        dalam
                                                        proses</strong></span>
                                            @elseif ($pendaftaran->status_2 == 2)
                                                <span class="badge rounded-pill bg-success"> <strong>Selamat anda lolos
                                                        seleksi
                                                        tahap administrasi</strong></span>
                                            @elseif ($pendaftaran->status_2 == 3)
                                                <span class="badge rounded-pill bg-danger"><strong>Mohon maaf anda belum
                                                        lolos
                                                        seleksi tahap administrasi</strong></span>
                                            @endif
                                        @elseif ($pendaftaran->status == 2)
                                            @if ($pendaftaran->status_2 == 1)
                                                <span class="badge rounded-pill bg-primary"><strong>Seleksi tahap akhir
                                                        dalam
                                                        proses</strong></span>
                                            @elseif ($pendaftaran->status_2 == 2)
                                                <span class="badge rounded-pill bg-success"> <strong>Selamat anda lolos
                                                        seleksi
                                                        tahap akhir</strong></span>
                                            @elseif ($pendaftaran->status_2 == 3)
                                                <span class="badge rounded-pill bg-danger"> <strong>Mohon maaf anda belum
                                                        lolos
                                                        seleksi tahap akhir</strong></span>
                                            @endif
                                        @elseif ($pendaftaran->status == 3)
                                            <span class="badge rounded-pill bg-danger"><strong>Mohon maaf anda belum lolos
                                                    seleksi tahap akhir</strong></span>
                                        @endif

                                    </td>
                                </tr>

                                <tr>
                                    <th>Nomor Tanda Anggota</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->nta }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->user->no_telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat, Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->tempat_lahir }},
                                        {{ $formattedDate = \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>kwartir Ranting</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->kwaran }}</td>
                                </tr>
                                <tr>
                                    <th>Gugus Depan</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->gudep }}</td>
                                </tr>
                                <tr>
                                    <th>Golongan</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->golongan->name }}</td>
                                </tr>
                                <tr>
                                    <th>Riwayat Pendaftaran</th>
                                    <td>:</td>
                                    <td>
                                        @foreach ($riwayat_pendaftaran as $row)
                                            {{ $row->golongan->name }} - {{ $row->created_at->year }} <br>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Berkas</td>
                                    <td>:</td>
                                    <td><a href="{{ asset('berkas/' . $pendaftaran->berkas) }}" target="_blank"
                                            class="btn btn-secondary btn-sm">Tautan ke Berkas</a></td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="card">
                            <h6 class="text-center">Data belum tersedia</h6>
                        </div>
                    @endif
                </div>
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->
@endsection
