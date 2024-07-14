@extends('layouts.admin', [
'second_title'    => 'Leads',
'header_title'    => 'Leads',
'sidebar_menu'    => 'leads',
'sidebar_submenu' => 'grab'
])


@section('css_plugins')
<link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">-->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/fontawesome.min.css" rel="stylesheet"> -->
@endsection


@section('content')
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="row">
            <div class="col-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 pb-0">
                        <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leads</li>
                    </ol>
                </nav>
            </div>
            <div class="col-7 fc-rtl">
                <button class="btn btn-outline-primary btn_filter" id="fil_interest" data-id="interest">Menarik</button>
                <button class="btn btn-outline-primary btn_filter" id="fil_taken" data-id="taken">Garap</button>
                <button class="btn btn-outline-primary btn_filter" id="fil_20jt" data-id="20jt">< 20jt</button>
                <button class="btn btn-outline-primary btn_filter" id="fil_50jt" data-id="50jt">> 50jt</button>
                <a href="#" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah</a>
            </div>
        </div>
        <div class="divider"></div>
        <table id="table-donatur" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Image</th>
                    <th>Nominal & Lembaga</th>
                    <th>Tanggal</th>
                    <th>Headline</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="interest_val" value="0">
<input type="hidden" id="taken_val" value="0">
<input type="hidden" id="20_val" value="0">
<input type="hidden" id="50_val" value="0">
@endsection


@section('js_plugins')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/fontawesome.min.js"></script> -->
@endsection


@section('js_inline')
<script type="text/javascript">
    $.fn.DataTable.ext.pager.numbers_length = 20;
    var table = $('#table-donatur').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 50,
        order: [],
        language: {
            paginate: {
                previous: "<",
                next: ">"
            }
        },
        ajax: "{{ route('adm.leads.grab.datatables') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'images', name: 'images'},
            {data: 'nominal', name: 'nominal'},
            {data: 'date', name: 'date'},
            {data: 'headline', name: 'headline'},
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

    function donate_table() {
        let interest_ar = $('#interest_val').val();
        let taken_ar    = $('#taken_val').val();
        let jt20_ar     = $('#20_val').val();
        let jt50_ar     = $('#50_val').val();

        table.ajax.url("{{ route('adm.leads.grab.datatables') }}/?interest="+interest_ar+"&taken="+taken_ar+"&jt20_ar="+jt20_ar+"&jt50_ar="+jt50_ar).load();
    }

    // Filter
    $(".btn_filter").on("click", function(){
        let fil_interest = $('#interest_val').val();
        let fil_taken    = $('#taken_val').val();
        let fil_20jt     = $('#20_val').val();
        let fil_50jt     = $('#50_val').val();
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
        } else if(fil_btn=='taken') {
            if(fil_taken==0) {
                $('#fil_taken').removeClass('btn-outline-primary');
                $('#fil_taken').addClass('btn-primary');
                $('#taken_val').val(1);
                donate_table();
            } else {
                $('#fil_taken').addClass('btn-outline-primary');
                $('#fil_taken').removeClass('btn-primary');
                $('#taken_val').val(0);
                donate_table();
            }
        }
    });


    function setInterest(id, status) {
        if(status==1) {
            var result = confirm("Set ke MENARIK?");
        } else {
            var result = confirm("Tidak jadi MENARIK?");
        }
        
        if (result) {
            $.ajax({
                type: "GET",
                url: "{{ route('adm.leads.grab.status') }}",
                data: {
                  "_token": "{{ csrf_token() }}",
                  "id": id,
                  "status": status,
                  "type": "interest"
                },
                success: function(data){
                    if(data.status=='success') {
                        // alert('BERHASIL, '+data.name+' dijadikan MENARIK');
                        $('#btninterest_'+id).html(data.btn);
                    } else {
                        alert('GAGAL menjadikan ke menarik');
                    }
                }
            });
        }
    }

    function setTaken(id, status) {
        if(status==1) {
            var result = confirm("Set ke Garap?");
        } else {
            var result = confirm("Tidak jadi MENARIK?");
        }
        
        if (result) {
            $.ajax({
                type: "GET",
                url: "{{ route('adm.leads.grab.status') }}",
                data: {
                  "_token": "{{ csrf_token() }}",
                  "id": id,
                  "status": status,
                  "type": "taken"
                },
                success: function(data){
                    if(data.status=='success') {
                        // alert('BERHASIL, '+data.name+' dijadikan GRRAP');
                        $('#btntaken_'+id).html(data.btn);
                    } else {
                        alert('GAGAL menjadikan ke GARAP');
                    }
                }
            });
        }
    }

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
