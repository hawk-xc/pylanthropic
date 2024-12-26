@extends('layouts.admin', [
    'second_title'    => 'Lembaga',
    'header_title'    => 'Lembaga',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'organization'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('css_inline')
    <style type="text/css">
        
    </style>
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Lembaga</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary">Biasa</button>
                    <button class="btn btn-outline-primary">Terverifikasi</button>
                    <button class="btn btn-outline-primary">Banned</button>
                    <button class="btn btn-outline-primary">Lembaga</button>
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_org"><i class="fa fa-sync"></i> Refresh</button>
                    <a href="{{ route('adm.organization.create') }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Rangkuman</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">

    var table = $('#table-donatur').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: "{{ route('adm.org.datatables') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'contact', name: 'contact'},
            {data: 'summary', name: 'summary'},
            {data: 'address', name: 'address'},
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

    $("#refresh_table_org").on("click", function() {
        table.ajax.reload();
    });

</script>
@endsection
