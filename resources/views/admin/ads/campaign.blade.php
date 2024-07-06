@extends('layouts.admin', [
    'second_title'    => 'Campaign',
    'header_title'    => 'Campaign',
    'sidebar_menu'    => 'ads',
    'sidebar_submenu' => 'campaign'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('css_inline')
    <style type="text/css">
        .big-checkbox .form-check-input {
            width: 16px;
            height: 16px;
            margin-top: 3px !important;
        }
        .big-checkbox .form-check-label {
            margin-left: 6px;
        }
        .big-checkbox {
            min-height: auto !important;
        }
        .btn-xs {
            padding: 3px !important;
            font-size: 13px !important;
        }
    </style>
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Campaign</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-10 fc-rtl">
                    <a href="{{ route('adm.ads.get.new.campaign').'?id=1' }}" target="_blank" class="btn btn-outline-primary">Get Campaign BM1</a>
                    <a href="{{ route('adm.ads.get.new.campaign').'?id=4' }}" target="_blank" class="btn btn-outline-primary">Get Campaign BM4</a>
                    <button class="btn btn-outline-primary filter_payment" id="filter-bni" data-id="bni">Aktif</button>
                    <button class="btn btn-outline-primary filter_payment" id="filter-bsi" data-id="bsi">Ada Program</button>
                    <button class="btn btn-outline-primary" id="filter-fu">Winning</button>
                    <button class="btn btn-outline-primary" id="filter-1day">Splittest</button>
                    <button class="btn btn-primary" id="filter-5day">Luaran</button>
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_donate"><i class="fa fa-sync"></i> Refresh</button>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-12 form-inline">
                    <span>Filter :</span>
                    <input type="text" id="donatur_name" placeholder="Nama Donatur" class="form-control form-control-sm me-1 ms-2"> 
                    <input type="text" id="donatur_telp" placeholder="Telp Donatur ex: 8574..." class="form-control form-control-sm me-1"> 
                    <input type="text" id="filter_nominal" placeholder="Nominal" class="form-control form-control-sm me-1"> 
                    <input type="text" id="donatur_title" placeholder="Judul Program" class="form-control form-control-sm me-1">
                    <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama Campaign</th>
                        <th>Program</th>
                        <th>Tgl Buat - REF</th>
                        <th>Donasi & Spent</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="last_donate" value="">
    <input type="hidden" id="fu_val" value="0">
    <input type="hidden" id="1day_val" value="0">
    <input type="hidden" id="5day_val" value="1">
    <input type="hidden" id="bni_val" value="0">
    <input type="hidden" id="bsi_val" value="0">
    <input type="hidden" id="bri_val" value="0">
    <input type="hidden" id="qris_val" value="0">
    <input type="hidden" id="mandiri_val" value="0">
    <input type="hidden" id="gopay_val" value="0">
@endsection


@section('content_modal')
    <!-- Modal Edit Status -->
    <div class="modal fade" id="modal_status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitle">Modal title</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4">
                <input type="radio" class="btn-check" name="status" id="status_draft" autocomplete="off" value="draft">
                <label class="btn btn-outline-primary" for="status_draft">Belum Dibayar</label>
                <input type="radio" class="btn-check" name="status" id="status_paid" autocomplete="off" value="success">
                <label class="btn btn-outline-success" for="status_paid">Sudah Dibayar</label>
                <input type="radio" class="btn-check" name="status" id="status_cancel" autocomplete="off" value="cancel">
                <label class="btn btn-outline-danger" for="status_cancel">Dibatalkan</label>
                <div class="mt-2 " style="width: 68%; margin: auto;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">RP</span>
                        </div>
                        <input class="form-control form-control-sm" id="rupiah" name="amount" placeholder="0" type="text" value=""/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="form-check big-checkbox mb-0 ms-1">
                                    <input class="form-check-input" type="checkbox" value="" id="checkgenap">
                                    <label class="form-check-label" for="checkgenap"> Genapkan</label>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="sendwa" checked role="switch" id="checkboxwa">
                    <label class="form-check-label" for="checkboxwa">Kirim WA Terimakasih?</label>
                </div>
            </div>
            <div class="modal-footer pt-2 pb-2">
                <input type="hidden" id="id_trans" name="id_trans" value="">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="save_status">Simpan</button>
            </div>
        </div>
      </div>
    </div>

    <!-- Modal FU Paid -->
    <div class="modal fade" id="modal_fu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitleFu">Modal title</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4">
                <h5 class="mb-3">Kirim WA Dengan?</h5>
                <input type="radio" class="btn-check" name="fu_name" id="fu_asli" autocomplete="off" value="asli" checked>
                <label class="btn btn-outline-primary" for="fu_asli">Sebut Nama Asli</label>
                <input type="radio" class="btn-check" name="fu_name" id="fu_anda" autocomplete="off" value="anda">
                <label class="btn btn-outline-success" for="fu_anda">Sebut Dengan Anda</label>
                <div>Kalau Nama tidak baik disebut di Wa maka pilih <strong>Sebut Dengan Anda</strong></div>
            </div>
            <div class="modal-footer pt-2 pb-2">
                <input type="hidden" id="id_trans_fu" name="id_trans_fu" value="">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="save_fu">Kirim WA</button>
            </div>
        </div>
      </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">    
    function editStatus(id, status, nominal) {
        $("#id_trans").val(id);
        $("#rupiah").val(nominal.replace('Rp. ', ''));
        if(status=='draft') {
            document.getElementById("status_draft").disabled = true;
            document.getElementById("status_draft").checked = true;
            var status_show = 'BELUM DIBAYAR';
        } else if(status=='success') {
            document.getElementById("status_paid").disabled = true;
            document.getElementById("status_paid").checked = true;
            var status_show = 'SUDAH DIBAYAR';
        } else {
            document.getElementById("status_cancel").disabled = true;
            document.getElementById("status_cancel").checked = true;
            var status_show = 'DIBATALKAN';
        }
        
        document.getElementById("checkgenap").checked = false;
        $("#modalTitle").html(nominal+' - '+status_show);
        let myModal = new bootstrap.Modal(document.getElementById('modal_status'));
        myModal.show();
    }

    var need_fu = $('#fu_val').val();
    var day5    = $('#5day_val').val();
    var day1    = $('#1day_val').val();

    $("#filter-5day").on("click", function(){
        let fil_5day    = $('#5day_val').val();
        var need_fu     = $('#fu_val').val();
        var fil_1day    = $('#1day_val').val();
        var fil_bni     = $('#bni_val').val();
        var fil_bsi     = $('#bsi_val').val();
        var fil_bri     = $('#bri_val').val();
        var fil_qris    = $('#qris_val').val();
        var fil_gopay   = $('#gopay_val').val();
        var fil_mandiri = $('#mandiri_val').val();
        if(fil_5day==1) {       // before click or want to change become 5 day off
            $('#filter-5day-icon').removeClass('fa-check');
            $('#filter-5day-icon').addClass('fa-filter');
            $('#filter-5day').removeClass('btn-primary');
            $('#filter-5day').addClass('btn-outline-primary');
            $('#5day_val').val(0);            
            // donate_table(need_fu, fil_1day, 0, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
            donate_table();
        } else {                // want to change become 5 day on
            $('#filter-5day-icon').removeClass('fa-filter');
            $('#filter-5day-icon').addClass('fa-check');
            $('#filter-5day').removeClass('btn-outline-primary');
            $('#filter-5day').addClass('btn-primary');
            $('#5day_val').val(1);
            // 1 day or yesterday button
            $('#1day_val').val(0);
            $('#filter-1day-icon').removeClass('fa-check');
            $('#filter-1day-icon').addClass('fa-filter');
            $('#filter-1day').removeClass('btn-primary');
            $('#filter-1day').addClass('btn-outline-primary');
            // donate_table(need_fu, 0, 1, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
            donate_table();
        }
    });

    $("#filter-1day").on("click", function(){
        let fil_1day    = $('#1day_val').val();
        let fil_5day    = $('#5day_val').val();
        var need_fu     = $('#fu_val').val();
        var fil_bni     = $('#bni_val').val();
        var fil_bsi     = $('#bsi_val').val();
        var fil_bri     = $('#bri_val').val();
        var fil_qris    = $('#qris_val').val();
        var fil_gopay   = $('#gopay_val').val();
        var fil_mandiri = $('#mandiri_val').val();
        if(fil_1day==1) {       // before click or want to change become 1 day off
            $('#filter-5day-icon').removeClass('fa-check');
            $('#filter-5day-icon').addClass('fa-filter');
            $('#filter-5day').removeClass('btn-primary');
            $('#filter-5day').addClass('btn-outline-primary');
            $('#5day_val').val(0);
            // donate_table(need_fu, 0, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
            donate_table();
        } else {                // want to change become 1 day on
            $('#filter-1day-icon').removeClass('fa-filter');
            $('#filter-1day-icon').addClass('fa-check');
            $('#filter-1day').removeClass('btn-outline-primary');
            $('#filter-1day').addClass('btn-primary');
            $('#1day_val').val(1);
            // 5 day or yesterday button
            $('#5day_val').val(0);
            $('#filter-5day-icon').removeClass('fa-check');
            $('#filter-5day-icon').addClass('fa-filter');
            $('#filter-5day').removeClass('btn-primary');
            $('#filter-5day').addClass('btn-outline-primary');
            // donate_table(need_fu, 1, 0, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
            donate_table();
        }
    });

    $("#filter-fu").on("click", function(){
        let fil_5day    = $('#5day_val').val();
        let fil_1day    = $('#1day_val').val();
        var need_fu     = $('#fu_val').val();
        var fil_bni     = $('#bni_val').val();
        var fil_bsi     = $('#bsi_val').val();
        var fil_bri     = $('#bri_val').val();
        var fil_qris    = $('#qris_val').val();
        var fil_gopay   = $('#gopay_val').val();
        var fil_mandiri = $('#mandiri_val').val();
        if(need_fu==0) {
            $('#filter-fu-icon').removeClass('fa-filter');
            $('#filter-fu-icon').addClass('fa-check');
            $('#filter-fu').removeClass('btn-outline-primary');
            $('#filter-fu').addClass('btn-primary');
            $('#fu_val').val(1);
            // donate_table(1, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
            donate_table();
        } else {
            $('#filter-fu-icon').removeClass('fa-check');
            $('#filter-fu-icon').addClass('fa-filter');
            $('#filter-fu').removeClass('btn-primary');
            $('#filter-fu').addClass('btn-outline-primary');
            $('#fu_val').val(0);
            // donate_table(0, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
            donate_table();
        }
    });

    $(".filter_payment").on("click", function(){
        let fil_5day    = $('#5day_val').val();
        let fil_1day    = $('#1day_val').val();
        var need_fu     = $('#fu_val').val();
        var fil_bni     = $('#bni_val').val();
        var fil_bsi     = $('#bsi_val').val();
        var fil_bri     = $('#bri_val').val();
        var fil_qris    = $('#qris_val').val();
        var fil_gopay   = $('#gopay_val').val();
        var fil_mandiri = $('#mandiri_val').val();
        var fil_payment = $(this).attr("data-id");

        if(fil_payment=='bni') {
            if(fil_bni==0) {
                $('#filter-bni').removeClass('btn-outline-primary');
                $('#filter-bni').addClass('btn-primary');
                $('#bni_val').val(1);
                // donate_table(need_fu, fil_1day, fil_5day, 1, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            } else {
                $('#filter-bni').addClass('btn-outline-primary');
                $('#filter-bni').removeClass('btn-primary');
                $('#bni_val').val(0);
                // donate_table(need_fu, fil_1day, fil_5day, 0, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            }
        } else if(fil_payment=='bsi') {
            if(fil_bsi==0) {
                $('#filter-bsi').removeClass('btn-outline-primary');
                $('#filter-bsi').addClass('btn-primary');
                $('#bsi_val').val(1);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, 1, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            } else {
                $('#filter-bsi').addClass('btn-outline-primary');
                $('#filter-bsi').removeClass('btn-primary');
                $('#bsi_val').val(0);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, 0, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            }
        } else if(fil_payment=='bri') {
            if(fil_bri==0) {
                $('#filter-bri').removeClass('btn-outline-primary');
                $('#filter-bri').addClass('btn-primary');
                $('#bri_val').val(1);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, 1, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            } else {
                $('#filter-bri').addClass('btn-outline-primary');
                $('#filter-bri').removeClass('btn-primary');
                $('#bri_val').val(0);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, 0, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            }
        } else if(fil_payment=='qris') {
            if(fil_qris==0) {
                $('#filter-qris').removeClass('btn-outline-primary');
                $('#filter-qris').addClass('btn-primary');
                $('#qris_val').val(1);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, 1, fil_gopay, fil_mandiri);
                donate_table();
            } else {
                $('#filter-qris').addClass('btn-outline-primary');
                $('#filter-qris').removeClass('btn-primary');
                $('#qris_val').val(0);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, 0, fil_gopay, fil_mandiri);
                donate_table();
            }
        } else if(fil_payment=='gopay') {
            if(fil_gopay==0) {
                $('#filter-gopay').removeClass('btn-outline-primary');
                $('#filter-gopay').addClass('btn-primary');
                $('#gopay_val').val(1);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, 1, fil_mandiri);
                donate_table();
            } else {
                $('#filter-gopay').addClass('btn-outline-primary');
                $('#filter-gopay').removeClass('btn-primary');
                $('#gopay_val').val(0);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, 0, fil_mandiri);/
                donate_table();
            }
        } else { // mandiri
            if(fil_mandiri==0) {
                $('#filter-mandiri').removeClass('btn-outline-primary');
                $('#filter-mandiri').addClass('btn-primary');
                $('#mandiri_val').val(1);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, 1);
                donate_table();
            } else {
                $('#filter-mandiri').addClass('btn-outline-primary');
                $('#filter-mandiri').removeClass('btn-primary');
                $('#mandiri_val').val(0);
                // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, 0);
                donate_table();
            }
        }
    });

    function hideFunc(name) {
        // const truck_modal = document.querySelector('#modal_status');
        const truck_modal = document.querySelector(name);
        const modal = bootstrap.Modal.getInstance(truck_modal);    
        modal.hide();
    }


    $("#filter_search").on("click", function(){
        donate_table();
    });

    // function donate_table(need_fu_ar, day1_ar, day5_ar, bni_ar, bsi_ar, bri_ar, qris_ar, gopay_ar, mandiri_ar) {
    function donate_table() {
        let day5_ar        = $('#5day_val').val();
        let day1_ar        = $('#1day_val').val();
        let need_fu_ar     = $('#fu_val').val();
        let bni_ar         = $('#bni_val').val();
        let bsi_ar         = $('#bsi_val').val();
        let bri_ar         = $('#bri_val').val();
        let qris_ar        = $('#qris_val').val();
        let gopay_ar       = $('#gopay_val').val();
        let mandiri_ar     = $('#mandiri_val').val();

        let donatur_name   = $('#donatur_name').val();
        let donatur_telp   = $('#donatur_telp').val();
        let filter_nominal = $('#filter_nominal').val();
        let donatur_title  = $('#donatur_title').val();

        table.ajax.url("{{ route('adm.donate.datatables') }}/?need_fu="+need_fu_ar+"&day1="+day1_ar+"&day5="+day5_ar+"&bni="+bni_ar+"&bsi="+bsi_ar+"&bri="+bri_ar+"&qris="+qris_ar+"&gopay="+gopay_ar+"&mandiri="+mandiri_ar+"&donatur_name="+encodeURI(donatur_name)+"&donatur_telp="+donatur_telp+"&filter_nominal="+filter_nominal+"&donatur_title="+encodeURI(donatur_title)).load();
    }
    
    var table = $('#table-donatur').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: "{{ route('adm.ads.campaign.datatables') }}/?need_fu="+need_fu+"&day1="+day1+"&day5="+day5,
        "columnDefs": [
            { "width": "27%", "targets": 0 },
            { "width": "37%", "targets": 1 },
            { "width": "14%", "targets": 2 },
            { "width": "14%", "targets": 3 },
            { "width": "8%", "targets": 4 },
            { "orderable": false, "targets": 4 },
            { "searchable": false, "targets": 4 },
        ],
        columns: [
            {data: 'name', name: 'name'},
            {data: 'program', name: 'program'},
            {data: 'start_time', name: 'start_time'},
            {data: 'spend', name: 'spend'},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
        ]
    });
    $('#table-donatur thead tr').clone(true).appendTo( '#table-donatur thead' );
    $('#table-donatur tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control form-control-sm" placeholder="Search '+title+'" />' );
    
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    });

    $("#refresh_table_donate").on("click", function() {
        table.ajax.reload();
    });

</script>
@endsection
