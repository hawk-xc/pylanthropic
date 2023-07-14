@extends('layouts.admin', [
    'second_title'    => 'Program',
    'header_title'    => 'List Program',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program'
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
                            <li class="breadcrumb-item active" aria-current="page">Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('adm.program.create') }}" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah Program</a>
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
      <div class="modal-dialog modal-lg">
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
        order: [[4, 'desc']],
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
</script>
@endsection
