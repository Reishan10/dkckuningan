@extends('layouts.frontend.main')
@section('title', 'Beranda')
@section('content')
    <style>
        .fixed-size-image {
            width: 800px;
            height: auto;
            max-width: 100%;
            max-height: 100%;
        }
    </style>

    <!-- Start Hero -->
    <section class="bg-half-170 pb-0 bg-light d-table w-100 overflow-hidden"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') top; z-index: 0;">
        <div class="container">
            <div class="row align-items-center mt-5 mt-sm-0">
                <div class="col-md-6">
                    <div class="title-heading text-center text-md-start">
                        <span class="badge rounded-pill bg-soft-primary">GARUDAKU</span>
                        <h5 class="heading mb-3 mt-2">Pramuka Garuda Kwartir Cabang Kabupaten <span
                                class="fw-bold">Kuningan</span></h5>
                        <a href="#tentang" class="btn btn-primary rounded-pill">Tentang</a>
                    </div>
                </div><!--end col-->

                <div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0">
                    <div class="freelance-hero position-relative">
                        <div class="bg-shape position-relative">
                            <img src="{{ asset('assets_frontend') }}/images/hero-jokowi.png"
                                class="mx-auto d-block img-fluid" alt="">
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End Hero -->

    <section class="section">
        <div class="container">
            <div class="row align-items-center" id="counter">
                <div class="col-md-6">
                    <img src="{{ asset('assets_frontend') }}/images/about-1.jpeg" class="img-fluid" alt="">
                </div><!--end col-->

                <div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0" id="tentang">
                    <div class="ms-lg-4">
                        <div class="section-title">
                            <h4 class="title mb-4">Tentang</h4>
                            <p class="text-muted" style="text-align: justify;">
                                Berdasarkan Surat Keputusan Kwartir Nasional Gerakan Pramuka Nomor 101 Tahun 1984 tentang
                                Petunjuk Penyelenggaraan <span class="text-primary fw-bold">Pramuka Garuda</span> : <span
                                    class="text-primary fw-bold">Pramuka Garuda</span> ialah seorang Pramuka yang dapat
                                menjadi teladan dan telah memenuhi persyaratan serta memiliki Tanda Parmuka Garuda. Tanda
                                <span class="text-primary fw-bold">Pramuka Garuda</span> adalah tanda kecakapan tertinggi
                                yang
                                diberikan kepada seorang Pramuka yang
                                memenuhi syarat-syarat <span class="text-primary fw-bold">Pramuka Garuda</span>. Selain itu,
                                Tanda
                                <span class="text-primary fw-bold">Pramuka Garuda</span> sebagai alat yang
                                mempunyai nilai pendidikan dalam rangka menerapkan prinsip dasar metodik pendidikan
                                kepramukaan.
                            </p>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->

    <!-- Start -->
    <section class="section bg-light"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') center center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-3 pb-2">
                        <h6 class="text-primary">KWARTIR CABANG KABUPATEN KUNINGAN</h6>
                        <h4 class="title mb-4">Tahapan Pendaftaran Pramuka Garuda</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-md-4 mt-4 pt-2">
                    <div
                        class="card features feature-primary feature-clean work-process bg-transparent process-arrow border-0 text-center">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-presentation-edit d-block rounded h3 mb-0"></i>
                        </div>

                        <div class="card-body">
                            <h5 class="text-dark">Mengisi Biodata</h5>
                            {{-- <p class="text-muted mb-0">The most well-known dummy text is the 'Lorem Ipsum', which is
                                said to have originated</p> --}}
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 mt-md-5 pt-md-3 mt-4 pt-2">
                    <div
                        class="card features feature-primary feature-clean work-process bg-transparent process-arrow border-0 text-center">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-airplay d-block rounded h3 mb-0"></i>
                        </div>

                        <div class="card-body">
                            <h5 class="text-dark">Test & Seleksi</h5>
                            {{-- <p class="text-muted mb-0">Generators convallis odio, vel pharetra quam malesuada vel. Nam
                                porttitor malesuada.</p> --}}
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 mt-md-5 pt-md-5 mt-4 pt-2">
                    <div
                        class="card features feature-primary feature-clean work-process bg-transparent d-none-arrow border-0 text-center">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-image-check d-block rounded h3 mb-0"></i>
                        </div>

                        <div class="card-body">
                            <h5 class="text-dark">Pengumuman</h5>
                            {{-- <p class="text-muted mb-0">Internet Proin tempus odio, vel pharetra quam malesuada vel. Nam
                                porttitor malesuada.</p> --}}
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->

    <!-- Start -->
    <section class="section"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') center center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-3 pb-2">
                        <h6 class="text-primary">KWARTIR CABANG KABUPATEN KUNINGAN</h6>
                        <h4 class="title mb-4">Berita Terbaru</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                @forelse ($konten as $row)
                    <div class="col-lg-4 col-md-6 mt-4 pt-2">
                        <div class="card blog blog-primary rounded border-0 shadow overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ asset('storage/konten/' . $row->image) }}" class="card-img-top" alt="...">
                                <div class="overlay rounded-top"></div>
                            </div>
                            <div class="card-body content">
                                <h5><a href="{{ route('berita-garudaku.detail', $row->slug) }}"
                                        class="card-title title text-dark">{{ $row->title }}</a></h5>
                                <div class="post-meta d-flex justify-content-between mt-3">
                                    <a href="{{ route('berita-garudaku.detail', $row->slug) }}"
                                        class="text-muted readmore">Baca Selengkapnya <i
                                            class="uil uil-angle-right-b align-middle"></i></a>
                                </div>
                            </div>
                            <div class="author">
                                <small class="user d-block"><i class="uil uil-user"></i>
                                    {{ $row->user->name }}</small>
                                <small class="date"><i class="uil uil-calendar-alt"></i>
                                    {{ $formattedDate = \Carbon\Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM Y') }}</small>
                            </div>
                        </div>
                    </div><!--end col-->

                @empty
                    <div class="col-md-12">
                        <p class="text-center">Data tidak tersedia</p>
                    </div>
                @endforelse
                <div class="col-12 mt-4 pt-2">
                    <div class="text-center">
                        <a href="{{ route('berita-garudaku.index') }}" class="btn btn-primary">Berita Lainnya <i
                                data-feather="arrow-right" class="fea icon-sm"></i></a>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->
@endsection
