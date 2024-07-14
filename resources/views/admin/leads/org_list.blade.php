@extends('layouts.admin', [
'second_title'    => 'Leads Lembaga',
'header_title'    => 'Leads Lembaga',
'sidebar_menu'    => 'leads',
'sidebar_submenu' => 'org-list'
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
                        <li class="breadcrumb-item active" aria-current="page">Leads Lembaga</li>
                    </ol>
                </nav>
            </div>
            <div class="col-7 fc-rtl">
                <button class="btn btn-outline-primary btn_filter" id="fil_interest" data-id="interest">Potensial</button>
                <button class="btn btn-outline-primary btn_filter" id="fil_wa" data-id="wa">Ada WA</button>
                <button class="btn btn-outline-primary btn_filter" id="fil_email" data-id="email">Ada Email</button>
                <button class="btn btn-outline-primary btn_filter" id="refresh_datatable">Refresh</button>
                <a href="{{ route('adm.leads.org.add') }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah</a>
            </div>
        </div>
        <div class="divider"></div>
        <table id="table-donatur" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nama Lembaga</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Sosial Media</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="interest_val" value="0">
<input type="hidden" id="wa_val" value="0">
<input type="hidden" id="email_val" value="0">
@endsection


@section('js_plugins')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
    $.fn.DataTable.ext.pager.numbers_length = 15;
    var table = $('#table-donatur').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order: [],
        language: {
            paginate: {
                previous: "<",
                next: ">"
            }
        },
        ajax: "{{ route('adm.leads.org.datatables') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'contact', name: 'contact'},
            {data: 'address', name: 'address'},
            {data: 'socmed', name: 'socmed'},
            {data: 'action', name: 'action'},
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

    $("#refresh_datatable").on("click", function() {
        table.ajax.reload();
    });

    function donate_table() {
        let interest_ar = $('#interest_val').val();
        let wa_ar       = $('#wa_val').val();
        let email_ar    = $('#email_val').val();

        table.ajax.url("{{ route('adm.leads.org.datatables') }}/?interest="+interest_ar+"&ada_wa="+wa_ar+"&ada_email="+email_ar).load();
    }

    // Filter
    $(".btn_filter").on("click", function(){
        let fil_interest = $('#interest_val').val();
        let fil_wa       = $('#wa_val').val();
        let fil_email    = $('#email_val').val();
        var fil_btn      = $(this).attr("data-id");

        if(fil_btn=='interest') {
            if(fil_interest==0) {
                $('#fil_interest').removeClass('btn-outline-primary');
                $('#fil_interest').addClass('btn-primary');
                $('#interest_val').val(1);
                donate_table();
            } else {
                $('#fil_interest').addClass('btn-outline-primary');
                $('#fil_interest').removeClass('btn-primary');
                $('#interest_val').val(0);
                donate_table();
            }
        } else if(fil_btn=='wa') {
            if(fil_wa==0) {
                $('#fil_wa').removeClass('btn-outline-primary');
                $('#fil_wa').addClass('btn-primary');
                $('#wa_val').val(1);
                donate_table();
            } else {
                $('#fil_wa').addClass('btn-outline-primary');
                $('#fil_wa').removeClass('btn-primary');
                $('#wa_val').val(0);
                donate_table();
            }
        } else if(fil_btn=='email') {
            if(fil_email==0) {
                $('#fil_email').removeClass('btn-outline-primary');
                $('#fil_email').addClass('btn-primary');
                $('#email_val').val(1);
                donate_table();
            } else {
                $('#fil_email').addClass('btn-outline-primary');
                $('#fil_email').removeClass('btn-primary');
                $('#email_val').val(0);
                donate_table();
            }
        }
    });


    function firstChat(id, org) {
        var result = confirm("Ingin Frist Chat ke "+org+"?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "{{ route('adm.leads.org.chat') }}",
                data: {
                  "_token": "{{ csrf_token() }}",
                  "id": id,
                  "type": 'fc'
                },
                success: function(data){
                    if(data.status=='success') {
                        alert('BERHASIL, first chat ke lembaga '+data.org);
                    } else {
                        alert('GAGAL, frist chat ke lembaga');
                    }
                }
            });
        }
    }

</script>
@endsection
