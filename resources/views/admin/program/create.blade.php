@extends('layouts.admin', [
    'second_title' => 'Program',
    'header_title' => 'Tambah Program',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'program',
])


@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0 pl-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{ route('adm.program.index') }}">Program</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary" href={{ route('adm.program.index') }}>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            <form action="{{ route('adm.program.store') }}" method="post" enctype="multipart/form-data"
                accept-charset="utf-8" class="row gy-3">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul Program (max 75 karakter) - <span id="count_title"
                            class="fw-normal"></span></label>
                    <input type="text" class="form-control form-control-sm" name="title" id="program_title"
                        placeholder="Masukkan Judul Program" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">URL Program (<span class="" id="status_url">Belum
                            Dicek</span>)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="edit_url"> Edit</span>
                        <span class="input-group-text">{{ url('/') }}/</span>
                        <input type="text" class="form-control" name="url" placeholder="bangunmasjidmandangin"
                            id="url" value="{{ old('url') }}" readonly>
                        <span class="input-group-text p-0"><button class="btn btn-sm btn-info" id="cek_url"
                                type="button">Cek & Lanjut</button></span>
                    </div>
                </div>

                <div class="divider mt-4"></div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Kategori (boleh lebih dari 1 kategori)</label>
                    <select class="form-control form-control-sm" name="category[]" id="kategori-select2" required></select>
                    @error('category')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Lembaga</label>
                    <select class="form-control form-control-sm" name="organization" id="lembaga-select2" required></select>
                    @error('organization')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Nominal Pengajuan</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control" name="nominal" id="rupiah" placeholder="100.000.000"
                            value="{{ old('nominal') }}" required>
                        @error('nominal')
                            <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Tanggal Berakhir</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="forever_checked">
                            Selamanya</span>
                        <input type="date" class="form-control form-control-sm" id="forever" min="{{ date('Y-m-d') }}"
                            name="date_end" value="{{ old('date') }}" required>
                        @error('date_end')
                            <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Status Tampil</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_biasa" value="1"
                            {{ old('show') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_biasa">Tampil Biasa</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_pilihan"
                            value="2" {{ old('show') == '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_pilihan">Tampil Pilihan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_terbaru"
                            value="3" {{ old('show') == '3' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_terbaru">Tampil Terbaru</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_sembunyikan"
                            value="4" {{ old('show') == '4' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_sembunyikan">Sembunyikan</label>
                    </div>
                    @error('show')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Cerita Singkat (caption)</label>
                    <input type="text" class="form-control form-control-sm" name="caption"
                        placeholder="Yuk bantu warga desa Meranti untuk memiliki masjid satu-satunya." required
                        value="{{ old('caption') }}">
                    @error('caption')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="divider mt-4"></div>

                <div class="col-6">
                    <label class="form-label fw-semibold">Gambar Utama (600 x 320 px)</label>
                    <input type="file" class="form-control form-control-sm" name="img_primary" required>
                    {{-- start image preview --}}
                    <img id="primary_image_preview" src="" class="mt-2 img-preview w-100">
                    {{-- end image preview --}}
                    <div class="d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="same_as_thumbnail"
                                name="same_as_thumbnail">

                            <label class="form-check-label mt-1" for="same_as_thumbnail">
                                Gambar utama sama dengan thumbnail
                            </label>
                        </div>
                    </div>
                    @error('img_primary')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-6">
                    <label class="form-label fw-semibold">Thumbnail (292 x 156 px)</label>
                    <input type="file" class="form-control form-control-sm" name="thumbnail" required>
                    {{-- start image preview --}}
                    <img id="thumbnail_image_preview" src="" class="mt-2 img-preview w-100">
                    {{-- end image preview --}}
                    @error('thumbnail')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="divider mt-4"></div>

                <!-- START IMAGE IN CONTENT -->
                {{-- <div class="col-4">
                    <label class="form-label fw-semibold">Gambar Dalam Konten 1 (580 x ~ px)</label>
                    <div class="input-group">
                        <input type="file" name="img_content1" class="form-control form-control-sm"
                            placeholder="Recipient's username" aria-label="Recipient's username"
                            aria-describedby="button-addon1" id="img1">
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                            id="button-addon1">Upload</button>
                    </div>
                    <div class="row">
                        <div class="col-10 fs-8" id="link_img1">no image</div>
                        <div class="col-2 text-end"><a href="#" id="copy_img1"><i class="fa fa-copy"></i></a>
                        </div>
                        <input type="hidden" name="link_img1" id="full_img1" value="">
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Gambar Dalam Konten 2 (580 x ~ px)</label>
                    <div class="input-group">
                        <input type="file" name="img_content2" class="form-control form-control-sm"
                            placeholder="Recipient's username" aria-label="Recipient's username"
                            aria-describedby="button-addon2" id="img2">
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                            id="button-addon2">Upload</button>
                    </div>
                    <div class="row">
                        <div class="col-10 fs-8" id="link_img2">no image</div>
                        <div class="col-2 text-end"><a href="#" id="copy_img2"><i class="fa fa-copy"></i></a>
                        </div>
                        <input type="hidden" name="link_img2" id="full_img2" value="">
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Gambar Dalam Konten 3 (580 x ~ px)</label>
                    <div class="input-group">
                        <input type="file" name="img_content3" class="form-control form-control-sm"
                            placeholder="Recipient's username" aria-label="Recipient's username"
                            aria-describedby="button-addon3" id="img3">
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                            id="button-addon3">Upload</button>
                    </div>
                    <div class="row">
                        <div class="col-10 fs-8" id="link_img3">no image</div>
                        <div class="col-2 text-end"><a href="#" id="copy_img3"><i class="fa fa-copy"></i></a>
                        </div>
                        <input type="hidden" name="link_img3" id="full_img3" value="">
                    </div>
                </div> --}}

                <div class="d-flex justify-content-center">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Cerita Lengkap (min 3 paragraf dan 1 foto)</label>
                        <textarea class="form-control form-control-sm w-100" name="story" id="editor" rows="12"
                            style="min-width: 0;">{{ old('story') }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="divider mb-2 mt-2"></div>
                </div>
                <div class="col-12 text-end">
                    <input type="reset" class="btn btn-danger" value="Reset">
                    <input type="submit" class="btn btn-info" value="Submit">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.tiny.cloud/1/wphaz17bf6i1tsqq7cjt8t5w6r275bw3b8acq6u2gi4hnan4/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#lembaga-select2").select2({
                placeholder: 'Cari Lembaga',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.organization.select2.all') }}",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data, params) {
                        var items = $.map(data.data, function(obj) {
                            let lembaga_name = obj.name;
                            obj.id = obj.id;
                            obj.text = `${lembaga_name}`;

                            return obj;
                        });
                        params.page = params.page || 1;

                        // console.log(items);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    },
                },
                templateResult: function(item) {
                    // console.log(item);
                    // No need to template the searching text
                    if (item.loading) {
                        return item.text;
                    }

                    var term = select2_query.term || '';
                    // var $result = markMatch(item.text, term);
                    var $result = item.text,
                        term;

                    return $result;
                },
                language: {
                    searching: function(params) {
                        // Intercept the query as it is happening
                        select2_query = params;

                        // Change this to be appropriate for your application
                        return 'Searching...';
                    }
                }
            });

            $("#kategori-select2").select2({
                placeholder: 'Cari Kategori Campaign',
                multiple: true,
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.category.select2.all') }}",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data, params) {
                        var items = $.map(data.data, function(obj) {
                            let kategori_name = obj.name;
                            obj.id = obj.id;
                            obj.text = `${kategori_name}`;

                            return obj;
                        });
                        params.page = params.page || 1;

                        // console.log(items);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    },
                },
                templateResult: function(item) {
                    // console.log(item);
                    // No need to template the searching text
                    if (item.loading) {
                        return item.text;
                    }

                    var term = select2_query.term || '';
                    // var $result = markMatch(item.text, term);
                    var $result = item.text,
                        term;

                    return $result;
                },
                language: {
                    searching: function(params) {
                        // Intercept the query as it is happening
                        select2_query = params;

                        // Change this to be appropriate for your application
                        return 'Searching...';
                    }
                }
            });
        });

        $(document).ready(function() {
            // Cek status awal checkbox
            toggleThumbnailInput();

            // Tambahkan event listener untuk perubahan checkbox
            $('#same_as_thumbnail').change(function() {
                toggleThumbnailInput();
            });

            function toggleThumbnailInput() {
                if ($('#same_as_thumbnail').is(':checked')) {
                    // Jika checkbox dicentang, sembunyikan input thumbnail
                    $('input[name="thumbnail"]').closest('.col-6').hide();
                    // Hapus required attribute jika ada
                    $('input[name="thumbnail"]').removeAttr('required');
                } else {
                    // Jika checkbox tidak dicentang, tampilkan input thumbnail
                    $('input[name="thumbnail"]').closest('.col-6').show();
                    // Tambahkan required attribute kembali
                    $('input[name="thumbnail"]').attr('required', 'required');
                }
            }

            $('input[name="img_primary"]').change(function(e) {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#primary_image_preview').attr('src', e.target.result).show();
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            $('input[name="thumbnail"]').change(function(e) {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#thumbnail_image_preview').attr('src', e.target.result).show();
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        $("#program_title").on("keyup change", function() {
            var title = $(this).val();
            var title = title.length;
            if (title > 75) {
                $("#count_title").html(title + ' / 75');
                $("#count_title").addClass('text-danger');
            } else {
                $("#count_title").html(title + ' / 75');
                $("#count_title").removeClass('text-warning');
            }
        });

        $("#program_title").on("blur", function() {
            var title = $(this).val();
            var title = title.toLowerCase();
            var title = title.replace(/[^a-zA-Z0-9 ]/g, '');
            var title = title.replace(/ /g, "-");
            var title = title.replace(/--/g, "-");
            var title = encodeURI(title);
            var title = title.replace(/[^a-zA-Z0-9-]/g, '');
            $("#url").val(title);
        });

        $("#cek_url").on("click", function() {
            var url_data = $('#url').val();

            $.ajax({
                url: "{{ route('adm.program.create.check_url') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    "url": url_data
                },
                success: function(data) {
                    if (data == 'valid') {
                        console.log(data);
                        $('#status_url').html('Valid');
                        $('#status_url').removeClass('text-danger');
                        $('#status_url').addClass('text-success');
                    } else {
                        console.log(data);
                        $('#status_url').html('Sudah Dipakai');
                        $('#status_url').removeClass('text-success');
                        $('#status_url').addClass('text-danger');
                    }

                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
        });

        $("#edit_url").on("click", function() {
            if ($("#edit_url").is(':checked')) {
                document.getElementById('url').removeAttribute('readonly');
            } else {
                document.getElementById('url').readOnly = true;
            }
        });

        $("#button-addon1").on("click", function() {
            imageContentUpload('img1');
            $(this).attr('disabled', 'disabled');
        });

        $("#button-addon2").on("click", function() {
            imageContentUpload('img2');
            $(this).attr('disabled', 'disabled');
        });

        $("#button-addon3").on("click", function() {
            imageContentUpload('img3');
            $(this).attr('disabled', 'disabled');
        });

        function imageContentUpload(img, editor = null) {
            var imgname = $('input[name=title]').val();
            var data = new FormData();

            // Handle both file input and editor upload
            var file = $('#' + img)[0] ? $('#' + img)[0].files[0] : img;
            data.append('file', file);
            data.append('name', imgname);
            data.append('number', img);

            $.ajax({
                url: "{{ route('adm.program.image.content.submit') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function(data) {
                    if (editor) {
                        // For CKEditor
                        editor.model.change(writer => {
                            const imageElement = writer.createElement('image', {
                                src: data.link
                            });
                            editor.model.insertContent(imageElement);
                        });
                    } else {
                        // For manual upload
                        $('#link_' + img).html(data.link);
                        $('#full_' + img).val(data.full);
                    }
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                    if (editor) {
                        editor.notification.showWarning('Upload gambar gagal', {
                            title: 'Error'
                        });
                    }
                }
            });
            return false;
        }

        $("#copy_img1").on("click", function() {
            navigator.clipboard.writeText($('#full_img1').val());
        });

        $("#copy_img2").on("click", function() {
            navigator.clipboard.writeText($('#full_img2').val());
        });

        $("#copy_img3").on("click", function() {
            navigator.clipboard.writeText($('#full_img3').val());
        });

        var rupiah = document.getElementById("rupiah");
        rupiah.addEventListener("keyup", function(e) {
            rupiah.value = formatRupiah(this.value, "");
        });

        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
        }
    </script>

    <script>
        tinymce.init({
            selector: 'textarea#editor',
            height: 1000,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'image', 'paste'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help | image',

            // Menambahkan menu konteks (klik kanan)
            contextmenu: 'paste | link image inserttable | cell row column deletetable',

            // Konfigurasi khusus untuk gambar
            image_dimensions: true,
            image_description: true,
            image_title: true,
            image_advtab: true,
            image_caption: true,

            images_upload_url: "{{ route('adm.program.image.content.submit') }}",
            images_upload_handler: function(blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{ route('adm.program.image.content.submit') }}", true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.upload.onprogress = function(e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }

                        const json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            reject('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        resolve(json.location);
                    };

                    xhr.onerror = function() {
                        reject('Image upload failed due to a XHR Transport error. Status: ' + xhr
                            .status);
                    };

                    xhr.send(formData);
                });
            },
            image_class_list: [{
                title: 'Responsive',
                value: 'img-responsive'
            }],
            content_style: `
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 16px;
            max-width: 100%;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px auto;
        }
        .img-responsive {
            max-width: 100%;
            height: auto;
        }
        .mce-content-body {
            max-width: 800px;
            margin: 0 auto;
        }
        `,
            setup: function(editor) {
                editor.on('init', function() {
                    this.getDoc().body.style.maxWidth = '800px';
                    this.getDoc().body.style.margin = '0 auto';
                });

                editor.on('SetContent', function() {
                    tinymce.activeEditor.dom.addClass(tinymce.activeEditor.dom.select('img'),
                        'img-responsive');
                });
            },
        });
    </script>
@endsection
