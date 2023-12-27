@extends('layouts.frontend.main')
@section('title', 'Notifikasi')
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title mb-0"> Notifikasi </h4>
                    </div>
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->

    <!-- Start -->
    <section class="section overflow-hidden">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h6 class="text-primary">KWARTIR CABANG KABUPATEN KUNINGAN</h6>
                        <h4 class="title mb-4">Notifikasi Pramuka Garuda</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-md-12 mt-4 pt-2">
                    <div class="rounded shadow p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-1">Pesan Notifikasi :</h5>
                        </div>
                        <hr>

                        @forelse ($notifications as $notification)
                            <div class="d-flex border-bottom p-3">
                                <div class="d-flex ms-2">
                                    <img src="{{ asset('storage/avatar/' . $notification->data['avatar']) }}"
                                        class="avatar avatar-md-sm rounded-pill shadow" alt="">
                                    <div class="flex-1 ms-3">
                                        <h6 class="text-dark">{{ $notification->data['name'] }}</h6>
                                        <p class="text-muted mb-0"><strong>Hebat!</strong> Selamat, Anda telah melewati
                                            seleksi Pramuka Garuda dengan sukses. Anda layak menjadi bagian dari perjalanan
                                            luar biasa ini, <a href="{{ route('notifikasi-garudaku.download') }}">Download
                                                Surat Keputusan</a></p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="d-flex border-bottom p-3">
                                <div class="d-flex ms-2 align-items-center justify-content-center w-100">
                                    <div class="flex-1 ms-3 text-center">
                                        <h6 class="text-muted mb-0">Tidak ada notifikasi.</h6>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        {{-- <div class="d-flex align-items-center justify-content-between mt-4">
                            <span class="text-muted h6 mb-0">Showing 8 out of 33</span>
                            <a href="javascript:void(0)" class="btn btn-primary">See more</a>
                        </div> --}}
                    </div>
                </div>
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->
@endsection
