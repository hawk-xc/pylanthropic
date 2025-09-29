@extends('layouts.admin', [
    'second_title' => 'Transaksi x Mutasi',
    'header_title' => 'Transaksi Donasi x Mutasi',
    'sidebar_menu' => 'donate_mutation',
    'sidebar_submenu' => 'donate_mutation',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
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

        table.dataTable thead tr th {
            word-wrap: break-word;
            word-break: break-all;
        }

        table.dataTable tbody tr td {
            word-wrap: break-word;
            word-break: break-all;
        }

        #table-donatur {
            width: 100% !important;
        }

        .grid-menu [class*=col-] {
            border-bottom: 0px;
        }

        .grid-menu [class*=col-]:nth-child(2n) {
            border-right-width: 1px;
        }

        .grid-menu [class*=col-]:nth-last-child(-n+1) {
            border-right-width: 0px !important;
        }

        .widget-chart {
            padding: 8px !important;
        }

        .widget-chart .widget-numbers {
            margin: 12px auto 14px auto;
        }

        .grid-menu .badge {
            padding: 2px 4px;
        }

        .copy_id_mutation {
            cursor: pointer;
            text-decoration: none;
        }
    </style>
@endsection


@section('content')
    <div class="row g-1">
        <div class="col-sm-12">
            <div class="main-card mb-2 card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 fc-rtl ps-1 pe-1">
                            <div class="grid-menu grid-menu-2col">
                                <div class="no-gutters row">
                                    <div class="col-md">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-success fs-5">
                                                Rp.<span
                                                    id="donate_paid_rp">{{ number_format($donate_today_paid_sum) }}</span>
                                                <span class="badge badge-pill badge-success" id="donate_paid_count">
                                                    {{ number_format($donate_today_paid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-numbers text-dark fs-5">
                                                Rp.<span
                                                    id="donate_unpaid_rp">{{ number_format($donate_today_unpaid_sum) }}</span>
                                                <span class="badge badge-pill badge-dark" id="donate_unpaid_count">
                                                    {{ number_format($donate_today_unpaid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-subheading">
                                                Rp. <span
                                                    id="sum_today">{{ number_format($donate_today_unpaid_sum + $donate_today_paid_sum) }}</span>
                                                <span class="badge badge-secondary"
                                                    id="count_today">{{ number_format($donate_today_unpaid_count + $donate_today_paid_count) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-success fs-5">
                                                Rp.<span
                                                    id="donate_paid_rp_yest1">{{ number_format($donate_yest1_paid_sum) }}</span>
                                                <span class="badge badge-pill badge-success" id="donate_paid_count_yest1">
                                                    {{ number_format($donate_yest1_paid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-numbers text-dark fs-5">
                                                Rp.<span
                                                    id="donate_unpaid_rp_yest1">{{ number_format($donate_yest1_unpaid_sum) }}</span>
                                                <span class="badge badge-pill badge-dark" id="donate_unpaid_count_yest1">
                                                    {{ number_format($donate_yest1_unpaid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-subheading">
                                                Rp. <span
                                                    id="sum_yest1">{{ number_format($donate_yest1_paid_sum + $donate_yest1_unpaid_sum) }}</span>
                                                <span class="badge badge-secondary"
                                                    id="count_yest1">{{ number_format($donate_yest1_paid_count + $donate_yest1_unpaid_count) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-success fs-5">
                                                Rp.<span
                                                    id="donate_paid_rp_yest2">{{ number_format($donate_yest2_paid_sum) }}</span>
                                                <span class="badge badge-pill badge-success" id="donate_paid_count_yest2">
                                                    {{ number_format($donate_yest2_paid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-numbers text-dark fs-5">
                                                Rp.<span
                                                    id="donate_unpaid_rp_yest2">{{ number_format($donate_yest2_unpaid_sum) }}</span>
                                                <span class="badge badge-pill badge-dark" id="donate_unpaid_count_yest2">
                                                    {{ number_format($donate_yest2_unpaid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-subheading">
                                                Rp. <span
                                                    id="sum_yest2">{{ number_format($donate_yest2_paid_sum + $donate_yest2_unpaid_sum) }}</span>
                                                <span class="badge badge-secondary"
                                                    id="count_yest2">{{ number_format($donate_yest2_paid_count + $donate_yest2_unpaid_count) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-success fs-5">
                                                Rp.<span
                                                    id="donate_paid_rp_yest2">{{ number_format($donate_yest3_paid_sum) }}</span>
                                                <span class="badge badge-pill badge-success" id="donate_paid_count_yest2">
                                                    {{ number_format($donate_yest3_paid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-numbers text-dark fs-5">
                                                Rp.<span
                                                    id="donate_unpaid_rp_yest2">{{ number_format($donate_yest3_unpaid_sum) }}</span>
                                                <span class="badge badge-pill badge-dark" id="donate_unpaid_count_yest2">
                                                    {{ number_format($donate_yest3_unpaid_count) }}
                                                </span>
                                            </div>
                                            <div class="widget-subheading">
                                                Rp. <span
                                                    id="sum_yest2">{{ number_format($donate_yest3_paid_sum + $donate_yest3_unpaid_sum) }}</span>
                                                <span class="badge badge-secondary"
                                                    id="count_yest2">{{ number_format($donate_yest3_paid_count + $donate_yest3_unpaid_count) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-dark fs-5">Rp. <span
                                                    id="paid_now">{{ str_replace(',', '.', number_format($sum_paid_now)) }}</span>
                                            </div>
                                            <div class="widget-numbers text-dark fs-5">Rp. <span
                                                    id="avg_paid_now">{{ str_replace(',', '.', number_format($sum_paid_now / date('d'))) }}</span>
                                            </div>
                                            <div class="widget-subheading">Rp. <span
                                                    id="all_paid">{{ str_replace(',', '.', number_format($sum_paid)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-sm-12 pe-0">
            <div class="main-card mb-3 card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 fc-rtl">
                            <button class="btn btn-sm btn-outline-primary" id="playButton"><i
                                    class="fa fa-volume-mute mr-1"></i> OFF</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-bca"
                                data-id="bca">BCA</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-bni"
                                data-id="bni">BNI</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-bsi"
                                data-id="bsi">BSI</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-bri"
                                data-id="bri">BRI</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-qris"
                                data-id="qris">QRIS</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-gopay"
                                data-id="gopay">Gopay</button>
                            <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-mandiri"
                                data-id="mandiri">Mandiri</button>
                            <div class="mt-1"></div>
                            <button class="btn btn-sm btn-outline-primary" id="filter-fu"><i class="fa fa-filter mr-1"
                                    id="filter-fu-icon"></i> Butuh FU</button>
                            <button class="btn btn-sm btn-outline-primary" id="filter-1day"><i class="fa fa-filter mr-1"
                                    id="filter-1day-icon"></i> Show Kemarin</button>
                            <button class="btn btn-sm btn-primary" id="filter-5day"><i class="fa fa-check mr-1"
                                    id="filter-5day-icon"></i> Show 5 Hari</button>
                            <button class="btn btn-sm btn-outline-primary" id="refresh_table_donate"><i
                                    class="fa fa-sync"></i> Refresh</button>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-12 form-inline">
                            <input type="text" id="donatur_name" placeholder="Nama Donatur"
                                class="form-control form-control-sm me-1 ms-2">
                            <input type="text" id="donatur_telp" placeholder="Telp Donatur ex: 8574..."
                                class="form-control form-control-sm me-1" style="width:160px">
                            <input type="text" id="filter_nominal" placeholder="Nominal"
                                class="form-control form-control-sm me-1" style="width:120px">
                            <input type="hidden" id="donatur_title" placeholder="Judul Program"
                                class="form-control form-control-sm me-1">
                            <input type="text" id="ref_code" placeholder="Ref Code"
                                class="form-control form-control-sm me-1" style="width:130px">
                            <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <table id="table-donatur" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jml Donasi</th>
                                <th>Staus</th>
                                <th>Tgl Donasi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="main-card mb-3 card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 fc-rtl">
                            <!-- <button class="btn btn-sm btn-outline-primary filter_mutation" id="filter-bca-mutation" data-id="bca">BCA</button> -->
                            <button class="btn btn-sm btn-outline-primary filter_mutation" id="filter-bca-mutation"
                                data-id="bni">BCA</button>
                            <button class="btn btn-sm btn-outline-primary filter_mutation" id="filter-bni-mutation"
                                data-id="bni">BNI</button>
                            <button class="btn btn-sm btn-outline-primary filter_mutation" id="filter-bsi-mutation"
                                data-id="bsi">BSI</button>
                            <button class="btn btn-sm btn-outline-primary filter_mutation" id="filter-bri-mutation"
                                data-id="bri">BRI</button>
                            <button class="btn btn-sm btn-outline-primary filter_mutation" id="filter-mandiri-mutation"
                                data-id="mandiri">Mandiri</button>
                            <button class="btn btn-sm btn-outline-primary" id="filter-notmatch-mutation">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                            <!-- <button class="btn btn-sm btn-outline-primary" id="filter-in-mutation"><i class="fa fa-filter mr-1" id="filter-in-icon"></i> In</button> -->
                            <div class="mt-1"></div>
                            <button class="btn btn-sm btn-outline-primary" id="filter-today-mutation"><i
                                    class="fa fa-filter mr-1" id="filter-1day-icon"></i> Today</button>
                            <button class="btn btn-sm btn-outline-primary" id="filter-1day-mutation"><i
                                    class="fa fa-filter mr-1" id="filter-1day-icon"></i> Kemarin</button>
                            <button class="btn btn-sm btn-primary" id="filter-5day"><i class="fa fa-check mr-1"
                                    id="filter-5day-mutation-icon"></i> 2 Hari</button>
                            <button class="btn btn-sm btn-outline-primary mr-1" id="refresh_table_donate_mutation"><i
                                    class="fa fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-12 form-inline">
                            <span>Filter :</span>
                            <input type="text" id="filter_nominal_mutation" placeholder="Nominal"
                                class="form-control form-control-sm me-1 ms-2">
                            <button class="btn btn-sm btn-primary" id="filter_search_mutation">Cari</button>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <table id="table-donatur-mutation" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Rp</th>
                                <th>Tanggal - Desc</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="last_donate" value="{{ $last_donate }}">
    <input type="hidden" id="fu_val" value="0">
    <input type="hidden" id="1day_val" value="0">
    <input type="hidden" id="5day_val" value="1">
    <input type="hidden" id="bca_val" value="0">
    <input type="hidden" id="bni_val" value="0">
    <input type="hidden" id="bsi_val" value="0">
    <input type="hidden" id="bri_val" value="0">
    <input type="hidden" id="qris_val" value="0">
    <input type="hidden" id="mandiri_val" value="0">
    <input type="hidden" id="gopay_val" value="0">

    <input type="hidden" id="mut_today" value="0">
    <input type="hidden" id="mut_day1" value="0">
    <input type="hidden" id="mut_day2" value="1">
    <input type="hidden" id="mut_notmatch" value="0">
    <input type="hidden" id="mut_bca" value="0">
    <input type="hidden" id="mut_bni" value="0">
    <input type="hidden" id="mut_bsi" value="0">
    <input type="hidden" id="mut_bri" value="0">
    <input type="hidden" id="mut_mandiri" value="0">
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
                    <input type="radio" class="btn-check" name="status" id="status_draft" autocomplete="off"
                        value="draft">
                    <label class="btn btn-outline-primary" for="status_draft">Belum Dibayar</label>
                    <input type="radio" class="btn-check" name="status" id="status_paid" autocomplete="off"
                        value="success">
                    <label class="btn btn-outline-success" for="status_paid">Sudah Dibayar</label>
                    <input type="radio" class="btn-check" name="status" id="status_cancel" autocomplete="off"
                        value="cancel">
                    <label class="btn btn-outline-danger" for="status_cancel">Dibatalkan</label>
                    <div class="mt-2 " style="width: 68%; margin: auto;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">RP</span>
                            </div>
                            <input class="form-control form-control-sm" id="rupiah" name="amount" placeholder="0"
                                type="text" value="" />
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
                    <div class="mt-2 " style="width: 68%; margin: auto;">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-sm" id="mutation_id" name="mutation_id"
                                placeholder="0" type="text" value="" />
                            <input class="btn btn-sm btn-info" type="button" id="paste_mutation_id"
                                value="Paste ID Mutasi">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="sendwa" checked role="switch"
                            id="checkboxwa">
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
                    <input type="radio" class="btn-check" name="fu_name" id="fu_asli" autocomplete="off"
                        value="asli" checked>
                    <label class="btn btn-outline-primary" for="fu_asli">Sebut Nama Asli</label>
                    <input type="radio" class="btn-check" name="fu_name" id="fu_anda" autocomplete="off"
                        value="anda">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        function editStatus(id, status, nominal) {
            $("#id_trans").val(id);
            $("#rupiah").val(nominal.replace('Rp. ', ''));
            if (status == 'draft') {
                document.getElementById("status_draft").disabled = true;
                document.getElementById("status_draft").checked = true;
                var status_show = 'BELUM DIBAYAR';
            } else if (status == 'success') {
                document.getElementById("status_paid").disabled = true;
                document.getElementById("status_paid").checked = true;
                var status_show = 'SUDAH DIBAYAR';
            } else {
                document.getElementById("status_cancel").disabled = true;
                document.getElementById("status_cancel").checked = true;
                var status_show = 'DIBATALKAN';
            }

            document.getElementById("checkgenap").checked = false;
            $("#modalTitle").html(nominal + ' - ' + status_show);
            let myModal = new bootstrap.Modal(document.getElementById('modal_status'));
            myModal.show();
        }

        var need_fu = $('#fu_val').val();
        var day5 = $('#5day_val').val();
        var day1 = $('#1day_val').val();

        var mut_today = $('#mut_today').val();
        var mut_day1 = $('#mut_day1').val();
        var mut_day2 = $('#mut_day2').val();
        var mut_notmatch = $('#mut_notmatch').val();
        var mut_bca = $('#mut_bca').val();
        var mut_bni = $('#mut_bni').val();
        var mut_bsi = $('#mut_bsi').val();
        var mut_bri = $('#mut_bri').val();
        var mut_mandiri = $('#mut_mandiri').val();
        var mut_nominal = $('#filter_nominal_mutation').val();

        $("#filter-5day").on("click", function() {
            let fil_5day = $('#5day_val').val();
            var need_fu = $('#fu_val').val();
            var fil_1day = $('#1day_val').val();
            var fil_bca = $('#bca_val').val();
            var fil_bni = $('#bni_val').val();
            var fil_bsi = $('#bsi_val').val();
            var fil_bri = $('#bri_val').val();
            var fil_qris = $('#qris_val').val();
            var fil_gopay = $('#gopay_val').val();
            var fil_mandiri = $('#mandiri_val').val();
            if (fil_5day == 1) { // before click or want to change become 5 day off
                $('#filter-5day-icon').removeClass('fa-check');
                $('#filter-5day-icon').addClass('fa-filter');
                $('#filter-5day').removeClass('btn-primary');
                $('#filter-5day').addClass('btn-outline-primary');
                $('#5day_val').val(0);
                // donate_table(need_fu, fil_1day, 0, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            } else { // want to change become 5 day on
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

        $("#filter-1day").on("click", function() {
            let fil_1day = $('#1day_val').val();
            let fil_5day = $('#5day_val').val();
            var need_fu = $('#fu_val').val();
            var fil_bca = $('#bca_val').val();
            var fil_bni = $('#bni_val').val();
            var fil_bsi = $('#bsi_val').val();
            var fil_bri = $('#bri_val').val();
            var fil_qris = $('#qris_val').val();
            var fil_gopay = $('#gopay_val').val();
            var fil_mandiri = $('#mandiri_val').val();
            if (fil_1day == 1) { // before click or want to change become 1 day off
                $('#filter-5day-icon').removeClass('fa-check');
                $('#filter-5day-icon').addClass('fa-filter');
                $('#filter-5day').removeClass('btn-primary');
                $('#filter-5day').addClass('btn-outline-primary');
                $('#5day_val').val(0);
                // donate_table(need_fu, 0, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                donate_table();
            } else { // want to change become 1 day on
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

        $("#filter-fu").on("click", function() {
            let fil_5day = $('#5day_val').val();
            let fil_1day = $('#1day_val').val();
            var need_fu = $('#fu_val').val();
            var fil_bca = $('#bca_val').val();
            var fil_bni = $('#bni_val').val();
            var fil_bsi = $('#bsi_val').val();
            var fil_bri = $('#bri_val').val();
            var fil_qris = $('#qris_val').val();
            var fil_gopay = $('#gopay_val').val();
            var fil_mandiri = $('#mandiri_val').val();
            if (need_fu == 0) {
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

        function resetFilterPayment() {
            $('.filter_payment').removeClass('btn-primary');
            $('.filter_payment').addClass('btn-outline-primary');
            $('#bca_val').val(0);
            $('#bni_val').val(0);
            $('#bsi_val').val(0);
            $('#bri_val').val(0);
            $('#qris_val').val(0);
            $('#gopay_val').val(0);
            $('#mandiri_val').val(0);
            donate_table();
        }

        function setFilterPayment(bank_type) {
            $('.filter_payment').removeClass('btn-primary');
            $('.filter_payment').addClass('btn-outline-primary');
            $('#bca_val').val(0);
            $('#bni_val').val(0);
            $('#bsi_val').val(0);
            $('#bri_val').val(0);
            $('#qris_val').val(0);
            $('#gopay_val').val(0);
            $('#mandiri_val').val(0);

            $('#filter-' + bank_type).removeClass('btn-outline-primary');
            $('#filter-' + bank_type).addClass('btn-primary');
            $('#' + bank_type + '_val').val(1);

            donate_table();
        }

        $(".filter_payment").on("click", function() {
            let fil_5day = $('#5day_val').val();
            let fil_1day = $('#1day_val').val();
            var need_fu = $('#fu_val').val();
            var fil_bca = $('#bca_val').val();
            var fil_bni = $('#bni_val').val();
            var fil_bsi = $('#bsi_val').val();
            var fil_bri = $('#bri_val').val();
            var fil_qris = $('#qris_val').val();
            var fil_gopay = $('#gopay_val').val();
            var fil_mandiri = $('#mandiri_val').val();
            var fil_payment = $(this).attr("data-id");

            if (fil_payment == 'bni') {
                if (fil_bni == 0) {
                    setFilterPayment('bni');
                } else {
                    resetFilterPayment();
                }
            } else if (fil_payment == 'bca') {
                if (fil_bca == 0) {
                    setFilterPayment('bca');
                } else {
                    resetFilterPayment();
                }
            } else if (fil_payment == 'bsi') {
                if (fil_bsi == 0) {
                    setFilterPayment('bsi');
                } else {
                    resetFilterPayment();
                }
            } else if (fil_payment == 'bri') {
                if (fil_bri == 0) {
                    setFilterPayment('bri');
                } else {
                    resetFilterPayment();
                }
            } else if (fil_payment == 'qris') {
                if (fil_qris == 0) {
                    setFilterPayment('qris');
                } else {
                    resetFilterPayment();
                }
            } else if (fil_payment == 'gopay') {
                if (fil_gopay == 0) {
                    setFilterPayment('gopay');
                } else {
                    resetFilterPayment();
                }
            } else { // mandiri
                if (fil_mandiri == 0) {
                    setFilterPayment('mandiri');
                } else {
                    resetFilterPayment();
                }
            }
        });



        function resetFilterMutation() {
            $('.filter_mutation').removeClass('btn-primary');
            $('.filter_mutation').addClass('btn-outline-primary');
            $('#mut_bca').val(0);
            $('#mut_bni').val(0);
            $('#mut_bsi').val(0);
            $('#mut_bri').val(0);
            $('#mut_mandiri').val(0);
            mutation_table();
        }

        function setFilterMutation(bank_type) {
            $('.filter_mutation').removeClass('btn-primary');
            $('.filter_mutation').addClass('btn-outline-primary');
            $('#mut_bca').val(0);
            $('#mut_bni').val(0);
            $('#mut_bsi').val(0);
            $('#mut_bri').val(0);
            $('#mut_mandiri').val(0);

            $('#filter-' + bank_type + '-mutation').removeClass('btn-outline-primary');
            $('#filter-' + bank_type + '-mutation').addClass('btn-primary');
            $('#mut_' + bank_type).val(1);

            mutation_table();
        }

        function copyIDMutation(id) {
            $('#mutation_id').val(id);
        }

        function addTrans(id) {
            var result = confirm("Yakin menambahkan ke donasi?");
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('adm.donate.auto_add') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "mutation_id": id
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            alert('BERHASIL, ' + data.nominal + ' ditambahkan ke transaksi donasi');
                        } else {
                            alert('GAGAL menambahkan ke transaksi donasi');
                        }
                    }
                });
            }
        }

        $(document).ready(function() {
            setInterval(function() {
                table.ajax.reload(); // reset paging
                table_mutation.ajax.reload(); // reset paging
                // table.ajax.reload(null, false);    // paging retained
                alarmNewDonate();
            }, 250000);
        });

        $(".filter_mutation").on("click", function() {
            let mut_today = $('#mut_today').val();
            let mut_day1 = $('#mut_day1').val();
            var mut_day2 = $('#mut_day2').val();
            var mut_notmatch = $('#mut_notmatch').val();

            var fil_bca = $('#mut_bca').val();
            var fil_bni = $('#mut_bni').val();
            var fil_bsi = $('#mut_bsi').val();
            var fil_bri = $('#mut_bri').val();
            var fil_mandiri = $('#mut_mandiri').val();
            var fil_payment = $(this).attr("data-id");

            if (fil_payment == 'bni') {
                if (fil_bni == 0) {
                    setFilterMutation('bni');
                } else {
                    resetFilterMutation();
                }
            } else if (fil_payment == 'bca') {
                if (fil_bca == 0) {
                    setFilterMutation('bca');
                } else {
                    resetFilterMutation();
                }
            } else if (fil_payment == 'bsi') {
                if (fil_bsi == 0) {
                    setFilterMutation('bsi');
                } else {
                    resetFilterMutation();
                }
            } else if (fil_payment == 'bri') {
                if (fil_bri == 0) {
                    setFilterMutation('bri');
                } else {
                    resetFilterMutation();
                }
            } else { // mandiri
                if (fil_mandiri == 0) {
                    setFilterMutation('mandiri');
                } else {
                    resetFilterMutation();
                }
            }
        });

        $("#filter_search_mutation").on("click", function() {
            mutation_table();
        });

        $("#refresh_table_donate_mutation").on("click", function() {
            table_mutation.ajax.reload();
        });

        function mutation_table() {
            let mut_today = $('#mut_today').val();
            let mut_day1 = $('#mut_day1').val();
            let mut_day2 = $('#mut_day2').val();
            let mut_notmatch = $('#mut_notmatch').val();

            let mut_bca = $('#mut_bca').val();
            let mut_bni = $('#mut_bni').val();
            let mut_bsi = $('#mut_bsi').val();
            let mut_bri = $('#mut_bri').val();
            let mut_mandiri = $('#mut_mandiri').val();
            let mut_nominal = $('#filter_nominal_mutation').val();

            table_mutation.ajax.url("{{ route('adm.donate.mutation.datatables') }}/?today=" + mut_today + "&day1=" +
                    mut_day1 + "&day2=" + mut_day2 + "&notmatch=" + mut_notmatch + "&bca=" + mut_bca + "&bni=" + mut_bni +
                    "&bsi=" + mut_bsi + "&bri=" + mut_bri + "&mandiri=" + mut_mandiri + "&filter_nominal=" + mut_nominal)
                .load();
        }

        $("#filter-notmatch-mutation").on("click", function() {
            var mut_notmatch = $('#mut_notmatch').val();

            if (mut_notmatch == 0) {
                $('#filter-notmatch-mutation').removeClass('btn-outline-primary');
                $('#filter-notmatch-mutation').addClass('btn-primary');
                $('#mut_notmatch').val(1);
                mutation_table();
            } else {
                $('#filter-notmatch-mutation').removeClass('btn-primary');
                $('#filter-notmatch-mutation').addClass('btn-outline-primary');
                $('#mut_notmatch').val(0);
                mutation_table();
            }
        });


        function hideFunc(name) {
            // const truck_modal = document.querySelector('#modal_status');
            const truck_modal = document.querySelector(name);
            const modal = bootstrap.Modal.getInstance(truck_modal);
            modal.hide();
        }


        $("#filter_search").on("click", function() {
            donate_table();
        });

        // function donate_table(need_fu_ar, day1_ar, day5_ar, bni_ar, bsi_ar, bri_ar, qris_ar, gopay_ar, mandiri_ar) {
        function donate_table() {
            let day5_ar = $('#5day_val').val();
            let day1_ar = $('#1day_val').val();
            let need_fu_ar = $('#fu_val').val();
            let bca_ar = $('#bca_val').val();
            let bni_ar = $('#bni_val').val();
            let bsi_ar = $('#bsi_val').val();
            let bri_ar = $('#bri_val').val();
            let qris_ar = $('#qris_val').val();
            let gopay_ar = $('#gopay_val').val();
            let mandiri_ar = $('#mandiri_val').val();

            let donatur_name = $('#donatur_name').val();
            let donatur_telp = $('#donatur_telp').val();
            let filter_nominal = $('#filter_nominal').val();
            let donatur_title = $('#donatur_title').val();
            let ref_code = $('#ref_code').val();

            table.ajax.url("{{ route('adm.donate.datatables') }}/?need_fu=" + need_fu_ar + "&day1=" + day1_ar + "&day5=" +
                day5_ar + "&bca=" + bca_ar + "&bni=" + bni_ar + "&bsi=" + bsi_ar + "&bri=" + bri_ar + "&qris=" +
                qris_ar + "&gopay=" + gopay_ar + "&mandiri=" + mandiri_ar + "&donatur_name=" + encodeURI(donatur_name) +
                "&donatur_telp=" + donatur_telp + "&filter_nominal=" + filter_nominal + "&donatur_title=" + encodeURI(
                    donatur_title) + "&ref_code=" + encodeURI(ref_code)).load();
        }

        function addCustomPagination(api) {
            let pageInfo = api.page.info();
            let paginate = $('#table-donatur_paginate .pagination');

            // Hapus input lama biar tidak duplikat
            paginate.find('.dt-page-input').remove();

            // Buat elemen input di tengah2 pagination
            let input = $(`
        <li class="paginate_button page-item dt-page-input">
            <input type="number" min="1" max="${pageInfo.pages}" 
                   value="${pageInfo.page + 1}" 
                   style="width:60px; text-align:center; height:38px" 
                   class="form-control form-control-sm" />
        </li>
    `);

            // Sisipkan input sebelum tombol Next
            paginate.find('.next').before(input);

            // Event: jika enter/ubah nilai
            input.find('input').off('change keyup').on('change keyup', function(e) {
                if (e.type === 'change' || e.key === 'Enter') {
                    let page = parseInt($(this).val(), 10) - 1;
                    if (!isNaN(page) && page >= 0 && page < pageInfo.pages) {
                        api.page(page).draw('page');
                    } else {
                        $(this).val(pageInfo.page + 1); // reset kalau invalid
                    }
                }
            });
        }

        var table = $('#table-donatur').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            order: [
                [3, 'desc']
            ],
            // dom: 'ftipr',
            language: {
                paginate: {
                    previous: "<",
                    next: ">"
                }
            },
            ajax: "{{ route('adm.donate.datatables') }}/?need_fu=" + need_fu + "&day1=" + day1 + "&day5=" + day5,
            "columnDefs": [{
                    "width": "33%",
                    "targets": 0
                },
                {
                    "width": "20%",
                    "targets": 1
                },
                {
                    "width": "24%",
                    "targets": 2
                },
                {
                    "width": "23%",
                    "targets": 3
                },
                {
                    "orderable": false,
                    "targets": 1
                },
                {
                    "orderable": false,
                    "targets": 2
                },
                // { "orderable": false, "targets": 3 },
            ],
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'nominal_final',
                    name: 'nominal_final'
                },
                {
                    data: 'invoice',
                    name: 'invoice'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],
            drawCallback: function() {
                addCustomPagination(this.api());
            }

        });
        $('#table-donatur thead tr').clone(true).appendTo('#table-donatur thead');
        $('#table-donatur tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title +
                '" />');

            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });

        $("#refresh_table_donate").on("click", function() {
            table.ajax.reload();
        });


        $.fn.DataTable.ext.pager.numbers_length = 7;
        var table_mutation = $('#table-donatur-mutation').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            responsive: false,
            lengthChange: false,
            ordering: false,
            // dom: 'Bfrtip',
            dom: 'ftipr',
            // initComplete: function( settings, json ) {
            //     $('.dataTables_paginate').addClass('btn btn-xs');
            // },
            language: {
                paginate: {
                    previous: "<",
                    next: ">"
                }
            },
            // order: [[1, 'desc']],
            autoWidth: false,
            ajax: "{{ route('adm.donate.mutation.datatables') }}/?/?today=" + mut_today + "&day1=" + mut_day1 +
                "&day2=" + mut_day2 + "&notmatch=" + mut_notmatch + "&bca=" + mut_bca + "&bni=" + mut_bni +
                "&bsi=" + mut_bsi + "&bri=" + mut_bri + "&mandiri=" + mut_mandiri + "&filter_nominal=" +
                mut_nominal,
            "columnDefs": [{
                    "width": "30%",
                    "targets": 0
                },
                {
                    "width": "70%",
                    "targets": 1
                },
                {
                    "orderable": false,
                    "targets": 0
                }
            ],
            columns: [{
                    data: 'nominal',
                    name: 'nominal'
                },
                {
                    data: 'date_desc',
                    name: 'date_desc'
                }
            ]
        });


        $("#save_status").on("click", function() {
            var id_trans = $("#id_trans").val();
            var status = $('input[name="status"]:checked').val();
            var nominal = $('#rupiah').val();
            var mutation_id = $('#mutation_id').val();

            if (document.getElementById('checkboxwa').checked) {
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
                    "nominal": nominal,
                    "mutation_id": mutation_id
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 'success') {
                        // toast success
                        let status_id = '#status_' + id_trans;
                        if (status == 'draft') {
                            $(status_id).html(data.nominal +
                                '<br><span class="badge badge-warning">BELUM DIBAYAR</span>');
                        } else if (status == 'success') {
                            $(status_id).html(data.nominal +
                                '<br><span class="badge badge-success">SUDAH DIBAYAR</span>');
                        } else {
                            $(status_id).html(data.nominal +
                                '<br><span class="badge badge-secondary">DIBATALKAN</span>');
                        }

                        hideFunc('#modal_status');
                    }
                }
            });

            $('#mutation_id').val('');
        });

        function fuPaid(id, name, nominal) {
            $("#modalTitleFu").html(name + " - " + nominal);
            $("#id_trans_fu").val(id);

            let myModal = new bootstrap.Modal(document.getElementById('modal_fu'));
            myModal.show();
        }

        $("#save_fu").on("click", function() {
            var id_trans = $("#id_trans_fu").val();
            var status = $('input[name="fu_name"]:checked').val();

            $.ajax({
                type: "POST",
                url: "{{ route('adm.donate.fu.paid') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id_trans": id_trans,
                    "status": status
                },
                success: function(data) {
                    console.log(data);
                    if (data == 'success') {
                        hideFunc('#modal_fu');
                        // toast success
                        alert("Sudah dikirim");
                    }
                }
            });
        });

        $('#playButton').on('click', () => {
            new Audio("{{ asset('public/audio/1.mp3') }}").play();
            document.querySelector('#playButton').innerHTML = '<i class="fa fa-volume-up mr-1"></i> ON';
        });

        // ALARM NEW DONATE
        function alarmNewDonate() {
            var last_donate = $('#last_donate').val();

            $.ajax({
                type: "POST",
                url: "{{ route('adm.donate.check.alarm') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "last_donate": last_donate
                },
                success: function(data) {
                    if (data.status == 'ON') {
                        $('#last_donate').val(data.last_donate);
                        const playButton = document.getElementById('playButton');
                        const audio = new Audio("{{ asset('public/audio/1.mp3') }}");
                        playButton.addEventListener('click', () => {
                            audio.play();
                        });
                        playButton.click();
                    }

                    $('#donate_paid_rp').html(data.paid_sum);
                    $('#donate_paid_count').html(data.paid_count);
                    $('#donate_unpaid_rp').html(data.unpaid_sum);
                    $('#donate_unpaid_count').html(data.unpaid_count);
                    $('#avg_paid_now').html(data.avg_paid_now);
                    $('#paid_now').html(data.paid_now);
                    $('#all_paid').html(data.all_paid);
                    $('#sum_today').html(data.sum_today);
                    $('#count_today').html(data.count_today);
                }
            });
        }

        $("#checkgenap").on("click", function() {
            let val_rupiah = $('#rupiah').val();
            val_rupiah = val_rupiah.slice(0, -3) + '000';
            $('#rupiah').val(val_rupiah);
            $('#rupiah').val(formatRupiah(document.getElementById("rupiah").value, ""));
        });

        var rupiah = document.getElementById("rupiah");
        rupiah.addEventListener("keyup", function(e) {
            // tambahkan 'Rp.' pada saat form di ketik
            // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
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
