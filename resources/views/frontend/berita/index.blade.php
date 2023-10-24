@extends('layouts.frontend.main')
@section('title', 'Berita')
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title mb-0"> Berita </h4>
                    </div>
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->
    <div class="position-relative">
        <div class="shape overflow-hidden text-color-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!-- Hero End -->

    <!--Blog Lists Start-->
    <section class="section">
        <div class="container">
            <div class="row">
                @forelse ($konten as $row)
                    <div class="col-lg-6 col-12 mb-4 pb-2">
                        <div class="card blog blog-primary rounded border-0 shadow overflow-hidden">
                            <div class="row align-items-center g-0">
                                <div class="col-md-6">
                                    <img src="{{ asset('storage/konten/' . $row->image) }}" class="img-fluid"
                                        alt="">
                                    <div class="overlay"></div>
                                    <div class="author">
                                        <small class="user d-block"><i class="uil uil-user"></i>
                                            {{ $row->user->name }}</small>
                                        <small class="date"><i class="uil uil-calendar-alt"></i>
                                            {{ $formattedDate = \Carbon\Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM Y') }}</small>
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="card-body content">
                                        <h5><a href="{{ route('berita-garudaku.detail', $row->slug) }}"
                                                class="card-title title text-dark">{{ $row->title }}</a></h5>
                                        <p class="text-muted mb-0">
                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags(htmlspecialchars_decode($row->content)),
                                                $limit = 150,
                                                $end = '...',
                                            ) }}
                                        </p>
                                        <div class="post-meta d-flex justify-content-between mt-3">
                                            <a href="{{ route('berita-garudaku.detail', $row->slug) }}"
                                                class="text-muted readmore">Baca Selengkapnya <i
                                                    class="uil uil-angle-right-b align-middle"></i></a>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div> <!--end row-->
                        </div><!--end blog post-->
                    </div><!--end col-->
                @empty
                    <div class="col-md-12">
                        <p class="text-center">Data tidak tersedia</p>
                    </div>
                @endforelse

                <!-- PAGINATION START -->
                <div class="col-12">
                    <ul class="pagination justify-content-center mb-0">
                        @if ($konten->onFirstPage())
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1"><i
                                        class="feather-chevron-left mr-2"></i>Previous</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $konten->previousPageUrl() }}"><i
                                        class="feather-chevron-left mr-2"></i>Previous</a>
                            </li>
                        @endif

                        <!-- Pagination Elements -->
                        @foreach ($konten->getUrlRange(1, $konten->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $konten->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }} <span
                                        class="sr-only"></span></a>
                            </li>
                        @endforeach

                        @if ($konten->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $konten->nextPageUrl() }}">Next<i
                                        class="feather-chevron-right ml-2"></i></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Next<i
                                        class="feather-chevron-right ml-2"></i></a>
                            </li>
                        @endif
                    </ul>
                </div><!--end col-->
                <!-- PAGINATION END -->

            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section -->
    <!--Blog Lists End-->
@endsection
