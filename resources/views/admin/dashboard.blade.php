@extends('layouts.admin', [
    'second_title'    => 'Dashboard Admin',
    'header_title'    => 'Dashboard Admin',
    'sidebar_menu'    => 'dashboard',
    'sidebar_submenu' => 'Dashboard Admin'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12 col-xl-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Donate Report</h5>
                        <!-- <div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 m-0">
                            <div style="height: 227px;">
                                <canvas id="line-chart"></canvas>
                            </div>
                        </div>
                        <h5 class="card-title">Target Sales</h5> -->
                        <div class="mt-3 row">
                            <div class="col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>{{ date('d-m-Y') }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-1 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-2 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-3 day')) }}</th>
                                            <th>{{ date('d-m-Y', strtotime(date('Y-m-d').'-4 day')) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JML Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success[0]) }}</td>
                                            <td>{{ number_format($donate_success[1]) }}</td>
                                            <td>{{ number_format($donate_success[2]) }}</td>
                                            <td>{{ number_format($donate_success[3]) }}</td>
                                            <td>{{ number_format($donate_success[4]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rp Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success_rp[0]) }}</td>
                                            <td>{{ number_format($donate_success_rp[1]) }}</td>
                                            <td>{{ number_format($donate_success_rp[2]) }}</td>
                                            <td>{{ number_format($donate_success_rp[3]) }}</td>
                                            <td>{{ number_format($donate_success_rp[4]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar</td>
                                            <td>{{ number_format($donate_draft[0]) }}</td>
                                            <td>{{ number_format($donate_draft[1]) }}</td>
                                            <td>{{ number_format($donate_draft[2]) }}</td>
                                            <td>{{ number_format($donate_draft[3]) }}</td>
                                            <td>{{ number_format($donate_draft[4]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar Rp</td>
                                            <td>{{ number_format($donate_draft_rp[0]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[1]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[2]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[3]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[4]) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- <div class="col-sm-12 col-md-4">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-numbers text-dark">65%</div>
                                            </div>
                                        </div>
                                        <div class="widget-progress-wrapper mt-1">
                                            <div class="progress-bar-xs progress-bar-animated-alt progress">
                                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;"></div>
                                            </div>
                                            <div class="progress-sub-label">
                                                <div class="sub-label-left font-size-md">Sales</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-numbers text-dark">22%</div>
                                            </div>
                                        </div>
                                        <div class="widget-progress-wrapper mt-1">
                                            <div class="progress-bar-xs progress-bar-animated-alt progress">
                                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 22%;"></div>
                                            </div>
                                            <div class="progress-sub-label">
                                                <div class="sub-label-left font-size-md">Profiles</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-numbers text-dark">83%</div>
                                            </div>
                                        </div>
                                        <div class="widget-progress-wrapper mt-1">
                                            <div class="progress-bar-xs progress-bar-animated-alt progress">
                                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="83" aria-valuemin="0" aria-valuemax="100" style="width: 83%;"></div>
                                            </div>
                                            <div class="progress-sub-label">
                                                <div class="sub-label-left font-size-md">Tickets</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-6">
                <div class="main-card mb-3 card">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-success"></div>
                                        <i class="lnr-heart text-success"></i>
                                    </div>
                                    <div class="widget-numbers">{{ str_replace(',', '.', number_format($sum_donate)) }}</div>
                                    <div class="widget-subheading">Total Donasi</div>
                                    <!-- <div class="widget-description text-success">
                                        <i class="fa fa-angle-up"></i>
                                        <span class="pl-1">175.5%</span>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-info"></div>
                                        <i class="lnr-gift text-info"></i>
                                    </div>
                                    <div class="widget-numbers fs-4">Rp. {{ str_replace(',', '.', number_format($sum_paid)) }}</div>
                                    <div class="widget-subheading">Total Dibayar</div>
                                    <!-- <div class="widget-description text-info">
                                        <i class="fa fa-arrow-right"></i>
                                        <span class="pl-1">175.5%</span>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-warning"></div>
                                        <i class="lnr-eye text-warning"></i>
                                    </div>
                                    <div class="widget-numbers">{{ str_replace(',', '.', number_format($sum_page_viewed)) }}</div>
                                    <div class="widget-subheading">Halaman Program Dilihat</div>
                                    <!-- <div class="widget-description text-info">
                                        <i class="fa fa-arrow-right"></i>
                                        <span class="pl-1">175.5%</span>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-chart widget-chart-hover br-br">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg bg-primary"></div>
                                        <i class="lnr-cart text-primary"></i>
                                    </div>
                                    <div class="widget-numbers">{{ str_replace(',', '.', number_format($sum_transaction)) }}</div>
                                    <div class="widget-subheading">Total Transaksi</div>
                                    <!-- <div class="widget-description text-warning">
                                        <span class="pr-1">175.5%</span>
                                        <i class="fa fa-arrow-left"></i>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Program
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">
                                <button class="active btn btn-info" id="refresh_table">Refresh Table</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive px-3 mt-3 mb-3">
                        <table id="table-donatur" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Lembaga</th>
                                    <th>Donasi</th>
                                    <th>Statistik</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
        autoWidth: false,
        pageLength : 10,
        columnDefs: [
            { "width": "22%", "targets": 0 }
        ],
        order: [[4, 'desc']],
        ajax: "{{ route('adm.program.datatables').'/?is_publish=1' }}",
        columns: [
            {data: 'title', name: 'title'},
            {data: 'nominal', name: 'nominal'},
            {data: 'status', name: 'status'},
            {data: 'organization', name: 'organization'},
            {data: 'donate', name: 'donate'},
            {data: 'stats', name: 'stats'},
            // {
            //     data: 'action', 
            //     name: 'action', 
            //     orderable: false, 
            //     searchable: false
            // },
        ]
    });

    $("#refresh_table").on("click", function() {
        table.ajax.reload();
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
