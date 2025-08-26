@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .required:after {
            content:"*";
            color:red;
        }
    </style>
@endsection

@csrf
<div class="form-group">
    <label for="date" class="form-label fw-semibold required">Tanggal</label>
    <input type="date" name="date" id="date" class="form-control"
        value="{{ old('date', $programInfo->date ?? date('Y-m-d')) }}"  required>
</div>
<div class="form-group">
    <label for="program_id" class="form-label fw-semibold required">Program</label>
    <select name="program_id" id="program-select2" class="form-control" required>
        @isset($programInfo)
            <option value="{{ $programInfo->program_id }}" selected>{{ $programInfo->program->title }}</option>
        @endisset
    </select>
</div>
<div class="form-group">
    <label for="title" class="form-label fw-semibold required">Judul</label>
    <input type="text" name="title" id="title" class="form-control"
        value="{{ old('title', $programInfo->title ?? '') }}" placeholder="Contoh: Penyaluran Bantuan Tahap 1" required>
</div>
<div class="form-group">
    <label class="form-label fw-semibold">Konten</label>
    <textarea class="form-control form-control-sm w-100" name="content" id="editor" rows="5"
        style="min-width: 0;">{{ old('content', $programInfo->content ?? '') }}</textarea>
</div>
<div class="form-group">
    <label for="is_publish" class="form-label fw-semibold required">Status</label>
    <select name="is_publish" id="is_publish" class="form-control" required>
        <option value="1" {{ isset($programInfo) && $programInfo->is_publish == 1 ? 'selected' : '' }}>Publish
        </option>
        <option value="0" {{ isset($programInfo) && $programInfo->is_publish == 0 ? 'selected' : '' }}>Draft
        </option>
    </select>
</div>
<div class="col-12 mt-3 text-end">
    <input type="reset" class="btn btn-danger" value="Reset">
    <input type="submit" class="btn btn-info" value="Submit">
</div>

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/wphaz17bf6i1tsqq7cjt8t5w6r275bw3b8acq6u2gi4hnan4/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
@endsection

@section('js_inline')
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
            images_upload_url: "{{ route('adm.program-info.image.content.submit') }}",

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
                    xhr.open('POST', "{{ route('adm.program-info.image.content.submit') }}", true);
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
@endsection