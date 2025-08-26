@extends('layouts.admin', [
    'second_title' => 'Program',
    'header_title' => 'List Kabar Terbaru',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'program_info',
])

@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.program.index') }}">Program</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kabar Terbaru</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('adm.program-info.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-1"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="program-info-table" class="table table-hover table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('js_inline')
    <script>
        $(document).ready(function() {
            $('#program-info-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('adm.program-info.datatables') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'program_title', name: 'program.title' },
                    { data: 'title', name: 'title' },
                    {
                        data: 'date',
                        name: 'date',
                        render: function(data) {
                            if (!data) return '-';
                            const date = new Date(data);
                            const options = {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric',
                                timeZone: 'UTC'
                            };
                            return date.toLocaleDateString('id-ID', options);
                        }
                    },
                    { data: 'is_publish', name: 'is_publish', render: function(data) {
                        return data == 1 ? '<span class="badge badge-success">Publish</span>' : '<span class="badge badge-secondary">Draft</span>';
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#program-info-table').on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form-' + id).submit();
                    }
                })
            });
        });
    </script>
@endsection