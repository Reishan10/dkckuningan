@extends('layouts.frontend.main')
@section('title', 'Pendaftaran')
@section('content')

    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title mb-0"> Pendaftaran </h4>
                    </div>
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->

    <!-- Hero Start -->
    <section class="section overflow-hidden"
        style="background: url('{{ asset('assets_frontend') }}/images/shapes/shape2.png') center center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h6 class="text-primary">KWARTIR CABANG KABUPATEN KUNINGAN</h6>
                        <h4 class="title mb-4">Pendaftaran Pramuka Garuda</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            <div class="row align-items-center">
                <div class="col-lg-12 col-md-5 order-2 order-md-1 mt-4 pt-2 mt-sm-0 pt-sm-0">
                    <div class="bg-white shadow rounded position-relative overflow-hidden">
                        <div class="tab-content" id="pills-tabContent">
                            @if (!$pendaftaran->count() > 0)
                                <div class="card" id="user">
                                    <form id="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nomor Tanda Anggota <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <input type="hidden" name="id_user" id="id_user"
                                                                value="{{ auth()->user()->id }}">
                                                            <i data-feather="credit-card" class="fea icon-sm icons"></i>
                                                            <input type="text" class="form-control ps-5" name="nta"
                                                                id="nta" placeholder="Nomor Tanda Anggota">
                                                            <small class="text-danger errorNta"></small>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tempat, Tanggal lahir <span
                                                                class="text-danger">*</span></label>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-icon position-relative">
                                                                    <i data-feather="map-pin" class="fea icon-sm icons"></i>
                                                                    <input type="text" class="form-control ps-5"
                                                                        name="tempat_lahir" id="tempat_lahir"
                                                                        placeholder="Tempat">
                                                                    <small class="text-danger errorTempatLahir"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-icon position-relative">
                                                                    <input type="date"
                                                                        class="form-control"name="tanggal_lahir"
                                                                        id="tanggal_lahir" placeholder="Tanggal Lahir">
                                                                    <small class="text-danger errorTanggalLahir"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="map" class="fea icon-sm icons"></i>
                                                            <textarea name="alamat" id="alamat" class="form-control ps-5"></textarea>
                                                            <small class="text-danger errorAlamat"></small>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Kelamin <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <select name="jenis_kelamin" id="jenis_kelamin"
                                                                class="form-control">
                                                                <option value="">Pilih Jenis Kelamin</option>
                                                                <option value="Laki-laki">Laki-laki</option>
                                                                <option value="Perempuan">Perempuan</option>
                                                            </select>
                                                            <small class="text-danger errorJenisKelamin"></small>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kwartir Ranting <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="home" class="fea icon-sm icons"></i>
                                                                <input type="text" class="form-control ps-5"
                                                                    name="kwaran" id="kwaran"
                                                                    placeholder="Kwartir Ranting">
                                                                <small class="text-danger errorKwaran"></small>
                                                            </div>
                                                        </div>
                                                    </div><!--end col-->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pangkalan <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="home" class="fea icon-sm icons"></i>
                                                                <input type="text" class="form-control ps-5"
                                                                    name="pangkalan" id="pangkalan"
                                                                    placeholder="Pangkalan">
                                                                <small class="text-danger errorPangkalan"></small>
                                                            </div>
                                                        </div>
                                                    </div><!--end col-->
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Gugus Depan <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <input type="text" name="gudep" id="gudep"
                                                                class="form-control" placeholder="Gugus Depan">
                                                            <small class="text-danger errorGudep"></small>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Golongan <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <select name="golongan" id="golongan" class="form-control">
                                                                <option value="">Pilih Golongan</option>
                                                                @foreach ($golongan as $row)
                                                                    <option value="{{ $row->id }}">{{ $row->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <small class="text-danger errorGolongan"></small>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Berkas <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <input type="file" class="form-control" name="berkas"
                                                                id="berkas" placeholder="Berkas"
                                                                onchange="updateFileSize()" accept="application/pdf">
                                                            <small class="text-danger errorBerkas"></small>
                                                        </div>
                                                        <small class="text-muted">Ukuran berkas: <span
                                                                id="fileSizeDisplay">-</span></small>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-md-12">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="simpan">Daftar</button>
                                                    </div>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div>
                                    </form><!--end form-->
                                </div><!--end teb pane-->
                            @else
                                <div class="col-lg-12 col-md-6 col-12">
                                    <div class="card bg-light rounded shadow border-0">
                                        <div class="card-body py-5">
                                            <i class="uil uil-exchange h2 text-primary"></i>
                                            <div class="mt-2">
                                                <h5 class="card-title"><a href="{{ route('pengumuman-garudaku.index') }}" class="text-primary">
                                                        Pengumuman</a></h5>
                                                <p class="text-muted mt-3 mb-0">Jangan lewatkan berita terbaru Garudaku!
                                                    Kunjungi <a href="{{ route('pengumuman-garudaku.index') }}">halaman
                                                        Pengumuman</a> untuk informasi lebih lanjut.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            @endif
                        </div><!--end tab content-->
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <script>
        function updateFileSize() {
            var inputBerkas = document.getElementById('berkas');
            var fileSizeKB = inputBerkas.files[0].size / 1024;
            document.getElementById('fileSizeDisplay').textContent = fileSizeKB.toFixed(2) + ' KB';
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('pendaftaran.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#simpan').attr('disable', 'disabled');
                        $('#simpan').text('Proses...');
                    },
                    complete: function() {
                        $('#simpan').removeAttr('disable');
                        $('#simpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.nta) {
                                $('.errorNta').html(response.errors.nta);
                            } else {
                                $('.errorNta').html('');
                            }

                            if (response.errors.tempat_lahir) {
                                $('.errorTempatLahir').html(response.errors.tempat_lahir);
                            } else {
                                $('.errorTempatLahir').html('');
                            }

                            if (response.errors.tanggal_lahir) {
                                $('.errorTanggalLahir').html(response.errors.tanggal_lahir);
                            } else {
                                $('.errorTanggalLahir').html('');
                            }

                            if (response.errors.alamat) {
                                $('.errorAlamat').html(response.errors.alamat);
                            } else {
                                $('.errorAlamat').html('');
                            }

                            if (response.errors.jenis_kelamin) {
                                $('.errorJenisKelamin').html(response.errors.jenis_kelamin);
                            } else {
                                $('.errorJenisKelamin').html('');
                            }

                            if (response.errors.kwaran) {
                                $('.errorKwaran').html(response.errors.kwaran);
                            } else {
                                $('.errorKwaran').html('');
                            }

                            if (response.errors.pangkalan) {
                                $('.errorPangkalan').html(response.errors.pangkalan);
                            } else {
                                $('.errorPangkalan').html('');
                            }

                            if (response.errors.golongan) {
                                $('.errorGolongan').html(response.errors.golongan);
                            } else {
                                $('.errorGolongan').html('');
                            }

                            if (response.errors.gudep) {
                                $('.errorGudep').html(response.errors.gudep);
                            } else {
                                $('.errorGudep').html('');
                            }

                            if (response.errors.berkas) {
                                $('.errorBerkas').html(response.errors.berkas);
                            } else {
                                $('.errorBerkas').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('pendaftaran.index') }}";
                            });
                        }
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
