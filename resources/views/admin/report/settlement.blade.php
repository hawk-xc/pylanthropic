@extends('layouts.admin', [
    'second_title'    => 'Laporan Settlement',
    'header_title'    => 'Laporan Settlement',
    'sidebar_menu'    => 'report',
    'sidebar_submenu' => 'settlement'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">Mutasi Bank & EPayment</div>
                                <table id="table-mutation" class="table table-hover mb-1">
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Trans ID</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <div class="card-title">Transaksi Belum Settlement</div>
                                <table id="table-transaction" class="table table-hover mb-1">
                                    <thead>
                                        <tr>
                                            <th>Trans ID</th>
                                            <th>Nominal</th>
                                            <th>Jenis</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content_modal')
    <!-- Modal Edit Status -->
    <div class="modal fade" id="modal_motation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitle">Modal title</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4">
                <input type="radio" class="btn-check" name="status" id="status_draft" autocomplete="off" value="draft">
                <label class="btn btn-outline-info" for="status_draft">Draft</label>
                <input type="radio" class="btn-check" name="status" id="status_matched" autocomplete="off" value="matched">
                <label class="btn btn-outline-success" for="status_matched">Matched</label>
                <input type="radio" class="btn-check" name="status" id="status_duplicate" autocomplete="off" value="duplicate">
                <label class="btn btn-outline-warning" for="status_duplicate">Duplicate</label>
                <input type="radio" class="btn-check" name="status" id="status_hold" autocomplete="off" value="hold">
                <label class="btn btn-outline-secondary" for="status_hold">Hold</label>
                <input type="radio" class="btn-check" name="status" id="status_notfound" autocomplete="off" value="notfound">
                <label class="btn btn-outline-danger" for="status_notfound">Notfound</label>
                <div class="mt-2" style="width: 50%; margin: auto;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Trans ID</span>
                        <input class="form-control form-control-sm" id="id_trans" name="id_trans" placeholder="0" type="number" value=""/>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-2 pb-2">
                <input type="hidden" id="id_mutation" name="id_mutation" value="">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="save_status">Simpan</button>
            </div>
        </div>
      </div>
    </div>
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <!-- Datatable -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
    var table = $('#table-mutation').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        order: [[2, 'desc']],
        ajax: "{{ route('adm.report.mutation.datatables').$month }}",
        columns: [
            {data: 'bank', name: 'bank'},
            {data: 'nominal', name: 'nominal'},
            {data: 'status', name: 'status'},
            {data: 'transaction_id', name: 'transaction_id'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
    $('#table-mutation thead tr').clone(true).appendTo( '#table-mutation thead' );
    $('#table-mutation tr:eq(1) th').each( function (i) {
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

    var table_trans = $('#table-transaction').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        order: [[2, 'desc']],
        ajax: "{{ route('adm.report.transaction.datatables').$month }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'nominal_final', name: 'nominal_final'},
            {data: 'name', name: 'name'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
    $('#table-transaction thead tr').clone(true).appendTo( '#table-transaction thead' );
    $('#table-transaction tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control form-control-sm" placeholder="Search '+title+'" />' );
    
        $( 'input', this ).on( 'keyup change', function () {
            if ( table_trans.column(i).search() !== this.value ) {
                table_trans
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    });

    // modal edit mutation
    function editMutation(id, trans_id, status, nominal, bank) {
        $("#id_mutation").val(id);
        $("#id_trans").val(trans_id);
        $("#modalTitle").html(nominal+' - '+bank);

        if(status=='draft') {
            document.getElementById("status_draft").disabled = true;
            document.getElementById("status_draft").checked = true;
        } else if(status=='matched') {
            document.getElementById("status_matched").disabled = true;
            document.getElementById("status_matched").checked = true;
        } else if(status=='duplicate') {
            document.getElementById("status_duplicate").disabled = true;
            document.getElementById("status_duplicate").checked = true;
        } else if(status=='hold') {
            document.getElementById("status_hold").disabled = true;
            document.getElementById("status_hold").checked = true;
        } else {
            document.getElementById("status_notfound").disabled = true;
            document.getElementById("status_notfound").checked = true;
        }
        let myModal = new bootstrap.Modal(document.getElementById('modal_motation'));
        myModal.show();
    }

    // simpan status
    $("#save_status").on("click", function(){
        var id_mutation = $("#id_mutation").val();
        var status      = $('input[name="status"]:checked').val();
        var id_trans    = $('#id_trans').val();

        $.ajax({
            type: "POST",
            url: "{{ route('adm.report.mutation.edit') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id_mutation": id_mutation,
              "status": status,
              "id_trans": id_trans
            },
            success: function(data){
                table.ajax.reload();
                table_trans.ajax.reload();
                hideFunc('#modal_motation');
            }
        });
    });

    function hideFunc(name) {
        const truck_modal = document.querySelector(name);
        const modal = bootstrap.Modal.getInstance(truck_modal);    
        modal.hide();
    }
</script>
@endsection
