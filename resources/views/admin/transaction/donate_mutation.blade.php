@extends('layouts.admin', [
    'second_title'    => 'Transaksi x Mutasi',
    'header_title'    => 'Transaksi Donasi x Mutasi',
    'sidebar_menu'    => 'donate_mutation',
    'sidebar_submenu' => 'donate_mutation'
])

@section('css_plugins')
<link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<style>
    .big-checkbox .form-check-input{width:16px;height:16px;margin-top:3px!important}
    .big-checkbox .form-check-label{margin-left:6px}
    .big-checkbox{min-height:auto!important}
    table.dataTable thead tr th,table.dataTable tbody tr td{word-wrap:break-word;word-break:break-all}
    #table-donatur{width:100%!important}
    .grid-menu [class*=col-]{border-bottom:0}
    .grid-menu [class*=col-]:nth-child(2n){border-right-width:1px}
    .grid-menu [class*=col-]:nth-last-child(-n+1){border-right-width:0!important}
    .widget-chart{padding:8px!important}
    .widget-chart .widget-numbers{margin:12px auto 14px auto}
    .grid-menu .badge{padding:2px 4px}
    .copy_id_mutation{cursor:pointer;text-decoration:none}
</style>
@endsection

@section('content')
<div class="row g-1">
    <div class="col-md-12">
        <div class="main-card mb-2 card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-12 fc-rtl ps-1 pe-1">
                        <div class="grid-menu grid-menu-2col">
                            <div class="no-gutters row">
                                <div class="col-md">
                                    <div class="widget-chart widget-chart-hover">
                                        <div class="widget-numbers text-success fs-5">
                                            Rp.<span id="donate_paid_rp">{{ number_format($donate_today_paid_sum) }}</span>
                                            <span class="badge badge-pill badge-success" id="donate_paid_count">{{ number_format($donate_today_paid_count) }}</span>
                                        </div>
                                        <div class="widget-numbers text-dark fs-5">
                                            Rp.<span id="donate_unpaid_rp">{{ number_format($donate_today_unpaid_sum) }}</span>
                                            <span class="badge badge-pill badge-dark" id="donate_unpaid_count">{{ number_format($donate_today_unpaid_count) }}</span>
                                        </div>
                                        <div class="widget-subheading">
                                            Rp. <span id="sum_today">{{ number_format($donate_today_unpaid_sum+$donate_today_paid_sum) }}</span>
                                            <span class="badge badge-secondary" id="count_today">{{ number_format($donate_today_unpaid_count+$donate_today_paid_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="widget-chart widget-chart-hover">
                                        <div class="widget-numbers text-success fs-5">
                                            Rp.<span id="donate_paid_rp_yest1">{{ number_format($donate_yest1_paid_sum) }}</span>
                                            <span class="badge badge-pill badge-success" id="donate_paid_count_yest1">{{ number_format($donate_yest1_paid_count) }}</span>
                                        </div>
                                        <div class="widget-numbers text-dark fs-5">
                                            Rp.<span id="donate_unpaid_rp_yest1">{{ number_format($donate_yest1_unpaid_sum) }}</span>
                                            <span class="badge badge-pill badge-dark" id="donate_unpaid_count_yest1">{{ number_format($donate_yest1_unpaid_count) }}</span>
                                        </div>
                                        <div class="widget-subheading">
                                            Rp. <span id="sum_yest1">{{ number_format($donate_yest1_paid_sum+$donate_yest1_unpaid_sum) }}</span>
                                            <span class="badge badge-secondary" id="count_yest1">{{ number_format($donate_yest1_paid_count+$donate_yest1_unpaid_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="widget-chart widget-chart-hover">
                                        <div class="widget-numbers text-success fs-5">
                                            Rp.<span id="donate_paid_rp_yest2">{{ number_format($donate_yest2_paid_sum) }}</span>
                                            <span class="badge badge-pill badge-success" id="donate_paid_count_yest2">{{ number_format($donate_yest2_paid_count) }}</span>
                                        </div>
                                        <div class="widget-numbers text-dark fs-5">
                                            Rp.<span id="donate_unpaid_rp_yest2">{{ number_format($donate_yest2_unpaid_sum) }}</span>
                                            <span class="badge badge-pill badge-dark" id="donate_unpaid_count_yest2">{{ number_format($donate_yest2_unpaid_count) }}</span>
                                        </div>
                                        <div class="widget-subheading">
                                            Rp. <span id="sum_yest2">{{ number_format($donate_yest2_paid_sum+$donate_yest2_unpaid_sum) }}</span>
                                            <span class="badge badge-secondary" id="count_yest2">{{ number_format($donate_yest2_paid_count+$donate_yest2_unpaid_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="widget-chart widget-chart-hover">
                                        <div class="widget-numbers text-success fs-5">
                                            Rp.<span id="donate_paid_rp_yest2">{{ number_format($donate_yest3_paid_sum) }}</span>
                                            <span class="badge badge-pill badge-success" id="donate_paid_count_yest2">{{ number_format($donate_yest3_paid_count) }}</span>
                                        </div>
                                        <div class="widget-numbers text-dark fs-5">
                                            Rp.<span id="donate_unpaid_rp_yest2">{{ number_format($donate_yest3_unpaid_sum) }}</span>
                                            <span class="badge badge-pill badge-dark" id="donate_unpaid_count_yest2">{{ number_format($donate_yest3_unpaid_count) }}</span>
                                        </div>
                                        <div class="widget-subheading">
                                            Rp. <span id="sum_yest2">{{ number_format($donate_yest3_paid_sum+$donate_yest3_unpaid_sum) }}</span>
                                            <span class="badge badge-secondary" id="count_yest2">{{ number_format($donate_yest3_paid_count+$donate_yest3_unpaid_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="widget-chart widget-chart-hover">
                                        <div class="widget-numbers text-dark fs-5">Rp. <span id="paid_now">{{ str_replace(',', '.', number_format($sum_paid_now)) }}</span></div>
                                        <div class="widget-numbers text-dark fs-5">Rp. <span id="avg_paid_now">{{ str_replace(',', '.', number_format($sum_paid_now/date('d'))) }}</span></div>
                                        <div class="widget-subheading">Rp. <span id="all_paid">{{ str_replace(',', '.', number_format($sum_paid)) }}</span></div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LEFT: TRANSAKSI --}}
    <div class="col-md-8 col-sm-12 pe-0">
        <div class="main-card mb-3 card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-12 fc-rtl">
                        <button class="btn btn-sm btn-outline-primary" id="playButton"><i class="fa fa-volume-mute mr-1"></i> OFF</button>

                        {{-- Filter Payment (exclusive group=payment) --}}
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#bca_val" data-value="1" id="filter-bca">BCA</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#bni_val" data-value="1" id="filter-bni">BNI</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#bsi_val" data-value="1" id="filter-bsi">BSI</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#bri_val" data-value="1" id="filter-bri">BRI</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#qris_val" data-value="1" id="filter-qris">QRIS</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#gopay_val" data-value="1" id="filter-gopay">Gopay</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="payment" data-target="#mandiri_val" data-value="1" id="filter-mandiri">Mandiri</button>

                        <div class="mt-1"></div>

                        {{-- Quick nominal (set #filter_nominal), exclusive within quick-nom group --}}
                        <button class="btn btn-sm btn-outline-primary quick-nom" data-value="1jt" id="filter-1jt"><i class="fa fa-filter mr-1"></i>>= 1jt</button>
                        <button class="btn btn-sm btn-outline-primary quick-nom" data-value="500k" id="filter-500k"><i class="fa fa-filter mr-1"></i>>= 500K</button>

                        {{-- FU toggle --}}
                        <button class="btn btn-sm btn-outline-primary btn-switch" data-target="#fu_val" data-toggle-value="1" id="filter-fu">
                            <i class="fa fa-filter mr-1" id="filter-fu-icon"></i> Butuh FU
                        </button>

                        {{-- Day range toggles (exclusive within range group) --}}
                        <button class="btn btn-sm btn-outline-primary btn-range" data-target="#1day_val" data-other="#5day_val" id="filter-1day">
                            <i class="fa fa-filter mr-1" id="filter-1day-icon"></i> Show Kemarin
                        </button>
                        <button class="btn btn-sm btn-primary btn-range active" data-target="#5day_val" data-other="#1day_val" id="filter-5day">
                            <i class="fa fa-check mr-1" id="filter-5day-icon"></i> Show 5 Hari
                        </button>

                        <button class="btn btn-sm btn-outline-primary" id="refresh_table_donate"><i class="fa fa-sync"></i> Refresh</button>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="row">
                    <div class="col-12 form-inline">
                        <input type="text" id="donatur_name" placeholder="Nama Donatur" class="form-control form-control-sm me-1 ms-2">
                        <input type="text" id="donatur_telp" placeholder="Telp Donatur ex: 8574..." class="form-control form-control-sm me-1" style="width:160px">
                        <input type="text" id="filter_nominal" placeholder="Nominal" class="form-control form-control-sm me-1" style="width:120px">
                        <input type="hidden" id="donatur_title" placeholder="Judul Program" class="form-control form-control-sm me-1">
                        <input type="text" id="ref_code" placeholder="Ref Code" class="form-control form-control-sm me-1" style="width:130px">
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
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT: MUTASI --}}
    <div class="col-md-4 col-sm-12">
        <div class="main-card mb-3 card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-12 fc-rtl">
                        {{-- Filter Mutasi (exclusive group=mutation) --}}
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="mutation" data-target="#mut_bca" data-value="1" id="filter-bca-mutation">BCA</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="mutation" data-target="#mut_bni" data-value="1" id="filter-bni-mutation">BNI</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="mutation" data-target="#mut_bsi" data-value="1" id="filter-bsi-mutation">BSI</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="mutation" data-target="#mut_bri" data-value="1" id="filter-bri-mutation">BRI</button>
                        <button class="btn btn-sm btn-outline-primary btn-toggle" data-group="mutation" data-target="#mut_mandiri" data-value="1" id="filter-mandiri-mutation">Mandiri</button>

                        {{-- Not match toggle --}}
                        <button class="btn btn-sm btn-outline-primary btn-switch" data-target="#mut_notmatch" data-toggle-value="1" id="filter-notmatch-mutation">
                            <i class="fa fa-eye-slash"></i>
                        </button>

                        <div class="mt-1"></div>

                        {{-- Range Mutasi (exclusive group=mutrange) --}}
                        <button class="btn btn-sm btn-outline-primary btn-mutr" data-target="#mut_today" data-other="#mut_day1,#mut_day2" id="filter-today-mutation"><i class="fa fa-filter mr-1"></i> Today</button>
                        <button class="btn btn-sm btn-outline-primary btn-mutr" data-target="#mut_day1" data-other="#mut_today,#mut_day2" id="filter-1day-mutation"><i class="fa fa-filter mr-1"></i> Kemarin</button>
                        <button class="btn btn-sm btn-primary btn-mutr active" data-target="#mut_day2" data-other="#mut_today,#mut_day1" id="filter-5day-mutation"><i class="fa fa-check mr-1"></i> 2 Hari</button>

                        <button class="btn btn-sm btn-outline-primary mr-1" id="refresh_table_donate_mutation"><i class="fa fa-sync"></i></button>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="row">
                    <div class="col-12 form-inline">
                        <span>Filter :</span>
                        <input type="text" id="filter_nominal_mutation" placeholder="Nominal" class="form-control form-control-sm me-1 ms-2">
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
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- Hidden states (tetap sama param & defaultnya) --}}
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
    {{-- Modal Status --}}
    <div class="modal fade" id="modal_status" tabindex="-1" aria-hidden="true">
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
                <div class="mt-2 " style="width:68%;margin:auto;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text">RP</span></div>
                        <input class="form-control form-control-sm" id="rupiah" name="amount" placeholder="0" type="text" value=""/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="form-check big-checkbox mb-0 ms-1">
                                    <input class="form-check-input" type="checkbox" id="checkgenap">
                                    <label class="form-check-label" for="checkgenap"> Genapkan</label>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-2 " style="width:68%;margin:auto;">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm" id="mutation_id" name="mutation_id" placeholder="0" type="text" value=""/>
                        <input class="btn btn-sm btn-info" type="button" id="paste_mutation_id" value="Paste ID Mutasi">
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

    {{-- Modal FU --}}
    <div class="modal fade" id="modal_fu" tabindex="-1" aria-hidden="true">
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
                <div>Kalau Nama tidak baik disebut di WA maka pilih <strong>Sebut Dengan Anda</strong></div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection

@section('js_inline')
<script>
/** =======================
 *  Utilities
 *  ======================= */
function addCustomPagination(api){
    const tableId = api.table().node().id;
    const wrapper = $(`#${tableId}_wrapper`);
    wrapper.find('.custom-pagination').remove();
    const custom = $(`
        <div class="custom-pagination d-inline-flex align-items-center ms-3">
            <input type="number" class="form-control form-control-sm" style="width:70px;">
            <button class="btn btn-sm btn-primary ms-1">Go</button>
        </div>`);
    wrapper.find('.dataTables_paginate').append(custom);
    const info = api.page.info();
    wrapper.find('.custom-pagination input').attr('placeholder', `${info.page+1}/${info.pages}`).val('');
}
function hideFunc(sel){
    const m = bootstrap.Modal.getInstance(document.querySelector(sel));
    m && m.hide();
}

/** =======================
 *  Filter State + Builders
 *  ======================= */
function buildDonateUrl(){
    const params = {
        need_fu: $('#fu_val').val(),
        day1:    $('#1day_val').val(),
        day5:    $('#5day_val').val(),
        bca:     $('#bca_val').val(),
        bni:     $('#bni_val').val(),
        bsi:     $('#bsi_val').val(),
        bri:     $('#bri_val').val(),
        qris:    $('#qris_val').val(),
        gopay:   $('#gopay_val').val(),
        mandiri: $('#mandiri_val').val(),
        donatur_name: encodeURI($('#donatur_name').val()||''),
        donatur_telp: $('#donatur_telp').val()||'',
        filter_nominal: $('#filter_nominal').val()||'',
        donatur_title: encodeURI($('#donatur_title').val()||''),
        ref_code: encodeURI($('#ref_code').val()||'')
    };
    const qs = Object.keys(params).map(k=>`${k}=${params[k]}`).join('&');
    return `{{ route('adm.donate.datatables') }}/?${qs}`;
}
function buildMutationUrl(){
    const params = {
        today:   $('#mut_today').val(),
        day1:    $('#mut_day1').val(),
        day2:    $('#mut_day2').val(),
        notmatch:$('#mut_notmatch').val(),
        bca:     $('#mut_bca').val(),
        bni:     $('#mut_bni').val(),
        bsi:     $('#mut_bsi').val(),
        bri:     $('#mut_bri').val(),
        mandiri: $('#mut_mandiri').val(),
        filter_nominal: $('#filter_nominal_mutation').val()||''
    };
    const qs = Object.keys(params).map(k=>`${k}=${params[k]}`).join('&');
    return `{{ route('adm.donate.mutation.datatables') }}/?${qs}`;
}
function donate_table(){ table.ajax.url(buildDonateUrl()).load(); }
function mutation_table(){ table_mutation.ajax.url(buildMutationUrl()).load(); }

/** =======================
 *  Generic Button Handlers
 *  ======================= */
// 1) Exclusive group toggles (payment & mutation)
function clearGroup(group){
    $(`.btn-toggle[data-group="${group}"]`).each(function(){
        const t = $(this).data('target');
        $(t).val(0);
        $(this).removeClass('btn-primary active').addClass('btn-outline-primary');
    });
}
$(document).on('click','.btn-toggle',function(){
    const group = $(this).data('group');     // 'payment' | 'mutation'
    const target = $(this).data('target');   // hidden input selector
    const value = $(this).data('value')||1;  // usually 1
    if($(this).hasClass('active')){
        // toggle off
        $(target).val(0);
        $(this).removeClass('btn-primary active').addClass('btn-outline-primary');
    }else{
        // exclusive on
        clearGroup(group);
        $(target).val(value);
        $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
    }
    (group==='mutation') ? mutation_table() : donate_table();
});

// 2) Simple switch (0 <-> toggleValue) for single hidden input
$(document).on('click','.btn-switch',function(){
    const target = $(this).data('target');
    const togVal = Number($(this).data('toggle-value')||1);
    const cur = Number($(target).val()||0);
    const next = cur===togVal ? 0 : togVal;
    $(target).val(next);
    $(this).toggleClass('btn-primary btn-outline-primary');
    donate_table();
});

// 3) Exclusive range for donate (1day vs 5day)
$(document).on('click','.btn-range',function(){
    const target = $(this).data('target'); // e.g. #1day_val or #5day_val
    const others = ($(this).data('other')||'').split(',');
    // activate this
    $(target).val(1);
    $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
    $(this).find('i').removeClass('fa-filter').addClass('fa-check');
    // deactivate others
    others.forEach(sel=>{
        if(sel){
            $(sel.trim()).val(0);
        }
    });
    // UI off others in same visual group
    $('.btn-range').not(this).each(function(){
        $(this).removeClass('btn-primary active').addClass('btn-outline-primary');
        $(this).find('i').removeClass('fa-check').addClass('fa-filter');
    });
    donate_table();
});

// 4) Exclusive range for mutation (today / day1 / day2)
$(document).on('click','.btn-mutr',function(){
    const target = $(this).data('target');
    const others = ($(this).data('other')||'').split(',');
    $(target).val(1);
    $(this).removeClass('btn-outline-primary').addClass('btn-primary active')
           .find('i').removeClass('fa-filter').addClass('fa-check');
    others.forEach(sel=>{ if(sel) $(sel.trim()).val(0); });
    $('.btn-mutr').not(this).each(function(){
        $(this).removeClass('btn-primary active').addClass('btn-outline-primary')
               .find('i').removeClass('fa-check').addClass('fa-filter');
    });
    mutation_table();
});

// 5) Quick nominal (1jt/500k) -> set #filter_nominal, exclusive within quick-nom group
$(document).on('click','.quick-nom',function(){
    const isActive = $(this).hasClass('active');
    $('.quick-nom').removeClass('btn-primary active').addClass('btn-outline-primary');
    if(isActive){
        $('#filter_nominal').val('');
    }else{
        $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
        $('#filter_nominal').val($(this).data('value'));
    }
    donate_table();
});
// ketik manual -> matikan quick-nom highlight
$('#filter_nominal').on('input',()=>$('.quick-nom').removeClass('btn-primary active').addClass('btn-outline-primary'));

/** =======================
 *  Existing: Edit Status / FU / Alarm / Format
 *  ======================= */
function editStatus(id,status,nominal){
    $("#id_trans").val(id);
    $("#rupiah").val(nominal.replace('Rp. ',''));
    const map = {draft:['status_draft','BELUM DIBAYAR'],success:['status_paid','SUDAH DIBAYAR'],cancel:['status_cancel','DIBATALKAN']};
    Object.keys(map).forEach(k=>{$('#'+map[k][0]).prop('disabled',false).prop('checked',false);});
    $('#'+map[status][0]).prop('disabled',true).prop('checked',true);
    $("#checkgenap").prop('checked',false);
    $("#modalTitle").html(nominal+' - '+map[status][1]);
    new bootstrap.Modal(document.getElementById('modal_status')).show();
}
function copyIDMutation(id){ $('#mutation_id').val(id); }
function addTrans(id){
    if(confirm("Yakin menambahkan ke donasi?")){
        $.post("{{ route('adm.donate.auto_add') }}",{_token:"{{ csrf_token() }}",mutation_id:id},function(d){
            alert(d.status==='success' ? ('BERHASIL, '+d.nominal+' ditambahkan ke transaksi donasi') : 'GAGAL menambahkan ke transaksi donasi');
        });
    }
}
$("#save_status").on("click",function(){
    const payload = {
        _token:"{{ csrf_token() }}",
        id_trans: $("#id_trans").val(),
        sendwa: $("#checkboxwa").is(':checked')?1:0,
        status: $('input[name="status"]:checked').val(),
        nominal: $('#rupiah').val(),
        mutation_id: $('#mutation_id').val()
    };
    $.post("{{ route('adm.donate.status.edit') }}", payload, function(data){
        if(data.status==='success'){
            const status_id = '#status_'+payload.id_trans;
            const badge = payload.status==='draft'?'warning':(payload.status==='success'?'success':'secondary');
            const text  = payload.status==='draft'?'BELUM DIBAYAR':(payload.status==='success'?'SUDAH DIBAYAR':'DIBATALKAN');
            $(status_id).html(data.nominal+`<br><span class="badge badge-${badge}">${text}</span>`);
            hideFunc('#modal_status');
        }
        $('#mutation_id').val('');
    });
});
function fuPaid(id,name,nominal){
    $("#modalTitleFu").html(name+" - "+nominal);
    $("#id_trans_fu").val(id);
    new bootstrap.Modal(document.getElementById('modal_fu')).show();
}
$("#save_fu").on("click",function(){
    $.post("{{ route('adm.donate.fu.paid') }}",{_token:"{{ csrf_token() }}",id_trans:$("#id_trans_fu").val(),status:$('input[name="fu_name"]:checked').val()},function(d){
        if(d==='success'){ hideFunc('#modal_fu'); alert("Sudah dikirim"); }
    });
});
$('#playButton').on('click',()=>{
    new Audio("{{ asset('public/audio/1.mp3') }}").play();
    $('#playButton').html('<i class="fa fa-volume-up mr-1"></i> ON');
});
function alarmNewDonate(){
    $.post("{{ route('adm.donate.check.alarm') }}",{_token:"{{ csrf_token() }}",last_donate:$('#last_donate').val()},function(d){
        if(d.status==='ON'){
            $('#last_donate').val(d.last_donate);
            const audio = new Audio("{{ asset('public/audio/1.mp3') }}");
            document.getElementById('playButton').addEventListener('click',()=>audio.play());
            document.getElementById('playButton').click();
        }
        $('#donate_paid_rp').html(d.paid_sum);
        $('#donate_paid_count').html(d.paid_count);
        $('#donate_unpaid_rp').html(d.unpaid_sum);
        $('#donate_unpaid_count').html(d.unpaid_count);
        $('#avg_paid_now').html(d.avg_paid_now);
        $('#paid_now').html(d.paid_now);
        $('#all_paid').html(d.all_paid);
        $('#sum_today').html(d.sum_today);
        $('#count_today').html(d.count_today);
    });
}
$("#checkgenap").on("click",function(){
    let v=$('#rupiah').val();
    v=v.slice(0,-3)+'000';
    $('#rupiah').val(formatRupiah(v,""));
});
const rupiah=document.getElementById("rupiah");
if(rupiah){ rupiah.addEventListener("keyup",function(){ this.value=formatRupiah(this.value,""); }); }
function formatRupiah(angka,prefix){
    var ns=angka.replace(/[^,\d]/g,"").toString(), sp=ns.split(","), sisa=sp[0].length%3, rp=sp[0].substr(0,sisa), ribu=sp[0].substr(sisa).match(/\d{3}/gi);
    if(ribu){ var sep=sisa?".":""; rp += sep + ribu.join("."); }
    rp = sp[1]!=undefined? rp + "," + sp[1] : rp;
    return prefix==undefined? rp : (rp? ""+rp :"");
}

/** =======================
 *  DataTables Init
 *  ======================= */
let need_fu=$('#fu_val').val(), day5=$('#5day_val').val(), day1=$('#1day_val').val();
let mut_today=$('#mut_today').val(), mut_day1=$('#mut_day1').val(), mut_day2=$('#mut_day2').val(), mut_notmatch=$('#mut_notmatch').val();
let mut_bca=$('#mut_bca').val(), mut_bni=$('#mut_bni').val(), mut_bsi=$('#mut_bsi').val(), mut_bri=$('#mut_bri').val(), mut_mandiri=$('#mut_mandiri').val();
let mut_nominal=$('#filter_nominal_mutation').val();

var table = $('#table-donatur').DataTable({
    orderCellsTop:true,fixedHeader:true,processing:true,serverSide:true,responsive:true,autoWidth:true,
    order:[[3,'desc']],
    language:{paginate:{previous:"<",next:">"}},
    ajax:"{{ route('adm.donate.datatables') }}/?need_fu="+need_fu+"&day1="+day1+"&day5="+day5,
    columnDefs:[
        {"width":"33%","targets":0},
        {"width":"20%","targets":1},
        {"width":"24%","targets":2},
        {"width":"23%","targets":3},
        {"orderable":false,"targets":1},
        {"orderable":false,"targets":2},
    ],
    columns:[
        {data:'name',name:'name'},
        {data:'nominal_final',name:'nominal_final'},
        {data:'invoice',name:'invoice'},
        {data:'created_at',name:'created_at'},
    ],
    initComplete:function(){
        const api=this.api(), tableId=api.table().node().id;
        $(`#${tableId}_wrapper`).on('click','.custom-pagination button',function(){
            const page=parseInt($(`#${tableId}_wrapper .custom-pagination input`).val(),10),
                  info=api.page.info();
            if(page>0 && page<=info.pages){ api.page(page-1).draw(false); }
            else { $(`#${tableId}_wrapper .custom-pagination input`).val(''); }
        });
    },
    drawCallback:function(){ addCustomPagination(this.api()); }
});
$('#table-donatur thead tr').clone(true).appendTo('#table-donatur thead');
$('#table-donatur tr:eq(1) th').each(function(i){
    var title=$(this).text();
    $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search '+title+'" />');
    $('input',this).on('keyup change',function(){
        if(table.column(i).search()!==this.value){ table.column(i).search(this.value).draw(); }
    });
});
$("#refresh_table_donate").on("click",()=>table.ajax.reload());
$("#filter_search").on("click",donate_table);

$.fn.DataTable.ext.pager.numbers_length=7;
var table_mutation=$('#table-donatur-mutation').DataTable({
    orderCellsTop:true,fixedHeader:true,processing:true,serverSide:true,responsive:false,lengthChange:false,ordering:false,
    dom:'ftipr',
    language:{paginate:{previous:"<",next:">"}},
    autoWidth:false,
    ajax:"{{ route('adm.donate.mutation.datatables') }}/?/?today="+mut_today+"&day1="+mut_day1+"&day2="+mut_day2+"&notmatch="+mut_notmatch+"&bca="+mut_bca+"&bni="+mut_bni+"&bsi="+mut_bsi+"&bri="+mut_bri+"&mandiri="+mut_mandiri+"&filter_nominal="+mut_nominal,
    columnDefs:[
        {"width":"30%","targets":0},
        {"width":"70%","targets":1},
        {"orderable":false,"targets":0}
    ],
    columns:[
        {data:'nominal',name:'nominal'},
        {data:'date_desc',name:'date_desc'}
    ],
    initComplete:function(){
        const api=this.api(), tableId=api.table().node().id;
        $(`#${tableId}_wrapper`).on('click','.custom-pagination button',function(){
            const page=parseInt($(`#${tableId}_wrapper .custom-pagination input`).val(),10),
                  info=api.page.info();
            if(page>0 && page<=info.pages){ api.page(page-1).draw(false); }
            else { $(`#${tableId}_wrapper .custom-pagination input`).val(''); }
        });
    },
    drawCallback:function(){ addCustomPagination(this.api()); }
});
$("#filter_search_mutation").on("click",mutation_table);
$("#refresh_table_donate_mutation").on("click",()=>table_mutation.ajax.reload());

/** =======================
 *  Interval refresh + alarm
 *  ======================= */
$(document).ready(function(){
    setInterval(function(){
        table.ajax.reload();
        table_mutation.ajax.reload();
        alarmNewDonate();
    },250000);
});
</script>
@endsection
