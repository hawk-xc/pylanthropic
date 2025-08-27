@extends('layouts.admin', [
    'second_title'    => 'Penyaluran',
    'header_title'    => isset($payout) ? 'Edit Penyaluran' : 'Tambah Penyaluran',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program_payout'
])


@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .ck-editor__editable {min-height: 120px;}
        .fs-8 {font-size: 8px;}
        .required:after {
            content:"*";
            color:red;
        }
    </style>
@endsection


@section('css_inline')
    <style type="text/css">
        .ck-editor__editable {min-height: 120px;}
        .fs-8 {font-size: 8px;}
        .required:after {
            content:"*";
            color:red;
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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('adm.payout.index') }}">Penyaluran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ isset($payout) ? 'Edit' : 'Tambah' }} Penyaluran</li>
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
            
            <form action="{{ isset($payout) ? route('adm.payout.update', $payout->id) : route('adm.payout.store') }}" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="row gy-4">
                @csrf
                @if(isset($payout))
                    @method('PUT')
                @endif
                <div class="col-12">
                    <label class="form-label fw-semibold required">Pilih Program</label>
                    <select class="form-control form-control-sm" name="program_id" id="program-select2" required>
                        @if(isset($payout))
                            <option value="{{ $payout->program_id }}" selected>{{ $payout->program->title }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Nominal Request</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah" name="nominal_request" id="rupiah1" placeholder="100.000.000" value="{{ old('nominal_request', $payout->nominal_request ?? '') }}" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Nominal Disetujui</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah" name="nominal_approved" id="rupiah2" placeholder="100.000.000" value="{{ old('nominal_approved', $payout->nominal_approved ?? '') }}" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Tanggal Dibayar</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="unpaid" {{ isset($payout) && $payout->date_paid ? '' : 'checked' }}> Belum</span>
                        <input type="date" class="form-control form-control-sm" id="date_paid" name="date_paid" value="{{ old('date_paid', isset($payout) ? \Carbon\Carbon::parse($payout->date_paid)->format('Y-m-d') : '') }}" {{ isset($payout) && $payout->date_paid ? '' : 'readonly' }}>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Status Penyaluran</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_biasa" value="request" {{ isset($payout) && $payout->status == 'request' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_biasa">Diajukan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_pilihan" value="process" {{ isset($payout) && $payout->status == 'process' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_pilihan">Diproses</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_terbaru" value="paid" {{ isset($payout) && $payout->status == 'paid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_terbaru">Sudah Dibayar</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_sembunyikan" value="reject" {{ isset($payout) && $payout->status == 'reject' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampil_sembunyikan">Ditolak</label>
                    </div>
                </div>
                <!-- START IMAGE IN CONTENT -->
                <div class="col-4">
                    <label class="form-label fw-semibold">Dokumen Pengajuan oleh Campaigner</label>
                    <div class="input-group">
                        <input type="file" name="file_submit" class="form-control form-control-sm" id="file_submit" onchange="previewImage('file_submit', 'preview_submit')">
                    </div>
                    <img id="preview_submit" src="{{ isset($payout) && $payout->file_submit ? asset('storage/'.$payout->file_submit) : '#' }}" alt="your image" class="mt-2" style="max-height: 200px;"/>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Bukti Dibayar oleh BaBe</label>
                    <div class="input-group">
                        <input type="file" name="file_paid" class="form-control form-control-sm" id="file_paid" onchange="previewImage('file_paid', 'preview_paid')">
                    </div>
                    <img id="preview_paid" src="{{ isset($payout) && $payout->file_paid ? asset('storage/'.$payout->file_paid) : '#' }}" alt="your image" class="mt-2" style="max-height: 200px;"/>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Bukti Terima Bantuan oleh Campaigner</label>
                    <div class="input-group">
                        <input type="file" name="file_accepted" class="form-control form-control-sm" id="file_accepted" onchange="previewImage('file_accepted', 'preview_accepted')">
                    </div>
                    <img id="preview_accepted" src="{{ isset($payout) && $payout->file_accepted ? asset('storage/'.$payout->file_accepted) : '#' }}" alt="your image" class="mt-2" style="max-height: 200px;"/>
                </div>
                <!-- END IMAGE IN CONTENT -->
                <div class="col-12">
                    <label class="form-label fw-semibold required">Keterangan</label>
                    <textarea class="form-control form-control-sm" name="desc_request" id="editor" rows="10">{{ old('desc_request', $payout->desc_request ?? '') }}</textarea>
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
    <script src="https://cdn.tiny.cloud/1/wphaz17bf6i1tsqq7cjt8t5w6r275bw3b8acq6u2gi4hnan4/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
    function previewImage(inputId, previewId) {
        const [file] = document.getElementById(inputId).files
        if (file) {
            document.getElementById(previewId).src = URL.createObjectURL(file)
        }
    }

    $(document).ready(function() {
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

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    var items = $.map(data.data, function(obj){
                        let program_name = obj.title;
                        obj.id = obj.id;
                        obj.text = `${program_name}`;

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
            templateResult: function (item) {
                // console.log(item);
                // No need to template the searching text
                if (item.loading) {
                    return item.text;
                }

                var term = select2_query.term || '';
                // var $result = markMatch(item.text, term);
                var $result = item.text, term;

                return $result;
            },
            language: {
                searching: function (params) {
                    // Intercept the query as it is happening
                    select2_query = params;

                    // Change this to be appropriate for your application
                    return 'Searching...';
                }
            }
        });

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
            images_upload_url: "{{ route('adm.program.image.content.submit') }}",

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
                    formData.append('program_title', document.getElementById('program_title').value);

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
    });

    $("#unpaid").on("click", function(){
        if ($("#unpaid").is(':checked')) {
            document.getElementById('date_paid').readOnly = true;
            document.getElementById('date_paid').value = '';
        } else {
            document.getElementById('date_paid').removeAttribute('readonly');
        }
    });


    var rupiah1 = document.getElementById("rupiah1");
    rupiah1.addEventListener("keyup", function(e) {
      rupiah1.value = formatRupiah(this.value, "");
    });

    var rupiah2 = document.getElementById("rupiah2");
    rupiah2.addEventListener("keyup", function(e) {
      rupiah2.value = formatRupiah(this.value, "");
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
@endsection
