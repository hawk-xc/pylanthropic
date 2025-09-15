@extends('layouts.admin', [
    'second_title'    => 'Transaksi',
    'header_title'    => 'Transaksi Donasi',
    'sidebar_menu'    => 'donate',
    'sidebar_submenu' => 'donate'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .dataTables_filter {
            display: none;
        }
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
    </style>
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Donatur</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 fc-rtl text-end">
                    <button class="btn btn-outline-primary" id="playButton"><i class="fa fa-volume-mute mr-1"></i> OFF</button>
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_donate"><i class="fa fa-sync"></i> Refresh</button>
                </div>
            </div>
            <div class="divider"></div>
            
            <div class="col-12">
                <div class="row">
                    <div class="col-12 text-end">
                        <button class="btn btn-outline-primary filter_payment" id="filter-bni" data-id="bni">BNI</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bsi" data-id="bsi">BSI</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bri" data-id="bri">BRI</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-qris" data-id="qris">QRIS</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-gopay" data-id="gopay">Gopay</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-mandiri" data-id="mandiri">Mandiri</button>
                        <button class="btn btn-outline-primary" id="filter-fu"><i class="fa fa-filter mr-1" id="filter-fu-icon"></i> Butuh FU</button>
                        <button class="btn btn-outline-primary" id="filter-1day"><i class="fa fa-filter mr-1" id="filter-1day-icon"></i> Show Kemarin</button>
                        <button class="btn btn-primary" id="filter-5day"><i class="fa fa-check mr-1" id="filter-5day-icon"></i> Show 5 Hari</button>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <select class="form-control form-control-sm" id="program_id" name="program_id"></select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <input type="text" id="donatur_name" placeholder="Nama Donatur" class="form-control form-control filter-input">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="donatur_telp" placeholder="Telp Donatur ex: 8574..." class="form-control form-control filter-input">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="filter_nominal" placeholder="Nominal" class="form-control form-control filter-input">
                    </div>
                    <div class="col-md-3">
                        <select id="status_filter" class="form-select form-select">
                            <option value="">Semua Status</option>
                            <option value="success">Success</option>
                            <option value="draft">Draft</option>
                            <option value="cancel">Cancel</option>
                        </select>
                    </div>
                </div>

                <!-- Reset button -->
                <div class="row mt-2">
                    <div class="col-12 text-end">
                        <button class="btn btn-sm btn-secondary" id="reset_filter">Reset Filter</button>
                    </div>
                </div>
            </div>

            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jml Donasi</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tgl Donasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="last_donate" value="{{ $last_donate }}">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        if(fil_5day==1) {       // before click or want to change become 5 day off
            $('#filter-5day-icon').removeClass('fa-check');
            $('#filter-5day-icon').addClass('fa-filter');
            $('#filter-5day').removeClass('btn-primary');
            $('#filter-5day').addClass('btn-outline-primary');
            $('#5day_val').val(0);            
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
            donate_table();
        }
    });

    $("#filter-1day").on("click", function(){
        let fil_1day    = $('#1day_val').val();
        if(fil_1day==1) {       // before click or want to change become 1 day off
            $('#filter-5day-icon').removeClass('fa-check');
            $('#filter-5day-icon').addClass('fa-filter');
            $('#filter-5day').removeClass('btn-primary');
            $('#filter-5day').addClass('btn-outline-primary');
            $('#5day_val').val(0);
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
            donate_table();
        }
    });

    $("#filter-fu").on("click", function(){
        var need_fu     = $('#fu_val').val();
        if(need_fu==0) {
            $('#filter-fu-icon').removeClass('fa-filter');
            $('#filter-fu-icon').addClass('fa-check');
            $('#filter-fu').removeClass('btn-outline-primary');
            $('#filter-fu').addClass('btn-primary');
            $('#fu_val').val(1);
            donate_table();
        } else {
            $('#filter-fu-icon').removeClass('fa-check');
            $('#filter-fu-icon').addClass('fa-filter');
            $('#filter-fu').removeClass('btn-primary');
            $('#filter-fu').addClass('btn-outline-primary');
            $('#fu_val').val(0);
            donate_table();
        }
    });

    $(".filter_payment").on("click", function(){
        var fil_payment = $(this).attr("data-id");
        var current_val = $('#'+fil_payment+'_val').val();

        if(current_val==0) {
            $('#filter-'+fil_payment).removeClass('btn-outline-primary').addClass('btn-primary');
            $('#'+fil_payment+'_val').val(1);
        } else {
            $('#filter-'+fil_payment).addClass('btn-outline-primary').removeClass('btn-primary');
            $('#'+fil_payment+'_val').val(0);
        }
        donate_table();
    });

    function hideFunc(name) {
        const truck_modal = document.querySelector(name);
        const modal = bootstrap.Modal.getInstance(truck_modal);    
        modal.hide();
    }

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
        let program_id     = $('#program_id').val();
        let status         = $('#status_filter').val();

        table.ajax.url("{{ route('adm.donate.datatables') }}/?need_fu="+need_fu_ar+"&day1="+day1_ar+"&day5="+day5_ar+"&bni="+bni_ar+"&bsi="+bsi_ar+"&bri="+bri_ar+"&qris="+qris_ar+"&gopay="+gopay_ar+"&mandiri="+mandiri_ar+"&donatur_name="+encodeURI(donatur_name)+"&donatur_telp="+donatur_telp+"&filter_nominal="+filter_nominal+"&program_id="+(program_id ? program_id : '')+"&status="+status).load();
    }
    
    var table = $('#table-donatur').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[4, 'desc']],
        ajax: "{{ route('adm.donate.datatables') }}/?need_fu="+need_fu+"&day1="+day1+"&day5="+day5,
        "columnDefs": [
            { "width": "21%", "targets": 0 },
            { "width": "14%", "targets": 1 },
            { "width": "30%", "targets": 2 },
            { "width": "16%", "targets": 3 },
            { "width": "14%", "targets": 4 },
            { "width": "5%", "targets": 5 },
            { "orderable": false, "targets": [1, 2, 3, 5] },
        ],
        columns: [
            {data: 'name', name: 'name'},
            {data: 'nominal_final', name: 'nominal_final'},
            {data: 'title', name: 'title'},
            {data: 'invoice', name: 'invoice'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    $("#refresh_table_donate").on("click", function() {
        table.ajax.reload();
    });

    $("#save_status").on("click", function(){
        var id_trans = $("#id_trans").val();
        var status   = $('input[name="status"]:checked').val();
        var nominal  = $('#rupiah').val();

        if(document.getElementById('checkboxwa').checked) {
            var sendwa = 1;
        } else {
            var sendwa = 0;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('adm.donate.status.edit') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id_trans": id_trans,
              "sendwa": sendwa,
              "status": status,
              "nominal": nominal
            },
            success: function(data){
                console.log(data);
                if(data.status=='success') {
                    let status_id = '#status_'+id_trans;
                    if(status=='draft') {
                        $(status_id).html(data.nominal+'<br><span class="badge badge-warning">BELUM DIBAYAR</span>');
                    } else if(status=='success') {
                        $(status_id).html(data.nominal+'<br><span class="badge badge-success">SUDAH DIBAYAR</span>');
                    } else {
                        $(status_id).html(data.nominal+'<br><span class="badge badge-secondary">DIBATALKAN</span>');
                    }

                    hideFunc('#modal_status');
                }
            }
        });
    });

    function fuPaid(id, name, nominal) {
        $("#modalTitleFu").html(name+" - "+nominal);
        $("#id_trans_fu").val(id);
        
        let myModal = new bootstrap.Modal(document.getElementById('modal_fu'));
        myModal.show();
    }

    $("#save_fu").on("click", function(){
        var id_trans = $("#id_trans_fu").val();
        var status   = $('input[name="fu_name"]:checked').val();

        $.ajax({
            type: "POST",
            url: "{{ route('adm.donate.fu.paid') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id_trans": id_trans,
              "status": status
            },
            success: function(data){
                console.log(data);
                if(data=='success') {
                    hideFunc('#modal_fu');
                    alert("Sudah dikirim");
                }
            }
        });
    });

    $('#playButton').on('click', () => {
        new Audio("{{ asset('public/audio/1.mp3') }}").play();
        document.querySelector('#playButton').innerHTML = '<i class="fa fa-volume-up mr-1"></i> ON';
    });

    function alarmNewDonate() {
        var last_donate = $('#last_donate').val();
        
        $.ajax({
            type: "POST",
            url: "{{ route('adm.donate.check.alarm') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "last_donate": last_donate
            },
            success: function(data){
                if(data.status=='ON') {
                    $('#last_donate').val(data.last_donate);
                    const playButton = document.getElementById('playButton');
                    const audio      = new Audio("{{ asset('public/audio/1.mp3') }}");
                    playButton.addEventListener('click', () => {
                        audio.play();
                    });
                    playButton.click();
                }
            }
        });
    }

    function openDeleteModal(transactionId) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Pilih jenis penghapusan:",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus Transaksi Saja',
            showDenyButton: true,
            denyButtonColor: '#ffc107',
            denyButtonText: `Hapus Transaksi & Donatur`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteTransaction(transactionId, 'transaction_only');
            } else if (result.isDenied) {
                deleteTransaction(transactionId, 'with_donatur');
            }
        });
    }

    function deleteTransaction(transactionId, deleteType) {
        $.ajax({
            url: '/adm/donate/' + transactionId,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'DELETE',
                delete_type: deleteType
            },
            success: function(response) {
                if(response.status === 'success') {
                    table.ajax.reload();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire(
                        'Gagal!',
                        response.message,
                        'error'
                    );
                }
            },
            error: function(xhr) {
                Swal.fire(
                    'Error!',
                    'Terjadi kesalahan. Silakan coba lagi.',
                    'error'
                );
            }
        });
    }

    $(document).ready(function(){
        setInterval( function () {
            table.ajax.reload(null, false); 
            alarmNewDonate();
        }, 250000 );

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        $('#program_id, #status_filter').on('change', function() {
            donate_table();
        });

        $('.filter-input').on('keyup', debounce(function() {
            donate_table();
        }, 500));

        $("#reset_filter").on("click", function(){
            $('.filter-input').val('');
            $('#program_id').val(null).trigger('change.select2');
            $('#status_filter').val('');

            $('#fu_val').val(0);
            $('#filter-fu-icon').addClass('fa-filter').removeClass('fa-check');
            $('#filter-fu').addClass('btn-outline-primary').removeClass('btn-primary');

            $('#1day_val').val(0);
            $('#filter-1day-icon').addClass('fa-filter').removeClass('fa-check');
            $('#filter-1day').addClass('btn-outline-primary').removeClass('btn-primary');

            $('#5day_val').val(1);
            $('#filter-5day-icon').addClass('fa-check').removeClass('fa-filter');
            $('#filter-5day').addClass('btn-primary').removeClass('btn-outline-primary');

            $('.filter_payment').addClass('btn-outline-primary').removeClass('btn-primary');
            $('#bni_val').val(0);
            $('#bsi_val').val(0);
            $('#bri_val').val(0);
            $('#qris_val').val(0);
            $('#gopay_val').val(0);
            $('#mandiri_val').val(0);

            donate_table();
        });

        var select2_query;
        $("#program_id").select2({
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


    $("#checkgenap").on("click", function(){
        let val_rupiah = $('#rupiah').val();
        val_rupiah     = val_rupiah.slice(0, -3)+'000';
        $('#rupiah').val(val_rupiah);
        $('#rupiah').val(formatRupiah(document.getElementById("rupiah").value, ""));
    });

    var rupiah = document.getElementById("rupiah");
    rupiah.addEventListener("keyup", function(e) {
      rupiah.value = formatRupiah(this.value, "");
    });

    function formatRupiah(angka, prefix) {
      var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
      }

      rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
      return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
    }
</script>
@endsection