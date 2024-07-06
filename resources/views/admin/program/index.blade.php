@extends('layouts.admin', [
    'second_title'    => 'Program',
    'header_title'    => 'List Program',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('css_inline')
    <style type="text/css">
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
                <div class="col-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-9 fc-rtl">
                    <button class="btn btn-outline-primary">Non Aktif</button>
                    <button class="btn btn-outline-primary">Aktif</button>
                    <button class="btn btn-outline-primary">Winning</button>
                    <button class="btn btn-outline-primary">Publish 15 Hari Terakhir</button>
                    <button class="btn btn-outline-primary">Berakhir 15 Hari</button>
                    <!-- <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button> -->
                    <a href="{{ route('adm.program.create') }}" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Lembaga</th>
                        <th>Donasi</th>
                        <th>Statistik</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
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
@endsection


@section('js_inline')
<script type="text/javascript">

    var table = $('#table-donatur').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        columnDefs: [
            { "width": "22%", "targets": 0 }
        ],
        // order: [[4, 'desc']],
        order: [],
        ajax: "{{ route('adm.program.datatables') }}",
        columns: [
            {data: 'title', name: 'title'},
            {data: 'nominal', name: 'nominal'},
            {data: 'status', name: 'status'},
            {data: 'organization', name: 'organization'},
            {data: 'donate', name: 'donate'},
            {data: 'stats', name: 'stats'},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
        ]
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
