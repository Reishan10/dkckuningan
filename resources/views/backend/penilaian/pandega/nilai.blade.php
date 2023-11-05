@extends('layouts.backend.main')
@section('title', 'Penilaian')
@section('content')
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

                            <form id="form">
                                <ul>
                                    @foreach ($soal as $row)
                                        <li>
                                            <strong>No: {{ $loop->iteration }}</strong><br>
                                            <strong> Persyaratan:</strong><br> {!!  $row->persyaratan !!}<br>
                                            <strong> Keterangan:</strong><br> {!! $row->keterangan  !!}<br>
                                            <input type="text" name="nilai[{{ $row->id }}]" class="form-control"
                                                value="0"><br>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                                <button type="submit" class="btn btn-primary float-end" id="simpan">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    url: "{{ route('penilaian-pandega.simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#simpan').attr('disabled', 'disabled');
                        $('#simpan').text('Proses...');
                    },
                    complete: function() {
                        $('#simpan').removeAttr('disabled');
                        $('#simpan').html('Simpan');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Data berhasil disimpan',
                        }).then(function() {
                            top.location.href =
                                "{{ route('penilaian-pandega.index') }}";
                        });
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
