@extends('layouts.admin', [
    'second_title' => 'Donatur',
    'header_title' => 'Donatur',
    'sidebar_menu' => 'person',
    'sidebar_submenu' => 'donatur',
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
                <div id="filter-button" class="fc-rtl">
                    <button class="btn btn-outline-primary filter_donatur" id="filter-wa-aktif" data-id="wa-aktif"
                        data-val="0" title="Donatur yang memiliki no WhatsApp Aktif">WA Aktif</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-wa-mau" data-id="wa-mau"
                        data-val="0" title="Donatur yang ingin dihubungi WA">Mau</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-sultan500" data-id="sultan500"
                        data-val="0" title="Donatur dengan total donasi > 500 ribu">>500</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-sultan1000" data-id="sultan1000"
                        data-val="0" title="Donatur dengan total donasi > 1 juta">>1jt</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-sultan2000" data-id="sultan2000"
                        data-val="0" title="Donatur dengan total donasi > 2 juta">>2jt</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-sultan5000" data-id="sultan5000"
                        data-val="0" title="Donatur dengan total donasi > 5 juta">>5jt</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-setia" data-id="setia"
                        data-val="0" title="Donatur yang memiliki donasi terbayar lebih dari 2 kali">Setia</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-rutin" data-id="rutin"
                        data-val="0" title="Donatur Loyal atau memiliki jadwal donasi tetap">Rutin</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-muslim" data-id="muslim"
                        data-val="0" title="Donatur Muslim">Muslim</button>
                    <button class="btn btn-outline-primary filter_donatur" id="filter-dorman" data-id="dorman"
                        data-val="0" title="Donatur tidak aktif/dorman">Dorman</button>
                    <button class="btn btn-outline-primary filter_donatur_days" data-days="7" data-val="0" title="Donatur dibuat dalam 7 hari terakhir">7 Hari</button>
                    <button class="btn btn-outline-primary filter_donatur_days" data-days="10" data-val="0" title="Donatur dibuat dalam 10 hari terakhir">10 Hari</button>
                    <button class="btn btn-outline-primary filter_donatur_days" data-days="14" data-val="0" title="Donatur dibuat dalam 14 hari terakhir">14 Hari</button>
                    <a href="{{ route('adm.donatur.create') }}" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                    <a href="{{ route('adm.donatur.reset-cache') }}" class="btn btn-outline-info"><i
                            class="fa fa-sync mr-1"></i> Refresh Data</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>   
                        <th>Terakhir Donasi</th>
                        <th>Rangkuman Donasi</th>
                        <th>Riwayat Chat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="sultan_val" value="0">
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(".filter_donatur").on("click", function() {
            var fil_name = $(this).attr("data-id");
            var fil_val = $(this).attr("data-val");

            if (fil_name == 'wa-aktif') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    donatur_table();
                }
            } else if (fil_name == 'wa-mau') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    donatur_table();
                }
            } else if (fil_name == 'sultan500') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    $('#sultan_val').val(500);
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    $('#sultan_val').val(0);
                    donatur_table();
                }
            } else if (fil_name == 'sultan1000') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    $('#sultan_val').val(1000);
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    $('#sultan_val').val(0);
                    donatur_table();
                }
            } else if (fil_name == 'sultan2000') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    $('#sultan_val').val(2000);
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    $('#sultan_val').val(0);
                    donatur_table();
                }
            } else if (fil_name == 'sultan5000') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    $('#sultan_val').val(5000);
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    $('#sultan_val').val(0);
                    donatur_table();
                }
            } else if (fil_name == 'setia') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    donatur_table();
                }
            } else if (fil_name == 'muslim') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    donatur_table();
                }
            } else { // dorman
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    donatur_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    donatur_table();
                }
            }
        });

        $(".filter_donatur_days").on("click", function() {
            var fil_val = $(this).attr("data-val");
            
            // Unselect other day filters
            $(".filter_donatur_days").not(this).addClass('btn-outline-primary').removeClass('btn-primary').attr("data-val", "0");

            if (fil_val == 0) {
                $(this).removeClass('btn-outline-primary');
                $(this).addClass('btn-primary');
                $(this).attr("data-val", "1");
            } else {
                $(this).addClass('btn-outline-primary');
                $(this).removeClass('btn-primary');
                $(this).attr("data-val", "0");
            }
            donatur_table();
        });

        function donatur_table() {
            let wa_aktif = $('#filter-wa-aktif').attr("data-val");
            let wa_mau = $('#filter-wa-mau').attr("data-val");
            let sultan = $('#sultan_val').val();
            let setia = $('#filter-setia').attr("data-val");
            let rutin = $('#filter-rutin').attr("data-val");
            let muslim = $('#filter-muslim').attr("data-val");
            let dorman = $('#filter-dorman').attr("data-val");
            let days = 0;
            let active_days_filter = $(".filter_donatur_days[data-val='1']");
            if (active_days_filter.length > 0) {
                days = active_days_filter.data('days');
            }

            table.ajax.url("{{ route('adm.donatur.datatables') }}/?wa_aktif=" + wa_aktif + "&wa_mau=" + wa_mau +
                    "&sultan=" + sultan + "&setia=" + setia + "&rutin=" + rutin + "&muslim=" + muslim + "&dorman=" + dorman + "&days=" + days)
                .load();
        }

        var table = $('#table-donatur').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            responsive: true,
            order: [],
            ajax: "{{ route('adm.donatur.datatables') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'last_donate',
                    name: 'last_donate'
                },
                {
                    data: 'donate_summary',
                    name: 'donate_summary'
                },
                {
                    data: 'chat',
                    name: 'chat'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $('#table-donatur thead tr').clone(true).appendTo('#table-donatur thead');
        $('#table-donatur tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title +
                '" />');

            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
    </script>
@endsection
