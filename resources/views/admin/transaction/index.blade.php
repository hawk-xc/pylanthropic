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
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Donatur</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <a href="#" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah Donatur</a>
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
        $("#modalTitle").html(nominal);
        $("#id_trans").val(id);
        if(status=='draft') {
            document.getElementById("status_draft").disabled = true;
            document.getElementById("status_draft").checked = true;
        } else if(status=='success') {
            document.getElementById("status_paid").disabled = true;
            document.getElementById("status_paid").checked = true;
        } else {
            document.getElementById("status_cancel").disabled = true;
            document.getElementById("status_cancel").checked = true;
        }
        let myModal = new bootstrap.Modal(document.getElementById('modal_status'));
        myModal.show();
    }

    $("#save_status").on("click", function(){
        var id_trans = $("#id_trans").val();
        var status   = $('input[name="status"]:checked').val();

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
              "status": status
            },
            success: function(data){
                console.log(data);
                if(data=='success') {
                    // toast success
                    let status_id = '#status_'+id_trans;
                    if(status=='draft') {
                        $(status_id).html('<span class="badge badge-warning">BELUM DIBAYAR</span>');
                    } else if(status=='success') {
                        $(status_id).html('<span class="badge badge-success">SUDAH DIBAYAR</span>');
                    } else {
                        $(status_id).html('<span class="badge badge-secondary">DIBATALKAN</span>');
                    }

                    hideFunc();
                }
            }
        });
    });

    function hideFunc() {
        const truck_modal = document.querySelector('#modal_status');
        const modal = bootstrap.Modal.getInstance(truck_modal);    
        modal.hide();
    }

    var table = $('#table-donatur').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[4, 'desc']],
        ajax: "{{ route('adm.donate.datatables') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'nominal_final', name: 'nominal_final'},
            {data: 'title', name: 'title'},
            {data: 'invoice', name: 'invoice'},
            {data: 'created_at', name: 'created_at'},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
        ]
    });

</script>
@endsection
