@extends('layouts.admin', [
    'second_title'    => 'Dashboard Admin',
    'header_title'    => 'Dashboard Admin',
    'sidebar_menu'    => 'dashboard',
    'sidebar_submenu' => 'Dashboard Admin'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .big-checkbox .form-check-input {
            width: 22px;
            height: 22px;
            margin-top: 5px;
        }
        .big-checkbox .form-check-label {
            margin-left: 8px;
            margin-top: 4px;
        }
        .widget-chart { padding:8px !important;  }
        .widget-chart .widget-numbers { margin: 12px auto 14px auto; }
    </style>
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12 col-xl-8">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="card-header p-0" style="border-bottom: 0px; height: auto;">
                            Donate Report
                            <div class="btn-actions-pane-right">AVG/Day : Rp.{{ str_replace(',', '.', number_format($sum_paid_now/date('d'))) }}</div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>{{ date('d-m-Y') }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-1 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-2 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-3 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-4 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-5 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-6 day')) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JML Dibayar</td>
                                            <td>{{ number_format($donate_success[0]) }}</td>
                                            <td>{{ number_format($donate_success[1]) }}</td>
                                            <td>{{ number_format($donate_success[2]) }}</td>
                                            <td>{{ number_format($donate_success[3]) }}</td>
                                            <td>{{ number_format($donate_success[4]) }}</td>
                                            <td>{{ number_format($donate_success[5]) }}</td>
                                            <td>{{ number_format($donate_success[6]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rp Dibayar</td>
                                            <td>{{ number_format($donate_success_rp[0]) }}</td>
                                            <td>{{ number_format($donate_success_rp[1]) }}</td>
                                            <td>{{ number_format($donate_success_rp[2]) }}</td>
                                            <td>{{ number_format($donate_success_rp[3]) }}</td>
                                            <td>{{ number_format($donate_success_rp[4]) }}</td>
                                            <td>{{ number_format($donate_success_rp[5]) }}</td>
                                            <td>{{ number_format($donate_success_rp[6]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>JML Blm Dibayar</td>
                                            <td>{{ number_format($donate_draft[0]) }}</td>
                                            <td>{{ number_format($donate_draft[1]) }}</td>
                                            <td>{{ number_format($donate_draft[2]) }}</td>
                                            <td>{{ number_format($donate_draft[3]) }}</td>
                                            <td>{{ number_format($donate_draft[4]) }}</td>
                                            <td>{{ number_format($donate_draft[5]) }}</td>
                                            <td>{{ number_format($donate_draft[6]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rp Blm Dibayar</td>
                                            <td>{{ number_format($donate_draft_rp[0]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[1]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[2]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[3]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[4]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[5]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[6]) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-4 ps-0">
                <div class="main-card mb-3 card">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-warning"></div>
                                        <i class="lnr-gift text-warning"></i>
                                    </div>
                                    <div class="widget-numbers fs-5">Rp.{{ str_replace(',', '.', number_format($sum_paid_now)) }}</div>
                                    <div class="widget-subheading">Dibayar Bulan Ini</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-info"></div>
                                        <i class="lnr-gift text-info"></i>
                                    </div>
                                    <div class="widget-numbers fs-5">Rp.{{ str_replace(',', '.', number_format($sum_paid)) }}</div>
                                    <div class="widget-subheading">Total Dibayar</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-success"></div>
                                        <i class="lnr-heart text-success"></i>
                                    </div>
                                    <div class="widget-numbers">{{ str_replace(',', '.', number_format($sum_donate)) }}</div>
                                    <div class="widget-subheading">Total Donasi</div>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-warning"></div>
                                        <i class="lnr-eye text-warning"></i>
                                    </div>
                                    <div class="widget-numbers fs-3">{{ str_replace(',', '.', number_format($sum_page_viewed)) }}</div>
                                    <div class="widget-subheading">Halaman Program Dilihat</div> -->
                                    <!-- <div class="widget-description text-info">
                                        <i class="fa fa-arrow-right"></i>
                                        <span class="pl-1">175.5%</span>
                                    </div> -->
                                <!-- </div>
                            </div> -->
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover br-br">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-primary"></div>
                                        <i class="lnr-cart text-primary"></i>
                                    </div>
                                    <div class="widget-numbers">{{ str_replace(',', '.', number_format($sum_transaction)) }}</div>
                                    <div class="widget-subheading">Total Transaksi</div>
                                    <!-- <div class="widget-description text-warning">
                                        <span class="pr-1">175.5%</span>
                                        <i class="fa fa-arrow-left"></i>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Statistik Pengunjung Keseluruhan
                    </div>
                    <div class="card-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Program
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">
                                <button class="active btn btn-info" id="refresh_table">Refresh Table</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive px-3 mt-3 mb-3">
                        <table id="table-donatur1" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Nominal</th>
                                    <th>Rekomendasi Ads</th>
                                    <th>Donasi</th>
                                    <th>Statistik</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Fundraiser
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">
                                <button class="active btn btn-info" id="refresh_table_fundraiser">Refresh Table</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive px-3 mt-3 mb-3">
                        <table id="table-fundraiser1" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>REF CODE</th>
                                    <th>TOTAL</th>
                                    <th>00:00 - 04:59</th>
                                    <th>05:00 - 08:59</th>
                                    <th>09:00 - 16:59</th>
                                    <th>17:00 - 23:59</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@section('content_modal')
    <!-- Modal Show Stats -->
    <div class="modal fade" id="modal_show_donate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitle">Donate Report</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4" id="modalBody">
                
            </div>
            <div class="modal-footer pt-2 pb-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
      </div>
    </div>


    <!-- Modal Show Summary -->
    <div class="modal fade" id="modal_show_summary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitleSummary">...</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4" id="modalBodySummary">
                
            </div>
            <div class="modal-footer pt-2 pb-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
      </div>
    </div>

    <!-- Modal Input Spend Budget -->
    <div class="modal fade" id="modal_inp_spend" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitleSpend">Donate Report</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4">
                <div id="modalBodySpend"></div>
                <div class="table-responsive mt-1 mb-2">
                    <table id="table-spent" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row gy-1 mt-3">
                    <div class="col-12">
                        <hr>
                    </div>
                    <input type="hidden" id="id_program" name="id_program" value="">
                    <div class="col-12 text-start">
                        <span class="fs-5 fw-semibold">Form Input Spend Budget</span>
                    </div>
                    <div class="col-4">
                        <input type="text" name="title" id="title" class="form-control form-control-sm" value="Iklan FB">
                    </div>
                    <div class="col-3">
                        <input type="datetime-local" name="date" id="date_time" class="form-control form-control-sm" value="{{ date('Y-m-d H:i') }}">
                    </div>
                    <div class="col-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">RP</span>
                            <input class="form-control form-control-sm" id="rupiah" name="amount" placeholder="0" type="text" value=""/>
                        </div>
                    </div>
                    <div class="col-2 text-start">
                        <div class="form-check big-checkbox">
                            <input class="form-check-input" type="checkbox" value="" id="check11percent">
                            <label class="form-check-label" for="check11percent">+ 11%</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-2 pb-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="submit_spend">Submit</button>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
    let tgl        = [];
    let tgl_before = new Date();
    tgl            = [tgl_before.getDate() + '-' + tgl_before.getMonth()];
    for(i=1; i<30; i++) {
        let tgl_before = new Date(Date.now() - i*24*60*60*1000);
        tgl.push(tgl_before.getDate() + '-' + tgl_before.getMonth());
    }

    function getStatsVisitor(type_page) {
        var tmp = null;
        $.ajax({
            async: false,
            global: false,
            type: "GET",
            url: "{{ route('adm.program.visitor.stats') }}",
            data: {'type':type_page, 'row':30},
            success: function (data) {
                tmp = data;
            }
        });
        return tmp;
    }

    new Chart(document.getElementById('myChart'), {
        type: 'line',
        data: {
            labels: tgl,
            datasets: [{
                label: 'Visit LP',
                // data: getStatsVisitor('landing_page'),
                data: [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
                borderColor: 'rgb(170, 215, 217)',
                tension: 0.1
            },
            {
                label: 'Klik Donasi',
                // data: getStatsVisitor('amount'),
                data: [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
                borderColor: 'rgb(40, 169, 224)',
                tension: 0.13
            },
            {
                label: 'Donasi/Transaksi',
                // data: getStatsVisitor('donate'),
                data: [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
                borderColor: 'rgb(255, 181, 52)',
                tension: 0.15
            },
            {
                label: 'Dibayar',
                // data: getStatsVisitor('paid'),
                data: [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
                borderColor: 'rgb(139, 197, 61)',
                tension: 0.18
            }]
        }
    });

    var table = $('#table-donatur').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        pageLength : 25,
        columnDefs: [
            { "width": "22%", "targets": 0 }
        ],
        // order: [[4, 'desc']],
        order: [],
        ajax: "{{ route('adm.program.dashboard.datatables').'/?is_publish=1' }}",
        columns: [
            {data: 'title', name: 'title'},
            {data: 'nominal', name: 'nominal'},
            {data: 'ads', name: 'ads'},
            {data: 'donate', name: 'donate'},
            {data: 'stats', name: 'stats'},
        ]
    });

    $("#refresh_table").on("click", function() {
        table.ajax.reload();
    });

    var table_fundraiser = $('#table-fundraiser').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        pageLength : 25,
        // columnDefs: [
        //     { "width": "22%", "targets": 0 }
        // ],
        order: [[1, 'desc']],
        ajax: "{{ route('adm.fundraiser.dashboard.datatables').'/?date='.date('Y-m-d') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'total', name: 'total'},
            {data: 'sesi1', name: 'sesi1'},
            {data: 'sesi2', name: 'sesi2'},
            {data: 'sesi3', name: 'sesi3'},
            {data: 'sesi4', name: 'sesi4'},
        ]
    });

    $("#refresh_table_fundraiser").on("click", function() {
        table_fundraiser.ajax.reload();
    });

    function showDonate(id, title) {
        $("#modalTitle").html(title);
        
        $.ajax({
            type: "GET",
            url: "{{ route('adm.program.show.donate') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id": id
            },
            success: function(data){
                $("#modalBody").html(data);
            }
        });

        let myModal = new bootstrap.Modal(document.getElementById('modal_show_donate'));
        myModal.show();
    }

    function showSummary(id, title) {
        $("#modalTitleSummary").html(title);
        
        $.ajax({
            type: "GET",
            url: "{{ route('adm.program.show.summary') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id": id
            },
            success: function(data){
                $("#modalBodySummary").html(data);
            }
        });

        let myModal = new bootstrap.Modal(document.getElementById('modal_show_summary'));
        myModal.show();
    }

    function hideFunc(name) {
        const truck_modal = document.querySelector(name);
        const modal = bootstrap.Modal.getInstance(truck_modal);    
        modal.hide();
    }

    $("#submit_spend").on("click", function(){
        var id_program = $("#id_program").val();
        var title      = $("#title").val();
        var date_time  = $("#date_time").val();
        var nominal    = $('#rupiah').val();

        $.ajax({
            type: "POST",
            url: "{{ route('adm.program.spend.submit') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id_program": id_program,
              "title": title,
              "date_time": date_time,
              "nominal": nominal
            },
            success: function(data){
                console.log(data);
                if(data=='success') {
                    table.ajax.reload();
                    hideFunc('#modal_inp_spend');
                    // toast success
                    alert("Berhasil Disimpan");
                }
            }
        });
    });

    function inpSpend(id, title) {
        $("#modalTitleSpend").html(title);
        $("#id_program").val(id);
        
        table_spent.ajax.url("{{ route('adm.program.spend.show').'/?id=' }}"+id).load();

        let myModal = new bootstrap.Modal(document.getElementById('modal_inp_spend'));
        myModal.show();
    }

    var table_spent = $('#table-spent').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        pageLength : 10,
        order: [[0, 'desc']],
        ajax: "{{ route('adm.program.spend.show').'/?id=1' }}",
        columns: [
            {data: 'date', name: 'date'},
            {data: 'title', name: 'title'},
            {data: 'nominal', name: 'nominal'},
            {data: 'status', name: 'status'},
            {data: 'desc', name: 'desc'},
        ]
    });

    $("#check11percent").on("click", function() {
        var val_rupiah = $('#rupiah').val();
        val_rupiah     = val_rupiah.replaceAll(".", "");
        val_rupiah     = Number(val_rupiah);
        var rupiah_11  = Math.ceil(val_rupiah*11/100);
        console.log(val_rupiah);

        if ($('#check11percent').is(':checked')) {
            console.log(rupiah_11);
            $('#rupiah').val(val_rupiah + rupiah_11);
            let rupiah_fix = formatRupiah( document.getElementById("rupiah").value, "");
            $('#rupiah').val(rupiah_fix);
        } else {
            $('#rupiah').val(val_rupiah - rupiah_11);
            let rupiah_fix = formatRupiah( document.getElementById("rupiah").value, "");
            $('#rupiah').val(rupiah_fix);
        }
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
