@extends('layouts.backend.main')
@section('title', 'Konten')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <ul class="list-links mb-4">
                        <li
                            class="{{ request()->query('status') !== '1' && request()->query('status') !== '0' ? 'active' : '' }}">
                            <a href="{{ url('konten') }}">Semua</a>
                        </li>
                        <li class="{{ request()->query('status') === '1' ? 'active' : '' }}">
                            <a href="{{ url('konten?status=1') }}">Pending</a>
                        </li>
                        <li class="{{ request()->query('status') === '0' ? 'active' : '' }}">
                            <a href="{{ url('konten?status=0') }}">Publish</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="{{ route('konten.create') }}" class="btn btn-primary btn-blog mb-3"><i
                            class="feather-plus-circle me-1"></i>
                        Tambah Data</a>
                </div>
            </div>
            <div class="row">
                @forelse ($konten as $row)
                    <div class="col-md-6 col-xl-4 col-sm-12 d-flex">
                        <div class="blog grid-blog flex-fill">
                            <div class="blog-image">
                                <a href="blog-details.html"><img class="img-fluid"
                                        src="{{ asset('storage/konten/' . $row->image) }}" alt="Post Image"
                                        style="width: 300px; height: 200px; object-fit: cover;"></a>
                            </div>
                            <div class="blog-content">
                                <ul class="entry-meta meta-item">
                                    <li>
                                        <div class="post-author">
                                            <a href="#">
                                                <img src="{{ $row->user->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . $row->user->name : asset('storage/avatar/' . $row->user->avatar) }}"
                                                    alt="Post Author">
                                                <span>
                                                    <span class="post-title">{{ $row->user->name }}</span>
                                                    <span class="post-date"><i class="far fa-clock"></i>
                                                        {{ $formattedDate = \Carbon\Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM Y') }}</span>
                                                </span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                                <h3 class="blog-title"><a href="blog-details.html">{{ $row->title }}
                                    </a></h3>
                                <p>{{ \Illuminate\Support\Str::limit(
                                    strip_tags(htmlspecialchars_decode($row->content)),
                                    $limit = 150,
                                    $end = '...',
                                ) }}
                                </p>
                            </div>
                            <div class="row">
                                <div class="edit-options">
                                    <div class="edit-delete-btn">
                                        <a href="{{ route('konten.edit', $row->id) }}" class="text-success"><i
                                                class="feather-edit-3 me-1"></i>
                                            Edit</a>
                                        <a href="#" class="text-danger" data-id="{{ $row->id }}"
                                            id="btnHapus"><i class="feather-trash-2 me-1"></i> Hapus</a>
                                    </div>
                                    <div class="text-end inactive-style">
                                        <a href="javascript:void(0);" class="text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteNotConfirmModal"><i class="feather-eye-off me-1"></i>
                                            Pending</a>
                                    </div>
                                </div>
                            </div>
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

                            <!-- Nomor Halaman -->
                            @foreach ($konten->getUrlRange(1, $konten->lastPage()) as $page => $url)
                                @if ($page == $konten->currentPage())
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
                            url: "{{ url('konten/delete') }}/" + id,
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
                                            "{{ route('konten.index') }}";
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
