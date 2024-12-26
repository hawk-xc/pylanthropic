@extends('layouts.admin', [
    'second_title'    => 'Penyaluran',
    'header_title'    => 'Tambah Penyaluran',
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


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0 pl-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('adm.payout.index') }}">Penyaluran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Penyaluran</li>
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
            
            <form action="{{ route('adm.payout.store') }}" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="row gy-4">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-semibold required">Pilih Program</label>
                    <select class="form-control form-control-sm" name="program" id="program-select2" required></select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Nominal Request</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah" name="nominal_request" id="rupiah1" placeholder="100.000.000" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Nominal Disetujui</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" class="form-control rupiah" name="nominal_approved" id="rupiah2" placeholder="100.000.000" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Tanggal Dibayar</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="unpaid" checked> Belum</span>
                        <input type="date" class="form-control form-control-sm" id="date_paid" name="date_paid" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold required">Status Penyaluran</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_biasa" value="request">
                        <label class="form-check-label" for="tampil_biasa">Diajukan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_pilihan" value="process">
                        <label class="form-check-label" for="tampil_pilihan">Diproses</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_terbaru" value="paid">
                        <label class="form-check-label" for="tampil_terbaru">Sudah Dibayar</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" type="radio" id="tampil_sembunyikan" value="reject">
                        <label class="form-check-label" for="tampil_sembunyikan">Ditolak</label>
                    </div>
                </div>
                <!-- START IMAGE IN CONTENT -->
                <div class="col-4">
                    <label class="form-label fw-semibold">Dokumen Pengajuan oleh Campaigner</label>
                    <div class="input-group">
                        <input type="file" name="file_submit" class="form-control form-control-sm" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon1" id="file_submit">
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Bukti Dibayar oleh BaBe</label>
                    <div class="input-group">
                        <input type="file" name="file_paid" class="form-control form-control-sm" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2" id="file_paid">
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Bukti Terima Bantuan oleh Campaigner</label>
                    <div class="input-group">
                        <input type="file" name="file_accepted" class="form-control form-control-sm" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon3" id="file_accepted">
                    </div>
                </div>
                <!-- END IMAGE IN CONTENT -->
                <div class="col-12">
                    <label class="form-label fw-semibold required">Keterangan</label>
                    <textarea class="form-control form-control-sm" name="description" id="editor" rows="10"></textarea>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
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

        // CKEDITOR
        ClassicEditor.create( document.querySelector( '#editor' ) )
        .then( editor => {
                console.log( editor );
        } )
        .catch( error => {
                console.error( error );
        } );

    });

    $("#unpaid").on("click", function(){
        if ($("#unpaid").is(':checked')) {
            document.getElementById('date_paid').readOnly = true;
        } else {
            document.getElementById('date_paid').removeAttribute('readonly');
        }
    });


    var rupiah = document.getElementById("rupiah1");
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

    var rupiah2 = document.getElementById("rupiah2");
    rupiah2.addEventListener("keyup", function(e) {
      rupiah2.value = formatRupiah(this.value, "");
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
      var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah2 = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? "." : "";
        rupiah2 += separator + ribuan.join(".");
      }

      rupiah2 = split[1] != undefined ? rupiah2 + "," + split[1] : rupiah2;
      return prefix == undefined ? rupiah2 : rupiah2 ? "" + rupiah2 : "";
    }
</script>
@endsection
