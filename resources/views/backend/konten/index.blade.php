@extends('layouts.backend.main')
@section('title', 'Konten')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="row">
                <div class="col-md-9">
                    <ul class="list-links mb-4">
                        <li class="active"><a href="blog.html">Aktif</a></li>
                        <li><a href="pending-blog.html">Pending</a></li>
                    </ul>
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="{{ route('konten.create') }}" class="btn btn-primary btn-blog mb-3"><i
                            class="feather-plus-circle me-1"></i>
                        Tambah Data</a>
                </div>
            </div>
            <div class="row">
                @foreach ($konten as $row)
                    <div class="col-md-6 col-xl-4 col-sm-12 d-flex">
                        <div class="blog grid-blog flex-fill">
                            <div class="blog-image">
                                <a href="blog-details.html"><img class="img-fluid" src="assets/img/category/blog-6.jpg"
                                        alt="Post Image"></a>
                            </div>
                            <div class="blog-content">
                                <ul class="entry-meta meta-item">
                                    <li>
                                        <div class="post-author">
                                            <a href="profile.html">
                                                <img src="assets/img/profiles/avatar-01.jpg" alt="Post Author">
                                                <span>
                                                    <span class="post-title">Vincent</span>
                                                    <span class="post-date"><i class="far fa-clock"></i> 4 Dec 2022</span>
                                                </span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                                <h3 class="blog-title"><a href="blog-details.html">Learning is an objective, Lorem Ipsum is
                                        not
                                    </a></h3>
                                <p>Lorem ipsum dolor sit amet, consectetur em adipiscing elit, sed do eiusmod tempor.</p>
                            </div>
                            <div class="row">
                                <div class="edit-options">
                                    <div class="edit-delete-btn">
                                        <a href="{{ route('konten.edit', $row->id) }}" class="text-success"><i
                                                class="feather-edit-3 me-1"></i>
                                            Edit</a>
                                        <a href="#" class="text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"><i class="feather-trash-2 me-1"></i> Delete</a>
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
                @endforeach
            </div>

            <div class="row ">
                <div class="col-md-12">
                    <div class="pagination-tab  d-flex justify-content-center">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1"><i
                                        class="feather-chevron-left mr-2"></i>Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active">
                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next<i class="feather-chevron-right ml-2"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
