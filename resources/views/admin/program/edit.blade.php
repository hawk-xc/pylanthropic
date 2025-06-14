@extends('layouts.admin', [
    'second_title' => 'Edit Program',
    'header_title' => 'Edit Program',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'program',
])


@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .ck-editor__editable {
            min-height: 340px;
        }

        .fs-8 {
            font-size: 8px;
        }
    </style>
@endsection


@section('css_inline')
    <style type="text/css">
        .img-preview {
            width: 280px;
            height: auto;
        }
    </style>
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
                            <li class="breadcrumb-item active" aria-current="page">Edit Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary" href={{ route('adm.program.index') }}>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>

            @if (session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger" id="error-alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('adm.program.update', $program->id) }}" method="POST" enctype="multipart/form-data"
                accept-charset="utf-8" class="row gy-3">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul Program (max 75 karakter) - <span id="count_title"
                            class="fw-normal"></span></label>
                    <input type="text" class="form-control form-control-sm" name="title" id="program_title"
                        value="{{ old('title', $program->title) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">URL Program (<span class="" id="status_url">Belum
                            Dicek</span>)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="edit_url"> Edit</span>
                        <span class="input-group-text">{{ url('/') }}/</span>
                        <input type="text" class="form-control" name="url" placeholder="bangunmasjidmandangin"
                            id="url" value="{{ old('slug', $program->slug) }}" readonly>
                        <span class="input-group-text p-0"><button class="btn btn-sm btn-info" id="cek_url"
                                type="button">Cek & Lanjut</button></span>
                    </div>
                    @error('thumbnail')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="divider mt-4"></div>

                <div class="col-6">
                    <label class="form-label fw-semibold">Lembaga</label>
                    <select class="form-control form-control-sm" name="organization" id="lembaga-select2" required>
                        <option value="{{ $program->organization_id }}">{{ $program->name }}</option>
                    </select>
                    @error('organization')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Nominal Pengajuan</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control" name="nominal" id="rupiah" placeholder="100.000.000"
                            value="{{ str_replace(',', '.', number_format(old('nominal', $program->nominal_approved))) }}"
                            required>
                        @error('nominal')
                            <div class="mt-2 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Tanggal Berakhir</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="forever_checked">
                            Selamanya</span>
                        <input type="date" class="form-control form-control-sm" id="forever" min="{{ date('Y-m-d') }}"
                            name="date_end" value="{{ date('Y-m-d', strtotime(old('date_end', $program->end_date))) }}"
                            required>
                        @error('date_end')
                            <div class="mt-2 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Status Tampil di :</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_pilihan" value="2"
                            {{ old('show', $program->is_show_home == 1 ? '2' : '') == '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_pilihan">Pilihan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_terbaru"
                            value="3"
                            {{ old('show', $program->is_recommended == 1 ? '3' : '') == '3' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_terbaru">Terbaru</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_mendesak"
                            value="5" {{ old('show', $program->is_urgent == 1 ? '5' : '') == '5' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_terbaru">Mendesak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_biasa" value="1"
                            {{ old('show', $program->is_publish == 1 && $program->is_recommended == 0 && $program->is_show_home == 0 && $program->is_urgent == 0 ? '1' : '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_biasa">Pencarian</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="tampil_sembunyikan"
                            value="4"
                            {{ old('show', $program->is_publish == 0 ? '4' : '') == '4' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_sembunyikan">Sembunyikan</label>
                    </div>
                    @error('show')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Cerita Singkat (caption)</label>
                    <input type="text" class="form-control form-control-sm" name="caption"
                        placeholder="Yuk bantu warga desa Meranti untuk memiliki masjid satu-satunya."
                        value="{{ old('caption', $program->short_desc) }}" required>
                    @error('caption')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="divider mt-4"></div>

                <div class="col-6">
                    <label class="form-label fw-semibold">Optimation Fee (%)</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" max="100" name="optimation_fee"
                            placeholder="Default 10" value="{{ old('optimation_fee', $program->optimation_fee) }}"
                            required>
                        <span class="input-group-text"> % </span>
                    </div>
                    @error('optimation_fee')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Menampilkan Pengurangan Berapa %</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" max="100" name="show_minus" placeholder="15"
                            value="{{ old('show_minus', $program->show_minus) }}" required>
                        <span class="input-group-text"> % </span>
                    </div>
                    @error('show_minus')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="divider mt-4"></div>

                <div class="col-6">
                    <label class="form-label fw-semibold">Gambar Utama (600 x 320 px)</label>
                    <input type="file" class="form-control form-control-sm" name="img_primary">
                    {{-- start image preview --}}
                    <img id="primary_image_preview" src="" class="mt-2 img-preview w-100">
                    {{-- end image preview --}}
                    @if (isset($program->image) && $program->image != '')
                        <img id="primary_image_main" src="{{ asset('public/images/program/' . $program->image) }}"
                            class="mt-2 img-preview w-100">
                    @else
                        <div class="mt-2 text-danger">Belum ada gambar utama</div>
                    @endif
                    <div class="d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="same_as_thumbnail"
                                name="same_as_thumbnail"
                                {{ old('same_as_thumbnail', $program->same_as_thumbnail) ? 'checked' : null }}>
                            <label class="form-check-label mt-1" for="same_as_thumbnail">
                                Gambar utama sama dengan thumbnail
                            </label>
                        </div>
                    </div>
                    @error('img_primary')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6">
                    <label class="form-label fw-semibold">Thumbnail (292 x 156 px)</label>
                    <input type="file" class="form-control form-control-sm" name="thumbnail">
                    {{-- start image preview --}}
                    <img id="thumbnail_image_preview" src="" class="mt-2 img-preview w-100">
                    {{-- end image preview --}}
                    @if (isset($program->thumbnail) && $program->thumbnail != '')
                        <img id="thumbnail_image_main" src="{{ asset('public/images/program/' . $program->thumbnail) }}"
                            class="mt-2 img-preview w-100">
                    @else
                        <div class="mt-2 text-danger">Belum ada thumbnail</div>
                    @endif
                    @error('thumbnail')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="divider mt-4"></div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Cerita Lengkap (min 3 paragfar dan 1 foto)</label>
                    <textarea class="form-control form-control-sm" name="story" id="editor" rows="12">
                        {{ old('story', $program->about) }}
                    </textarea>
                    @error('story')
                        <div class="mt-2 text-danger">{{ $message }}</div>
                    @enderror
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
                // Periksa apakah checkbox dicentang atau ada nilai old dari Laravel
                var isChecked = $('#same_as_thumbnail').is(':checked') || {{ old('same_as_thumbnail', 'false') }};

                if (isChecked) {
                    // Jika checkbox dicentang, sembunyikan input thumbnail
                    $('input[name="thumbnail"]').closest('.col-6').hide();
                    // Hapus required attribute jika ada
                    $('input[name="thumbnail"]').removeAttr('required');
                } else {
                    // Jika checkbox tidak dicentang, tampilkan input thumbnail
                    $('input[name="thumbnail"]').closest('.col-6').show();
                    // Tambahkan required attribute kembali
                    // $('input[name="thumbnail"]').attr('required', 'required');
                }
            }

            $('input[name="img_primary"]').change(function(e) {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#primary_image_preview').attr('src', e.target.result).show();
                        $('#primary_image_main').hide();
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            $('input[name="thumbnail"]').change(function(e) {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#thumbnail_image_preview').attr('src', e.target.result).show();
                        $('#thumbnail_image_main').hide();
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
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'image'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help | image',

            // Menambahkan menu konteks (klik kanan)
            contextmenu: 'paste | link image inserttable | cell row column deletetable',

            // Konfigurasi khusus untuk gambar
            image_dimensions: true, // Aktifkan pengaturan dimensi
            image_description: true, // Nonaktifkan deskripsi jika tidak diperlukan
            image_title: true, // Aktifkan title untuk gambar

            // Dialog gambar yang dimodifikasi
            image_advtab: true, // Aktifkan tab advanced untuk opsi lebih banyak
            image_caption: true, // Nonaktifkan caption jika tidak diperlukan

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
            // Konfigurasi untuk mengontrol gambar
            image_dimensions: false, // Nonaktifkan pengubahan dimensi otomatis
            image_class_list: [{
                title: 'Responsive',
                value: 'img-responsive'
            }],
            // Style untuk konten editor
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
                max-width: 800px; /* Sesuaikan dengan lebar maksimal yang Anda inginkan */
                margin: 0 auto;
            }
        `,
            setup: function(editor) {
                editor.on('init', function() {
                    // Perbaiki path gambar dan konversi lazyload ke src biasa untuk editor
                    let content = editor.getContent();

                    // 1. Perbaiki path gambar relatif
                    content = content.replace(
                        /src="(\.\.\/)+images\/([^"]+)"/g,
                        'src="{{ url('/') }}/images/$2"'
                    );

                    // 2. Konversi lazyload menjadi src biasa untuk keperluan editor
                    content = content.replace(
                        /<img[^>]+class="lazyload"[^>]+data-original="([^"]+)"[^>]*>/g,
                        function(match, src) {
                            return match.replace('class="lazyload"',
                                    'class="lazyload img-responsive"')
                                .replace('data-original="' + src + '"', 'src="' + src +
                                    '" data-original="' + src + '"');
                        }
                    );

                    editor.setContent(content);
                });

                // Sebelum disimpan, kembalikan ke format lazyload asli
                editor.on('BeforeSetContent', function(e) {
                    if (e.content) {
                        e.content = e.content.replace(
                            /<img[^>]+class="[^"]*lazyload[^"]*"[^>]+src="([^"]+)"[^>]*>/g,
                            '<img class="lazyload" data-original="$1" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="">'
                        );
                    }
                });
            }
        });
    </script>
@endsection
