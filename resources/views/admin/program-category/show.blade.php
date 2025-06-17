@extends('layouts.admin', [
    'second_title' => 'Kategori Program',
    'header_title' => 'Tambah Kategori',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'category',
])

@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .big-checkbox .form-check-input {
            width: 16px;
            height: 16px;
            margin-top: 3px !important;
        }

        .big-checkbox .form-check-label {
            margin-left: 6px;
        }

        .big-checkbox {
            min-height: auto !important;
        }
    </style>
@endsection


@section('content')
    <div class="main-card mb-2 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.program-category.index') }}">Kategori</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary" href={{ route('adm.program-category.index') }}>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Icon Kategori</th>
                        <td><img src="{{ asset('public/images/categories/' . $category->icon) }}" alt=""></td>
                    </tr>
                    <tr>
                        <th>Nama Kategori</th>
                        <td>{{ ucwords($category->name) }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Ditampilkan</th>
                        <td>{{ $category->is_show ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    <tr>
                        <th>Total Program</th>
                        <td>{{ $totalPrograms->count() ?? '0' }} Program</td>
                    </tr>
                    <tr>
                        <th>Total Donasi</th>
                        <td>
                            Rp. {{ number_format($totalDonations, 0, ',', '.') }}
                        </td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <div class="main-card mb-2 mt-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <h5 class="fw-semibold">Program</h5>
                </div>
                <div class="col-10 fc-rtl">
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_category-program"><i
                            class="fa fa-sync"></i>
                        Refresh</button>
                    <a href="{{ route('adm.program.create') }}" class="btn btn-primary" id="filter-54">
                        <i class="fa fa-plus mr-1" id="filter-54-icon"></i> Tambah
                    </a>
                </div>
                <div class="divider"></div>
                <table id="table-category-programs" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Lembaga</th>
                            <th>Donasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('js_inline')
    <script type="text/javascript">
        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
        }

        var table = $('#table-category-programs').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            ajax: {
                url: "{{ route('adm.program-in-detail-category.datatables') }}",
                type: "GET",
                data: {
                    category_id: "{{ $category->id }}"
                }
            },
            columns: [{
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'organization',
                    name: 'organization'
                },
                {
                    data: 'donate',
                    name: 'donate'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            columnDefs: [{
                "width": "22%",
                "targets": 0
            }],
            order: []
        });

        $('#refresh_table_category-program').on('click', function() {
            table.ajax.reload(null, false);
        });
    </script>
@endsection
