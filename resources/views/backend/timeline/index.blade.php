@extends('layouts.backend.main')
@section('title', 'Timeline')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="row">
                <div class="col-md-12 text-md-end">
                    @if (auth()->user()->type != 'Juri')
                        <a href="{{ route('timeline.create') }}"class="btn btn-primary btn-blog mb-3"><i
                                class="feather-plus-circle me-1"></i>
                            Tambah Data</a>
                    @endif
                </div>
            </div>
            <div class="row">
                @forelse ($timeline as $row)
                    <div class="col-md-6 col-xl-4 col-sm-12 d-flex">
                        <div class="blog grid-blog flex-fill">
                            <div class="blog-image">
                                <a href="{{ route('timeline-garudaku.index') }}" target="_blank">
                                    <img class="img-fluid" src="{{ asset('storage/timeline/' . $row->foto) }}"
                                        alt="{{ $row->foto }}" style="width: 300px; height: 200px; object-fit: cover;">
                                </a>
                            </div>
                            <div class="blog-content">
                                <ul class="entry-meta meta-item">
                                    <li>
                                        <div class="post-author">
                                            <span class="post-date"><i class="far fa-clock"></i>
                                                {{ $formattedDate = \Carbon\Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM Y') }}</span>

                                        </div>
                                    </li>
                                </ul>
                                <h3 class="blog-title"><a href="{{ route('timeline-garudaku.index') }}"
                                        target="_blank">{{ $row->name }}
                                    </a></h3>
                            </div>
                            @if (auth()->user()->type != 'Juri')
                                <div class="row">
                                    <div class="edit-options">
                                        <div class="edit-delete-btn">
                                            <a href="{{ route('timeline.edit', $row->id) }}" class="text-success"><i
                                                    class="feather-edit-3 me-1"></i>
                                                Edit</a>
                                            <a href="#" class="text-danger" data-id="{{ $row->id }}"
                                                id="btnHapus"><i class="feather-trash-2 me-1"></i> Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-center">Data tidak tersedia</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="pagination-tab d-flex justify-content-center">
                        <ul class="pagination mb-0">
                            <!-- Tombol Previous -->
                            @if ($timeline->onFirstPage())
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1"><i
                                            class="feather-chevron-left mr-2"></i>Previous</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $timeline->previousPageUrl() }}"><i
                                            class="feather-chevron-left mr-2"></i>Previous</a>
                                </li>
                            @endif

                            <!-- Nomor Halaman -->
                            @foreach ($timeline->getUrlRange(1, $timeline->lastPage()) as $page => $url)
                                @if ($page == $timeline->currentPage())
                                    <li class="page-item active">
                                        <a class="page-link" href="{{ $url }}">{{ $page }} <span
                                                class="sr-only">(current)</span></a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            <!-- Tombol Next -->
                            @if ($timeline->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $timeline->nextPageUrl() }}">Next<i
                                            class="feather-chevron-right ml-2"></i></a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Next<i
                                            class="feather-chevron-right ml-2"></i></a>
                                </li>
                            @endif
                        </ul>
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

            // Hapus Data
            $('body').on('click', '#btnHapus', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus',
                    text: "Apakah anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('timeline/delete') }}/" + id,
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sukses',
                                        text: response.success,
                                    }).then(function() {
                                        top.location.href =
                                            "{{ route('timeline.index') }}";
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: response.error,
                                    });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.error(xhr.status + "\n" + xhr.responseText +
                                    "\n" + thrownError);
                            }
                        });
                    }
                });
            });
        })
    </script>
@endsection
