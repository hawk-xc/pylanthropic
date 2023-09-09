@extends('layouts.admin', [
    'second_title'    => 'Transaksi',
    'header_title'    => 'Transaksi Donasi',
    'sidebar_menu'    => 'donate',
    'sidebar_submenu' => 'donate'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Donatur</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-9 fc-rtl">
                    <button class="btn btn-outline-primary"><i class="fa fa-sync mr-1"></i> BCA</button>
                    <button class="btn btn-outline-primary"><i class="fa fa-sync mr-1"></i> BRI</button>
                    <button class="btn btn-outline-primary"><i class="fa fa-sync mr-1"></i> BNI</button>
                    <button class="btn btn-outline-primary"><i class="fa fa-sync mr-1"></i> BSI</button>
                    <button class="btn btn-outline-primary mr-3"><i class="fa fa-sync mr-1"></i> Mandiri</button>

                    <button class="btn btn-outline-primary" id="playButton"><i class="fa fa-volume-mute mr-1"></i> OFF</button>
                    <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <button class="btn btn-outline-primary" id="refresh_table_donate"><i class="fa fa-sync mr-1"></i> Refresh</button>
                    <a href="#" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah Donasi</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jml Donasi</th>
                        <th>Judul</th>
                        <th>Staus</th>
                        <th>Tgl Donasi</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="last_donate" value="{{ $last_donate }}">
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
                <div class="mt-2" style="width: 50%; margin: auto;">    
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">RP</span>
                        <input class="form-control form-control-sm" id="rupiah" name="amount" placeholder="0" type="text" value=""/>
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
        $("#modalTitle").html(nominal+' - '+status_show);
        let myModal = new bootstrap.Modal(document.getElementById('modal_status'));
        myModal.show();
    }

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
                    // toast success
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

    function hideFunc(name) {
        // const truck_modal = document.querySelector('#modal_status');
        const truck_modal = document.querySelector(name);
        const modal = bootstrap.Modal.getInstance(truck_modal);    
        modal.hide();
    }

    var table = $('#table-donatur').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[4, 'desc']],
        ajax: "{{ route('adm.donate.datatables') }}",
        "columnDefs": [
            { "width": "21%", "targets": 0 },
            { "width": "14%", "targets": 1 },
            { "width": "35%", "targets": 2 },
            { "width": "16%", "targets": 3 },
            { "width": "14%", "targets": 4 }
        ],
        columns: [
            {data: 'name', name: 'name'},
            {data: 'nominal_final', name: 'nominal_final'},
            {data: 'title', name: 'title'},
            {data: 'invoice', name: 'invoice'},
            {data: 'created_at', name: 'created_at'},
            // {
            //     data: 'action', 
            //     name: 'action', 
            //     orderable: false, 
            //     searchable: false
            // },
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

    function fuPaid(id, name, nominal) {
        $("#modalTitleFu").html(name+' - '+nominal);
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

    $(document).ready(function(){
        setInterval( function () {
            table.ajax.reload();    // reset paging
            // table.ajax.reload(null, false);    // paging retained
            alarmNewDonate();
        }, 250000 );
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
