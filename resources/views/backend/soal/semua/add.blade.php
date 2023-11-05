@extends('layouts.backend.main')
@section('title', 'Tambah Soal')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('soal.index') }}">Soal</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form id="form">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group local-forms mb-3">
                                            <label>Persyaratan <span class="login-danger">*</span></label>
                                            <textarea name="persyaratan" id="persyaratan" rows="2" class="form-control"></textarea>
                                            <div class="invalid-feedback errorPersyaratan"></div>
                                        </div>
                                    </div>
                                    <div class="form-group local-forms mb-3">
                                        <label>Keterangan <span class="login-danger">*</span></label>
                                        <textarea name="keterangan" id="keterangan" rows="2" class="form-control"></textarea>
                                        <div class="invalid-feedback errorKeterangan"></div>
                                    </div>
                                    <div class="form-group local-forms mb-3">
                                        <label>Bobot <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="bobot_nilai" id="bobot_nilai">
                                        <div class="invalid-feedback errorBobotNilai"></div>
                                    </div>
                                    <div class="form-group local-forms mb-3">
                                        <label>Golongan <span class="login-danger">*</span></label>
                                        <select name="golongan" id="golongan" class="form-control">
                                            <option value="">Pilih Golongan</option>
                                            @foreach ($golongan as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback errorGolongan"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="student-submit text-end">
                                            <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            ClassicEditor
                .create(document.querySelector('#keterangan'), {
                    toolbar: {
                        items: [
                            'findAndReplace', 'selectAll', '|',
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript',
                            'superscript',
                            'removeFormat',
                            'undo', 'redo',
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
                        ]
                    },
                    htmlSupport: {
                        allow: [{
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }]
                    },
                    // Be careful with enabling previews
                    // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                    htmlEmbed: {
                        showPreviews: true
                    },

                    removePlugins: [
                        'CKBox',
                        'CKFinder',
                        'EasyImage',
                        'RealTimeCollaborativeComments',
                        'RealTimeCollaborativeTrackChanges',
                        'RealTimeCollaborativeRevisionHistory',
                        'PresenceList',
                        'Comments',
                        'TrackChanges',
                        'TrackChangesData',
                        'RevisionHistory',
                        'Pagination',
                        'WProofreader',
                        'MathType',
                        'SlashCommand',
                        'Template',
                        'DocumentOutline',
                        'FormatPainter',
                        'TableOfContents',
                        'PasteFromOfficeEnhanced'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#persyaratan'), {
                    toolbar: {
                        items: [
                            'findAndReplace', 'selectAll', '|',
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript',
                            'superscript',
                            'removeFormat',
                            'undo', 'redo',
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
                        ]
                    },
                    htmlSupport: {
                        allow: [{
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }]
                    },
                    // Be careful with enabling previews
                    // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                    htmlEmbed: {
                        showPreviews: true
                    },

                    removePlugins: [
                        'CKBox',
                        'CKFinder',
                        'EasyImage',
                        'RealTimeCollaborativeComments',
                        'RealTimeCollaborativeTrackChanges',
                        'RealTimeCollaborativeRevisionHistory',
                        'PresenceList',
                        'Comments',
                        'TrackChanges',
                        'TrackChangesData',
                        'RevisionHistory',
                        'Pagination',
                        'WProofreader',
                        'MathType',
                        'SlashCommand',
                        'Template',
                        'DocumentOutline',
                        'FormatPainter',
                        'TableOfContents',
                        'PasteFromOfficeEnhanced'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });


            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('soal.store') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#simpan').attr('disabled', 'disabled');
                        $('#simpan').text('Proses...');
                    },
                    complete: function() {
                        $('#simpan').removeAttr('disabled');
                        $('#simpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.persyaratan) {
                                $('#persyaratan').addClass('is-invalid');
                                $('.errorPersyaratan').html(response.errors.persyaratan);
                            } else {
                                $('#persyaratan').removeClass('is-invalid');
                                $('.errorPersyaratan').html('');
                            }

                            if (response.errors.keterangan) {
                                $('#keterangan').addClass('is-invalid');
                                $('.errorKeterangan').html(response.errors.keterangan);
                            } else {
                                $('#keterangan').removeClass('is-invalid');
                                $('.errorKeterangan').html('');
                            }

                            if (response.errors.bobot_nilai) {
                                $('#bobot_nilai').addClass('is-invalid');
                                $('.errorBobotNilai').html(response.errors.bobot_nilai);
                            } else {
                                $('#bobot_nilai').removeClass('is-invalid');
                                $('.errorBobotNilai').html('');
                            }

                            if (response.errors.golongan) {
                                $('#golongan').addClass('is-invalid');
                                $('.errorGolongan').html(response.errors.golongan);
                            } else {
                                $('#golongan').removeClass('is-invalid');
                                $('.errorGolongan').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('soal.index') }}";
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });
        })
    </script>
@endsection
