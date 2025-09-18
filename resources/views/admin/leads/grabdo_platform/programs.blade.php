@extends('layouts.admin', [
'second_title'    => 'Leads',
'header_title'    => 'Leads',
'sidebar_menu'    => 'leads',
'sidebar_submenu' => 'grab_do'
])

@section('css_plugins')
<link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="row">
            <div class="col-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 pb-0">
                        <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adm.leads.grabdo.platform') }}">Grab Do Platform</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Program List dari {{ $platform->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="divider"></div>
        <table id="table-list_grab_program" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Image</th>
                    <th>Nominal & Lembaga</th>
                    <th>Platform</th>
                    <th>Tanggal</th>
                    <th>Headline</th>
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
    var table = $('#table-list_grab_program').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: "{{ route('adm.leads.grabdo.platform-programs.datatables', $platform->id) }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'images', name: 'images', orderable: false, searchable: false},
            {data: 'nominal', name: 'nominal', orderable: false, searchable: false},
            {data: 'platform', name: 'platform'},
            {data: 'date', name: 'date', orderable: false, searchable: false},
            {data: 'headline', name: 'headline'},
        ]
    });
    $('#table-list_grab_program thead tr').clone(true).appendTo( '#table-list_grab_program thead' );
    $('#table-list_grab_program tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        if (i === 1 || i === 2 || i === 4) { // No search for image, nominal, date
            $(this).html('');
            return;
        }
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
