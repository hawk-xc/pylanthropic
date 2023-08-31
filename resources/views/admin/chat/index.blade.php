@extends('layouts.admin', [
    'second_title'    => 'Chat History',
    'header_title'    => 'Chat History',
    'sidebar_menu'    => 'wa-history',
    'sidebar_submenu' => 'wa-history'
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
                            <li class="breadcrumb-item active" aria-current="page">Chat</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <a href="#" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah Chat</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-chat" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Telp</th>
                        <th>Tipe</th>
                        <th>Text</th>
                        <th>Program</th>
                        <th>Transaksi</th>
                        <th>Tanggal</th>
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

    var table = $('#table-chat').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[5, 'desc']],
        ajax: "{{ route('adm.chat.datatables') }}",
        columns: [
            {data: 'no_telp', name: 'no_telp'},
            {data: 'type', name: 'type'},
            {data: 'text', name: 'text'},
            {data: 'program', name: 'program'},
            {data: 'transaction', name: 'transaction'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
    $('#table-chat thead tr').clone(true).appendTo( '#table-chat thead' );
    $('#table-chat tr:eq(1) th').each( function (i) {
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
