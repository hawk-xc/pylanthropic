@extends('layouts.admin', [
    'second_title'    => 'Program',
    'header_title'    => 'Tambah Program',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program'
])


@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .ck-editor__editable {min-height: 340px;}
        .fs-8 {font-size: 8px;}
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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('adm.program.index') }}">Program</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    
                </div>
            </div>
            <div class="divider"></div>
            <form action="{{ route('adm.program.store') }}" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="row gy-3">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul Program (max 50 karakter)</label>
                    <input type="text" class="form-control form-control-sm" name="title" id="program_title">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">URL Program ({{ url('/').'/disini_url' }})</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="edit_url"> Edit</span>
                        <input type="text" class="form-control" name="url" placeholder="Username" id="url" readonly>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Kategori (boleh lebih dari 1 kategori)</label>
                    <select class="form-control form-control-sm" name="category[]" id="kategori-select2"></select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Lembaga</label>
                    <select class="form-control form-control-sm" name="organization" id="lembaga-select2"></select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Nominal Pengajuan</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control" name="nominal" id="rupiah" placeholder="100.000.000">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Tanggal Berakhir</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="forever_checked"> Selamanya</span>
                        <input type="date" class="form-control form-control-sm" id="forever" name="date_end">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Status Tampil</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="inlineCheckbox1" value="1">
                        <label class="form-check-label" for="inlineCheckbox1">Tampil Biasa</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="inlineCheckbox1" value="2">
                        <label class="form-check-label" for="inlineCheckbox1">Tampil Pilihan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="inlineCheckbox1" value="3">
                        <label class="form-check-label" for="inlineCheckbox1">Tampil Terbaru</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="show" type="radio" id="inlineCheckbox1" value="4">
                        <label class="form-check-label" for="inlineCheckbox1">Sembunyikan</label>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Thumbnail (292 x 156 px)</label>
                    <input type="file" class="form-control form-control-sm" name="thumbnail">
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Gambar Utama (600 x 320 px)</label>
                    <input type="file" class="form-control form-control-sm" name="img">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Cerita Singkat (caption)</label>
                    <input type="text" class="form-control form-control-sm" name="caption" placeholder="Yuk bantu warga desa Meranti untuk memiliki masjid satu-satunya.">
                </div>
                <!-- START IMAGE IN CONTENT -->
                <div class="col-4">
                    <label class="form-label fw-semibold">Gambar Dalam Konten 1 (580 x ~ px)</label>
                    <div class="input-group">
                        <input type="file" name="img_content1" class="form-control form-control-sm" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="button-addon2">Upload</button>
                    </div>
                    <div class="row">
                        <div class="col-10 fs-8">https://bantubersama.com/public/images/program/content/...</div>
                        <div class="col-2 text-end"><a href="#"><i class="fa fa-copy"></i></a></div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Gambar Dalam Konten 2 (580 x ~ px)</label>
                    <div class="input-group">
                        <input type="file" name="img_content2" class="form-control form-control-sm" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="button-addon2">Upload</button>
                    </div>
                    <div class="row">
                        <div class="col-10 fs-8">https://bantubersama.com/public/images/program/content/...</div>
                        <div class="col-2 text-end"><a href="#"><i class="fa fa-copy"></i></a></div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Gambar Dalam Konten 3 (580 x ~ px)</label>
                    <div class="input-group">
                        <input type="file" name="img_content3" class="form-control form-control-sm" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="button-addon2">Upload</button>
                    </div>
                    <div class="row">
                        <div class="col-10 fs-8">https://bantubersama.com/public/images/program/content/...</div>
                        <div class="col-2 text-end"><a href="#"><i class="fa fa-copy"></i></a></div>
                    </div>
                </div>
                <!-- END IMAGE IN CONTENT -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Cerita Lengkap (min 3 paragfar dan 1 foto)</label>
                    <textarea class="form-control form-control-sm" name="story" id="editor" row="12"></textarea>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
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

        $("#kategori-select2").select2({
            placeholder: 'Cari Kategori Campaign',
            multiple: true,
            theme: 'bootstrap-5',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.category.select2.all') }}",
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

        // CKEDITOR
        ClassicEditor.create( document.querySelector( '#editor' ) )
        .then( editor => {
                console.log( editor );
        } )
        .catch( error => {
                console.error( error );
        } );
    });

    $("#program_title").on("blur", function(){
        var title = $(this).val();
        var title = title.toLowerCase();
        var title = title.replace(/[^a-zA-Z0-9 ]/g, '');
        var title = title.replace(/ /g, "-");
        var title = title.replace(/--/g, "-");
        var title = encodeURI(title);
        var title = title.replace(/[^a-zA-Z0-9-]/g, '');
        $("#url").val(title);
    });

    $("#edit_url").on("click", function(){
        if ($("#edit_url").is(':checked')) {
            document.getElementById('url').removeAttribute('readonly');
        } else {
            document.getElementById('url').readOnly = true;
        }
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
@endsection
