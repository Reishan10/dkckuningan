@extends('layouts.frontend.main')
@section('title', 'Berkas')
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title mb-0"> Berkas Pendaftaran </h4>
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
                        <h4 class="title mb-4">Berkas Pendaftaran Pramuka Garuda</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-md-12 mt-4 pt-2">
                    <div class="table-responsive">
                        <table class="table border-0 table-hover table-center mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Surat</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($berkas as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $formattedDate = \Carbon\Carbon::parse($row->tanggal_terbit)->locale('id')->isoFormat('D MMMM Y') }}
                                        <td>{{ $row->name }}</td>
                                        </td>
                                        <td><a href="{{ asset('storage/arsip/pendaftaran/' . $row->file) }}" target="_blank"
                                                class="btn btn-secondary btn-sm">Tautan ke Berkas</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Data Tidak Tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->
@endsection
