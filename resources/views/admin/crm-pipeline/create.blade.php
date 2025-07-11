@extends('layouts.admin', [
    'second_title' => 'Tambah CRM Pipeline',
    'header_title' => 'Tambah CRM Pipeline',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'crm-leads',
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
                                    href="{{ route('adm.program.index') }}">CRM Leads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Pipeline</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary"
                        href={{ route('adm.crm-leads.index', ['leads' => request()->query('leads')]) }}>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            <form action="{{ route('adm.crm-pipeline.store') }}" method="post" accept-charset="utf-8" class="row gy-3">
                @csrf
                @php
                    $leadsQuery = strtolower(request()->query('leads'));
                @endphp

                <input type="hidden" id="leads-query" value="{{ $leadsQuery }}">
                <input type="hidden" name="leads_id" id="hidden-leads-id">

                <div class="col-12">
                    <label class="form-label">Nama Pipeline</label>
                    <input type="text" class="form-control" name="name" id="name"
                        placeholder="Masukkan Nama Pipeline" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label required">Pilih Leads</label>
                    <select class="form-control form-control-sm" name="leads_id" id="leads-select2" required></select>
                    @error('leads_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="percentage_deals" class="form-label">Percentage Deals</label>
                    <input type="number" class="form-control" id="percentage_deals" name="percentage_deals" min="1"
                        max="100" placeholder="Masukkan persentase deals disini" value="{{ old('percentage_deals') }}"
                        required>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan deskripsi">{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2 ml-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="status_is_active"
                            value="1" checked>
                        <label class="form-check-label" for="status_is_active">Status Pipeline (<span
                                id="_status">Aktif</span>)</label>
                        <input type="hidden" name="is_active_hidden" id="status_is_active_hidden" value="1">
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
            let leadsQuery = $('#leads-query').val(); // dari input hidden
            let leadsSelect = $("#leads-select2");

            leadsSelect.select2({
                placeholder: 'Cari Leads',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.crm-leads.list') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        var items = $.map(data.data, function(obj) {
                            obj.id = obj.id;
                            obj.text = obj.name;
                            return obj;
                        });
                        params.page = params.page || 1;

                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    }
                },
                templateResult: function(item) {
                    if (item.loading) return item.text;
                    return item.text;
                },
                language: {
                    searching: function(params) {
                        select2_query = params;
                        return 'Searching...';
                    }
                }
            });

            // Auto-select berdasarkan query jika tersedia
            if (leadsQuery) {
                $.ajax({
                    url: "{{ route('adm.crm-leads.list') }}",
                    data: {
                        search: leadsQuery
                    },
                    success: function(response) {
                        const matched = response.data.find(item => item.name.toLowerCase() ===
                            leadsQuery);
                        if (matched) {
                            // Tambahkan option dan pilih secara otomatis
                            let option = new Option(matched.name, matched.id, true, true);
                            leadsSelect.append(option).trigger('change');

                            // Salin id ke input hidden agar tetap terkirim
                            $('#hidden-leads-id').val(matched.id);

                            // Disable input agar tidak bisa diubah
                            leadsSelect.prop('disabled', true);
                        }
                    }
                });
            }

            $("#users-select2").select2({
                placeholder: 'Cari PIC',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.users.select2.all') }}",
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
                            let donatur_name = obj.name;
                            obj.id = obj.id;
                            obj.text = `${donatur_name}`
                            return obj;
                        });
                        params.page = params.page || 1
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
                        term
                    return $result;
                },
                language: {
                    searching: function(params) {
                        // Intercept the query as it is happening
                        select2_query = params
                        // Change this to be appropriate for your application
                        return 'Searching...';
                    }
                }
            });

            document.getElementById('is_potential').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('_status').textContent = 'Ya';
                    document.getElementById('is_potential_hidden').value = '1';
                } else {
                    document.getElementById('_status').textContent = 'Tidak';
                    document.getElementById('is_potential_hidden').value = '0';
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
                                var tables = editor.getBody().getElementsByTagName(
                                    'table');
                                editor.dom.addClass(tables[tables.length - 1],
                                    'content-table');
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
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['type'] }}',
                title: '{{ session('message')['text'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
                },
                background: '{{ session('message')['type'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['type'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection
