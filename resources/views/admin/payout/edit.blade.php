@extends('layouts.admin', [
    'second_title'    => 'Penyaluran',
    'header_title'    => 'Edit Penyaluran',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program_payout'
])

@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .ck-editor__editable {
            min-height: 120px;
        }

        .fs-8 {
            font-size: 8px;
        }

        .required:after {
            content: "*";
            color: red;
        }
    </style>
@endsection


@section('css_inline')
    <style type="text/css">
        .ck-editor__editable {
            min-height: 120px;
        }

        .fs-8 {
            font-size: 8px;
        }

        .required:after {
            content: "*";
            color: red;
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
                                    href="{{ route('adm.payout.index') }}">Penyaluran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Penyaluran</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">

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

            <form action="{{ route('adm.payout.update', $data->id) }}"
                method="post" enctype="multipart/form-data" accept-charset="utf-8" class="row gy-4">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <label class="form-label fw-semibold required">Pilih Program</label>
                    <select class="form-control form-control-sm @error('program_id') is-invalid @enderror" name="program_id"
                        id="program-select2" required>
                        <option value="{{ old('program_id', $data->program_id) }}" selected>
                            {{ $data->title }}</option>
                    </select>
                    @error('program_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Nominal Request</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah @error('nominal_request') is-invalid @enderror"
                            name="nominal_request" id="rupiah1" placeholder="100.000.000"
                            value="{{ old('nominal_request', number_format($data->nominal_request, 0, ',', '.')) }}" required>
                    </div>
                    @error('nominal_request')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Nominal Disetujui</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah @error('nominal_approved') is-invalid @enderror"
                            name="nominal_approved" id="rupiah2" placeholder="100.000.000"
                            value="{{ old('nominal_approved', number_format($data->nominal_approved, 0, ',', '.')) }}" required>
                    </div>
                    @error('nominal_approved')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Bank Fee</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah @error('bank_fee') is-invalid @enderror"
                            name="bank_fee" id="rupiah3" placeholder="0"
                            value="{{ old('bank_fee', number_format($data->bank_fee, 0, ',', '.')) }}">
                    </div>
                    @error('bank_fee')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Optimation Fee</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah @error('optimation_fee') is-invalid @enderror"
                            name="optimation_fee" id="rupiah4" placeholder="0"
                            value="{{ old('optimation_fee', number_format($data->optimation_fee, 0, ',', '.')) }}">
                    </div>
                    @error('optimation_fee')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Platform Fee</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah @error('platform_fee') is-invalid @enderror"
                            name="platform_fee" id="rupiah5" placeholder="0"
                            value="{{ old('platform_fee', number_format($data->platform_fee, 0, ',', '.')) }}">
                    </div>
                    @error('platform_fee')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ads Fee</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah @error('ads_fee') is-invalid @enderror"
                            name="ads_fee" id="rupiah6" placeholder="0"
                            value="{{ old('ads_fee', number_format($data->ads_fee, 0, ',', '.')) }}">
                    </div>
                    @error('ads_fee')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Tanggal Dibayar --}}
                <div class="col-6">
                    <label class="form-label fw-semibold required">Tanggal Dibayar</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="unpaid"
                                {{ $data->date_paid ? '' : 'checked' }}> Belum</span>
                        <input type="date" class="form-control form-control-sm @error('date_paid') is-invalid @enderror"
                            id="date_paid" name="date_paid"
                            value="{{ old('date_paid', \Carbon\Carbon::parse($data->date_paid)->format('Y-m-d')) }}"
                            {{ $data->date_paid ? '' : 'readonly' }}>
                    </div>
                    @error('date_paid')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Status Penyaluran --}}
                <div class="col-6">
                    <label class="form-label fw-semibold required">Status Penyaluran</label><br>

                    @php
                        $status = old('status', $data->status);
                    @endphp

                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status"
                            id="status_request" value="request" {{ $status == 'request' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_request">Diajukan</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status"
                            id="status_process" value="process" {{ $status == 'process' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_process">Diproses</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status"
                            id="status_paid" value="paid" {{ $status == 'paid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_paid">Sudah Dibayar</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('status') is-invalid @enderror" type="radio"
                            name="status" id="status_reject" value="reject" {{ $status == 'reject' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_reject">Ditolak</label>
                    </div>

                    @error('status')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <!-- START IMAGE IN CONTENT -->
                <div class="col-4">
                    <label class="form-label fw-semibold">Dokumen Pengajuan oleh Campaigner</label>
                    <div class="input-group">
                        <input type="file" name="file_submit"
                            class="form-control form-control-sm @error('file_submit') is-invalid @enderror"
                            id="file_submit" onchange="previewFile('file_submit', 'preview_container_submit')">
                    </div>
                    @error('file_submit')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                    <div id="preview_container_submit" class="mt-2">
                        @if($data->file_submit)
                            @php
                                $filePath = $data->file_submit;
                                $isImage = in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            @if($isImage)
                                <img src="{{ asset($filePath) }}" alt="Preview" style="width: 100%;" />
                            @else
                                <a href="{{ asset($filePath) }}" target="_blank">{{ basename($filePath) }}</a>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Bukti Dibayar oleh BaBe</label>
                    <div class="input-group">
                        <input type="file" name="file_paid"
                            class="form-control form-control-sm @error('file_paid') is-invalid @enderror" id="file_paid"
                            onchange="previewFile('file_paid', 'preview_container_paid')">
                    </div>
                    @error('file_paid')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                    <div id="preview_container_paid" class="mt-2">
                        @if($data->file_paid)
                            @php
                                $filePath = $data->file_paid;
                                $isImage = in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            @if($isImage)
                                <img src="{{ asset($filePath) }}" alt="Preview" style="width: 100%;" />
                            @else
                                <a href="{{ asset($filePath) }}" target="_blank">{{ basename($filePath) }}</a>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Bukti Terima Bantuan oleh Campaigner</label>
                    <div class="input-group">
                        <input type="file" name="file_accepted"
                            class="form-control form-control-sm @error('file_accepted') is-invalid @enderror"
                            id="file_accepted" onchange="previewFile('file_accepted', 'preview_container_accepted')">
                    </div>
                    @error('file_accepted')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                    <div id="preview_container_accepted" class="mt-2">
                        @if($data->file_accepted)
                            @php
                                $filePath = $data->file_accepted;
                                $isImage = in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            @if($isImage)
                                <img src="{{ asset($filePath) }}" alt="Preview" style="width: 100%;" />
                            @else
                                <a href="{{ asset($filePath) }}" target="_blank">{{ basename($filePath) }}</a>
                            @endif
                        @endif
                    </div>
                </div>
                <!-- END IMAGE IN CONTENT -->
                <div class="col-12">
                    <label class="form-label fw-semibold required">Keterangan</label>
                    <textarea class="form-control form-control-sm @error('desc_request') is-invalid @enderror" name="desc_request"
                        id="editor" rows="10">{{ old('desc_request', $data->desc_request) }}</textarea>
                    @error('desc_request')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="divider mb-0 mt-0"></div>
                </div>
                <div class="col-12 mt-3 text-end">
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
        referrerpolicy="origin">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('js_inline')
    <script type="text/javascript">
        function previewFile(inputId, previewContainerId) {
            const fileInput = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewContainerId);
            
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const fileUrl = URL.createObjectURL(file);

                // Clear previous preview
                previewContainer.innerHTML = '';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = fileUrl;
                    img.alt = 'Preview';
                    img.style.width = '100%';
                    img.classList.add('mt-2');
                    previewContainer.appendChild(img);
                } else {
                    const link = document.createElement('a');
                    link.href = fileUrl;
                    link.target = '_blank';
                    link.textContent = file.name;
                    link.classList.add('mt-2', 'd-block');
                    previewContainer.appendChild(link);
                }
            }
        }

        $("#unpaid").on("click", function() {
            if ($("#unpaid").is(':checked')) {
                document.getElementById('date_paid').readOnly = true;
                document.getElementById('date_paid').value = '';
            } else {
                document.getElementById('date_paid').removeAttribute('readonly');
            }
        });

        document.querySelectorAll('.rupiah').forEach(function(element) {
            element.addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value, '');
            });
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
            height: 500,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'image'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | ' +
                'removeformat | help | image',
            
            toolbar_sticky: true,
            toolbar_sticky_offset: 50,

            table_style_by_css: true,
            table_default_styles: {
                'border-collapse': 'collapse',
                'width': '100%',
                'margin': '15px 0'
            },
            table_default_attributes: {
                'border': '1'
            },
            table_appearance_options: {
                enabled: true,
                showHeaderOption: true,
                showBorderOption: true
            },
            table_cell_appearance_options: {
                enabled: true,
                borderColors: ['#34495e', '#3498db', '#e74c3c'],
                backgroundColors: ['#f8f9fa', '#e9ecef', '#dee2e6']
            },
            table_row_appearance_options: {
                enabled: true,
                backgroundColors: ['#f8f9fa', '#e9ecef', '#dee2e6']
            },

            contextmenu: 'paste | link image inserttable | cell row column deletetable',
            // image dimensions
            image_dimensions: false,
            image_advtab: true,
            image_caption: true,
            images_upload_url: "{{ route('adm.program-payout.image.content.submit') }}",

            file_picker_types: 'image',
            images_file_types: 'jpg,jpeg,png,gif,webp',

            table_appearance_options: {
                enabled: true,
                showHeaderOption: true,
                showBorderOption: true
            },

            images_upload_handler: function(blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('program_title', $('#program-select2 option:selected').text());

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{ route('adm.program-payout.image.content.submit') }}", true);
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

                        // Kembalikan URL langsung tanpa konversi ke lazyload
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
                    line-height: 1.6;
                }

                /* Style untuk gambar */
                img {
                    max-width: 100%;
                    height: auto;
                    display: block;
                    margin: 10px auto;
                }
                .img-responsive, .img-fluid {
                    max-width: 100%;
                    height: auto;
                }

                /* Style untuk tabel */
                table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 15px 0;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                }
                table, th, td {
                    border: 1px solid #ddd;
                }
                th {
                    background-color: #34495e;
                    color: white;
                    padding: 12px;
                    text-align: left;
                    font-weight: bold;
                }

                td {
                    padding: 10px 12px;
                    vertical-align: top;
                }
                tr:nth-child(even) {
                    background-color: #f8f9fa;
                }

                tr:hover {
                    background-color: #e9f7fe;
                }

                .mce-content-body {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 15px;
                }

                /* Style untuk toolbar sticky */
                .tox-tinymce--toolbar-sticky .tox-editor-header {
                    position: sticky;
                    top: 0;
                    z-index: 1000;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }

                table.content-table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 15px 0;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                }

                table.content-table, 
                table.content-table th, 
                table.content-table td {
                    border: 1px solid #34495e !important;
                }
            `,

            setup: function(editor) {
                // Nonaktifkan sementara konversi lazyload saat init
                editor.on('init', function() {
                    let content = editor.getContent();

                    // Perbaiki path gambar yang relatif
                    content = content.replace(
                        /src="(\.\.\/)+public\/images\/([^"]+)"/g,
                        'src="{{ url('/') }}/public/images/$2"'
                    );

                    content = content.replace(
                        /src="(\.\.\/)+images\/([^"]+)"/g,
                        'src="{{ url('/') }}/public/images/$2"'
                    );

                    // Untuk gambar baru, tampilkan langsung tanpa lazyload di editor
                    content = content.replace(
                        /<img[^>]+src="([^"]+)"[^>]*>/g,
                        function(match, src) {
                            return '<img class="img-responsive" src="' + src + '" alt="">';
                        }
                    );

                     editor.dom.addStyle(
                        'table { border-collapse: collapse; width: 100%; } ' +
                        'table, th, td { border: 1px solid #34495e; } ' +
                        'th { background-color: #34495e; color: white; }'
                    );

                    var tables = editor.getBody().getElementsByTagName('table');
                    for (var i = 0; i < tables.length; i++) {
                        editor.dom.addClass(tables[i], 'content-table');
                    }

                    editor.on('ExecCommand', function(e) {
                        if (e.command === 'mceInsertTable') {
                            setTimeout(function() {
                                var tables = editor.getBody().getElementsByTagName('table');
                                editor.dom.addClass(tables[tables.length - 1], 'content-table');
                            }, 100);
                        }
                    });

                    editor.on('SaveContent', function(e) {
                        var div = document.createElement('div');
                        div.innerHTML = e.content;
                        var tables = div.getElementsByTagName('table');
                                    
                        for (var i = 0; i < tables.length; i++) {
                            tables[i].className = 'content-table';
                            // Force inline styles sebagai fallback
                            tables[i].style.borderCollapse = 'collapse';
                            tables[i].style.width = '100%';
                            tables[i].style.margin = '15px 0';
                        }
                        e.content = div.innerHTML;
                    });

                    editor.setContent(content);
                });

                editor.on('NewRow', function(e) {
                    editor.dom.addClass(e.row.parentNode.parentNode, 'content-table');
                });
            
                editor.on('NewCell', function(e) {
                    var dom = editor.dom;
                    dom.setAttrib(e.cell, 'style', 'border-color: #34495e; padding: 8px;');
                });

                // Format lazyload hanya saat menyimpan
                editor.on('BeforeSetContent', function(e) {
                    if (e.content) {
                        // Pastikan path konsisten sebelum disimpan
                        e.content = e.content.replace(
                            new RegExp('src="{{ url('/') }}/public/images/([^"]+)"', 'g'),
                            'src="/public/images/$1"'
                        );
                    }
                });

                // Saat menyimpan, konversi ke format lazyload
                editor.on('SaveContent', function(e) {
                    e.content = e.content.replace(
                        /<img[^>]+src="([^"]+)"[^>]*>/g,
                        '<img class="lazyload" data-original="$1" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="">'
                    );
                });

                // Untuk konten yang ditampilkan di editor, gunakan gambar asli
                editor.on('PostProcess', function(e) {
                    if (!e.get) { // Hanya untuk konten yang ditampilkan, bukan yang disimpan

                        e.content = e.content.replace(
                            /<img([^>]+)class="[^"]*lazyload[^"]*"([^>]+)data-original="([^"]+)"([^>]*)>/g,
                            '<img$1class="img-fluid"$2src="$3"$4>'
                        );

                        e.content = e.content.replace(/<img([^>]+)(width|height)="[^"]*"/g, '<img$1');

                        // disable previous dimension size
                        // e.content = e.content.replace(
                        //     /<img[^>]+class="lazyload"[^>]+data-original="([^"]+)"[^>]*>/g,
                        //     '<img class="img-responsive" src="$1" alt="">'
                        // );
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var select2_query;
            $("#program-select2").select2({
                placeholder: 'Cari Program',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.program.select2.all') }}",
                    delay: 250,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    },
                    processResults: function (data, params) {
                        var items = $.map(data.data, function(obj){
                            obj.id = obj.id;
                            obj.text = obj.title;
                            return obj;
                        });
                        params.page = params.page || 1;
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.extra_data.last_page
                            }
                        };
                    },
                },
                templateResult: function (item) {
                    if (item.loading) {
                        return item.text;
                    }
                    var term = select2_query.term || '';
                    var $result = item.text;
                    return $result;
                },
                language: {
                    searching: function (params) {
                        select2_query = params;
                        return 'Searching...';
                    }
                }
            });
        });
    </script>

    <script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['status'] }}',
                title: '{{ session('message')['message'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
                },
                background: '{{ session('message')['status'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['status'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection
