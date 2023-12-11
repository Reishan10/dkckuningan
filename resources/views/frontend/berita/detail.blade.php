@extends('layouts.frontend.main')
@section('title', 'Detail Berita')
@section('content')

    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2> {{ $konten->title }} </h2>
                        <ul class="list-unstyled mt-4 mb-0">
                            <li class="list-inline-item h6 user text-muted me-2"><i class="mdi mdi-account"></i>
                                {{ $konten->user->name }}
                            </li>
                            <li class="list-inline-item h6 date text-muted"><i class="mdi mdi-calendar-check"></i>
                                {{ $formattedDate = \Carbon\Carbon::parse($konten->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                            </li>
                        </ul>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('beranda.index') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('berita-garudaku.index') }}">Berita</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Berita Detail</li>
                    </ul>
                </nav>
            </div>
        </div> <!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Blog STart -->
    <section class="section overflow-hidden"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') center center;">
        <div class="container">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-lg-8 col-md-6">
                    <div class="card blog blog-detail border-0 shadow rounded">
                        <img src="{{ asset('storage/konten/' . $konten->image) }}" class="img-fluid rounded-top"
                            alt="">
                        <div class="card-body content">

                            <span class="text-muted mt-3">{!!  $konten->content !!}</span>

                        </div>
                    </div>
                </div>
                <!-- BLog End -->

                <!-- START SIDEBAR -->
                <div class="col-lg-4 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card border-0 sidebar sticky-bar ms-lg-4">
                        <div class="card-body p-0">
                            <!-- Author -->
                            <div class="text-center">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    Author
                                </span>

                                <div class="mt-4">
                                    <img src="{{ $konten->user->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . $konten->user->name : asset('storage/avatar/' . $konten->user->avatar) }}"
                                        class="img-fluid avatar avatar-medium rounded-pill shadow-md d-block mx-auto"
                                        alt="">

                                    <a href="blog-about.html"
                                        class="text-primary h5 mt-4 mb-0 d-block">{{ $konten->user->name }}</a>
                                    <small class="text-muted d-block">{{ $konten->user->type }}</small>
                                </div>
                            </div>
                            <!-- Author -->

                            <!-- RECENT POST -->
                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    Recent Post
                                </span>

                                <div class="mt-4">
                                    @foreach ($recentPosts as $post)
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/images/blog/' . $post->image) }}"
                                                class="avatar avatar-small rounded" style="width: auto;" alt="">
                                            <div class="flex-1 ms-3">
                                                <a href="{{ route('berita-garudaku.detail', $post->slug) }}"
                                                    class="d-block title text-dark">{{ $post->title }}</a>
                                                <span class="text-muted">
                                                    {{ $formattedDate = \Carbon\Carbon::parse($post->created_at)->locale('id')->isoFormat('D MMMM Y') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- RECENT POST -->


                            <!-- SOCIAL -->
                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    Social Media
                                </span>

                                <ul class="list-unstyled social-icon social text-center mb-0 mt-4">
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="facebook" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="twitter" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="linkedin" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="github" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="youtube" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                                data-feather="gitlab" class="fea icon-sm fea-social"></i></a></li>
                                </ul><!--end icon-->
                            </div>
                            <!-- SOCIAL -->
                        </div>
                    </div>
                </div><!--end col-->
                <!-- END SIDEBAR -->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Blog End -->
@endsection
