@extends('layouts.admin', [
    'second_title'    => 'Donatur',
    'header_title'    => 'Donatur',
    'sidebar_menu'    => 'person',
    'sidebar_submenu' => 'donatur'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
 -->
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
                        <th>Name</th>
                        <th>Terakhir Donasi</th>
                        <th>Summary Donasi</th>
                        <th>History Chat</th>
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
        order: [[2, 'desc']],
        ajax: "{{ route('adm.donatur.datatables') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'last_donate', name: 'last_donate'},
            {data: 'donate_summary', name: 'donate_summary'},
            {data: 'chat', name: 'chat'},
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

</script>
@endsection
