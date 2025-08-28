@extends('layouts.admin', [
    'second_title' => 'Penyaluran',
    'header_title' => 'Penyaluran Program',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'program_payout',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('css_inline')
    <style type="text/css">
        .btn-xs {
            padding: 3px !important;
            font-size: 13px !important;
        }
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
                            <li class="breadcrumb-item active" aria-current="page">Penyaluran</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-sm btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <!-- <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-ads" data-id="bca">ADS</button>
                        <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-fee" data-id="bca">Payment Fee</button>
                        <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-opera" data-id="bca">Operational</button>
                        <button class="btn btn-sm btn-outline-primary filter_payment" id="filter-others" data-id="bca">Others</button> -->
                    <a href="{{ route('adm.payout.create') }}" class="btn btn-sm btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Program</th>
                        <th>Tanggal</th>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            columnDefs: [{
                    "width": "12%",
                    "targets": 0
                },
                {
                    "width": "14%",
                    "targets": 3
                }
            ],
            // order: [[4, 'desc']],
            order: [],
            ajax: "{{ route('adm.payout.datatables') }}",
            columns: [
                {
                    data: 'nominal',
                    name: 'nominal'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'program_title',
                    name: 'program_title'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    </script>

    <script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['status'] }}',
                title: '{{ session('message')['message'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
                },
                background: '{{ session('message')['status'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['status'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection
