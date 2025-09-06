@extends('layouts.admin', [
    'second_title' => 'Tautan Pendek',
    'header_title' => 'Tautan Pendek',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'shorten_link',
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

        /* Tambahkan di bagian CSS Anda */
        .short-url-input {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .copy-short-url {
            transition: all 0.2s;
        }

        .copy-short-url:hover {
            background-color: #e9ecef;
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
                            <li class="breadcrumb-item active" aria-current="page">Tautan Pendek</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    {{-- <button class="btn btn-outline-primary">Biasa</button>
                    <button class="btn btn-outline-primary">Terverifikasi</button>
                    <button class="btn btn-outline-primary">Banned</button>
                    <button class="btn btn-outline-primary">Lembaga</button> --}}
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_short-link"><i class="fa fa-sync"></i>
                        Refresh</button>
                    <a href="{{ route('adm.shorten-link.create') }}" target="_blank" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-short-link" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Tautan</th>
                        <th>Tautan Link</th>
                        <th>Kode</th>
                        <th>Tautan Pendek</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection


@section('js_plugins')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#table-short-link').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [0, 'asc']
                ],
                ajax: {
                    url: "{{ route('adm.short-link.datatables') }}",
                    type: "GET",
                    error: function(xhr, error, thrown) {
                        console.log("AJAX Error:", xhr, error, thrown);
                        $('#table-short-link').DataTable().clear().draw();
                        alert('Failed to load data. Please check console for details.');
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'direct_link',
                        name: 'direct_link'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'short_url_column',
                        name: 'short_url_column'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                initComplete: function() {
                    console.log('DataTable initialized successfully');
                },
                drawCallback: function(settings) {
                    console.log('Draw occurred:', settings);
                }
            });

            $(document).on('click', '.copy-short-url', function() {
                var urlToCopy = $(this).data('url');
                var $temp = $('<input>');
                $('body').append($temp);
                $temp.val(urlToCopy).select();
                document.execCommand('copy');
                $temp.remove();

                // Show feedback
                $(this).html('<i class="fas fa-check"></i>');
                setTimeout(() => {
                    $(this).html('<i class="fas fa-copy"></i>');
                }, 2000);

                toastr.success('URL copied to clipboard!');
            });

            // SweetAlert untuk konfirmasi delete
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $('#table-donatur thead tr').clone(true).appendTo('#table-donatur thead');
            $('#table-donatur tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html(
                    '<input type="text" class="form-control form-control-sm" placeholder="Search ' +
                    title +
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
        });

        $("#refresh_table_short-link").on("click", function() {
            table.ajax.reload();
        });
    </script>
@endsection
