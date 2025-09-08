@extends('layouts.admin', [
    'second_title' => 'Banners',
    'header_title' => 'Daftar Banner',
    'sidebar_menu' => 'banners',
    'sidebar_submenu' => ''
])

@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('css_inline')
    <style>
        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
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
                            <li class="breadcrumb-item active" aria-current="page">Banners</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 text-end">
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_banner"><i class="fa fa-sync"></i>
                        Refresh</button>
                    <a href="{{ route('adm.banner.create') }}" class="btn btn-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="banner-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Links</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('js_inline')
    <script>
        $(function() {
            var table = $('#banner-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [[1, 'desc']],
                ajax: {
                    url: '{{ route("adm.banner.datatables") }}',
                    type: "GET",
                    error: function (xhr, error, thrown) {
                        console.log("AJAX Error:", xhr, error, thrown);
                        $('#banner-table').DataTable().clear().draw();
                        alert('Gagal memuat data. Silakan refresh halaman.');
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                    { data: 'title', name: 'title' },
                    { data: 'links', name: 'links', orderable: false, searchable: false },
                    { data: 'is_publish', name: 'is_publish', width: '10%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%' }
                ],
                language: {
                    emptyTable: "Tidak ada data banner",
                    zeroRecords: "Data banner tidak ditemukan"
                }
            });

            $('#refresh_table_banner').on('click', function() {
                table.ajax.reload();
            });

            $('#banner-table').on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Banner akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>

    <script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['type'] }}',
                title: '{{ session('message')['text'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
                },
                background: '{{ session('message')['type'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['type'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection