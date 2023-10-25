@extends('layouts.frontend.main')
@section('title', 'Timeline')
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title mb-0"> Timeline </h4>
                    </div>
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->
  

    <!-- Hero End -->
    <!-- Start -->
    <section class="section overflow-hidden"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') center center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h6 class="text-primary">KWARTIR CABANG KABUPATEN KUNINGAN</h6>
                        <h4 class="title mb-4">Timeline Pendaftaran Pramuka Garuda</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-md-4 mt-4 pt-2">
                    <ul class="nav nav-pills nav-justified flex-column rounded shadow p-3 mb-0 sticky-bar" id="pills-tab"
                        role="tablist">
                        @foreach ($timeline as $row)
                            <li class="nav-item mb-2">
                                <a class="nav-link rounded @if ($loop->first) active @endif"
                                    id="timeline-{{ $row->id }}" data-bs-toggle="pill"
                                    href="#timeline-content-{{ $row->id }}" role="tab"
                                    aria-controls="timeline-content-{{ $row->id }}" aria-selected="false">
                                    <div class="text-center py-1">
                                        <h6 class="mb-0">{{ $row->name }}</h6>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul><!--end nav pills-->
                </div><!--end col-->

                <div class="col-md-8 col-12 mt-4 pt-2">
                    <div class="tab-content" id="pills-tabContent">
                        @foreach ($timeline as $row)
                            <div class="tab-pane fade p-4 rounded shadow @if ($loop->first) show active @endif"
                                id="timeline-content-{{ $row->id }}" role="tabpanel"
                                aria-labelledby="timeline-{{ $row->id }}">
                                <img src="{{ asset('/storage/timeline/' . $row->foto) }}" class="img-fluid fixed-size-image"
                                    alt="">
                            </div><!--end tab pane-->
                        @endforeach
                    </div><!--end tab content-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->
@endsection
