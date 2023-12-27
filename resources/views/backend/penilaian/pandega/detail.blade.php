@extends('layouts.backend.main')
@section('title', 'Detail Penilaian')
@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">@yield('title')</h3>
                                    </div>
                                </div>
                            </div>

                            <table>
                                <tr>
                                    <td>Nomor Tanda Anggota</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->nta }}</td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->user->email }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->user->no_telepon }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->tempat_Lahir }},
                                        {{ $formattedDate = \Carbon\Carbon::parse($detail_user->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->alamat }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <td>Kwartir Ranting</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->kwaran }}</td>
                                </tr>
                                <tr>
                                    <td>Gugus Depan</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->gudep }}</td>
                                </tr>
                                <tr>
                                    <td>Pangkalan</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->pangkalan }}</td>
                                </tr>
                                <tr>
                                    <td>Golongan</td>
                                    <td>:</td>
                                    <td>{{ $detail_user->golongan->name }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">@yield('title')</h3>
                                    </div>
                                </div>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Persyaratan</th>
                                        <th>Keterangan</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penilaian as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{!! $row->soal->persyaratan !!}</td>
                                            <td>{!! $row->soal->keterangan !!}</td>
                                            <td>{{ $row->nilai }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="3">Rata-Rata Nilai</td>
                                        <td>{{ number_format($detail_user->nilai, 1) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
