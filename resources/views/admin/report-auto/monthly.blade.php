@extends('layouts.admin', [
    'second_title'    => 'Automation',
    'header_title'    => 'Monthly Automation',
    'sidebar_menu'    => 'automate',
    'sidebar_submenu' => 'automate'
])


@section('css_plugins')
    
@endsection


@section('content')
    <div class="main-card mb-3 pb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0 pl-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Monthly Automation</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-12"><label class="form-label fw-semibold">Akumulasi Donasi Donatur Bulanan</label></div>
                <div class="col-4">
                    <input type="date" name="date_donatur" class="form-control form-control-sm">
                </div>
                <div class="col-8 mb-2">
                    <button class="btn btn-sm btn-danger mr-2">Reset</button>
                    <button class="btn btn-sm btn-info mr-2">Get List Donatur</button>
                    <button class="btn btn-sm btn-success">Rangkum Donasi</button>
                </div>

                <div class="col-12 mt-4"><label class="form-label fw-semibold">Pencocokan Donasi dg Mutasi</label></div>
                <div class="col-4">
                    <input type="date" name="date_donatur" class="form-control form-control-sm">
                </div>
                <div class="col-8">
                    <button class="btn btn-sm btn-success">Lakukan Pencocokan</button>
                </div>
            </div>
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

    $("#button-addon1").on("click", function(){
        imageContentUpload('img1');
        $(this).attr('disabled', 'disabled');
    });

    $("#button-addon2").on("click", function(){
        imageContentUpload('img2');
        $(this).attr('disabled', 'disabled');
    });

    $("#button-addon3").on("click", function(){
        imageContentUpload('img3');
        $(this).attr('disabled', 'disabled');
    });

    function imageContentUpload(img) {
        var imgname =  $('input[name=title]').val();
        var data    = new FormData();

        data.append('file', $('#'+img)[0].files[0]);
        data.append('name', imgname);
        data.append('number', img);

        $.ajax({
            url: "{{ route('adm.program.image.content.submit') }}",
            type: "POST",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: data,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success:function(data){
                $('#link_'+img).html(data.link);
                $('#full_'+img).val(data.full);
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
        return false;
    }

    $("#copy_img1").on("click", function(){
        navigator.clipboard.writeText($('#full_img1').val());
    });

    $("#copy_img2").on("click", function(){
        navigator.clipboard.writeText($('#full_img2').val());
    });

    $("#copy_img3").on("click", function(){
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
@endsection
