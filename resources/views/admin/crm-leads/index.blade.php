@extends('layouts.admin', [
    'second_title' => 'Leads CRM',
    'header_title' => 'Leads CRM',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'crm-leads',
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

        .leads-box {
            background-color: #f5f7fa;
            color: rgb(42, 28, 28);
            width: 160px;
            border-radius: 10px;
            height: 50px;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .leads-box:hover {
            background-color: #e9ecef;
            transform: scale(1.05);
        }

        .create-leads-box {
            outline: #c1c3c7 1px dashed;
            color: rgb(42, 28, 28);
            width: 160px;
            border-radius: 10px;
            height: 50px;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .create-leads-box:hover {
            background-color: #e9ecef;
            transform: scale(1.05);
        }
    </style>
@endsection

@section('css_inline')
    <style type="text/css">
        .btn-xs {
            padding: 3px !important;
            font-size: 13px !important;
        }

        .card-body {
            max-width: 83vw;
        }

        /* Container utama untuk seluruh kanban */
        .kanban-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
            width: 102%;
            max-width: 100vw;
            /* Maksimum lebar viewport */
            overflow-x: auto;
            /* Scroll horizontal jika diperlukan */
            margin-left: -15px;
            /* Kompensasi padding container */
            margin-right: -15px;
            /* Kompensasi padding container */
            padding: 0 15px;
            /* Padding untuk konten */
        }

        /* Baris kanban */
        .kanban-row {
            display: flex;
            gap: 5px;
            min-width: fit-content;
            /* Lebar minimum sesuai konten */
        }

        /* Papan kanban utama */
        .kanban-board {
            display: flex;
            flex-direction: row;
            /* gap: 5px; */
            padding: 5px;
            background: #f5f5f5;
            border-radius: 8px;
            min-height: 800px;
            min-width: 100%;
            /* Minimum lebar penuh container */
        }

        /* Kolom individual */
        .kanban-column {
            min-width: 280px;
            width: 280px;
            background: #f8f9fa;
            border-radius: 5px;
            padding: 2px;
            /* Mengurangi padding dari 10px ke 5px */
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border: 1px solid #dee2e6;
            /* Menambahkan border */
        }

        .kanban-column-header {
            text-align: center;
            padding: 2px;
            /* Mengurangi padding dari 10px ke 8px */
            /* margin-bottom: 100px; */
            /* Mengurangi margin dari 15px ke 10px */
            background: #e9ecef;
            border-radius: 5px;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
            /* Menambahkan border bawah */
        }

        .kanban-column-body {
            flex-grow: 1;
            min-height: 200px;
            padding: 3px;
            /* Mengurangi padding dari 5px ke 3px */
            background: #fff;
            border-radius: 5px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            /* Menambahkan border */
        }

        .kanban-card {
            background: white;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: move;
            border-left: 4px solid #4285f4;
        }

        .kanban-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .sortable-ghost {
            opacity: 0.5;
            background: #dee2e6;
            border: 2px dashed #4285f4;
        }

        .sortable-chosen {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .kanban-card h6 {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .kanban-card p {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .kanban-card .btn-xs {
            padding: 2px 5px !important;
            font-size: 11px !important;
        }

        .dropdown-menu {
            transform: translateX(-55%) translateY(30%) !important;
        }

        /* Style untuk integrasi Select2 dengan dropdown Bootstrap */
        .dropdown-menu .select2-container {
            width: 100% !important;
            padding: 0.5rem;
        }

        .dropdown-menu .select2-search--dropdown {
            padding: 0.5rem;
        }

        .dropdown-menu .select2-results {
            padding: 0;
        }

        .dropdown-menu .select2-results__option {
            padding: 0.5rem 1rem;
        }

        .select2-dropdown {
            border: none;
            box-shadow: none;
        }

        .select2-search--dropdown {
            border-bottom: 1px solid #e9ecef;
        }

        .prospect-card-adder {
            background-color: white;
            width: 100%;
            height: 100%;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
            color: #4285f4;
        }

        .prospect-card-adder:hover {
            background-color: #fffefe;
            color: rgba(66, 133, 244, 0.5);
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .prospect-card-adder:hover i {
            transform: scale(1.2);
        }

        .kanban-empty-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }

        .kanban-column-header {
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .kanban-card {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endsection

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-middle">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Leads CRM</li>
                            @if (request()->query('leads'))
                                <li class="breadcrumb-item active" aria-current="page">{{ request()->query('leads') }}</li>
                            @endif
                        </ol>
                    </nav>
                    <div class="d-flex flex-col gap-2">
                        @if (request()->query('leads'))
                            <a id="crm_prospect-id" href="/adm/crm-prospect/create?leads_id={{ request()->query('leads') }}"
                                class="btn btn-primary">
                                Tambah Prospect
                            </a>
                        @endif
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="leadsDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="width: 100%; text-align: left;">
                                Pilih Journey
                            </button>
                            <div class="dropdown-menu" aria-labelledby="leadsDropdown" style="width: 100%;">
                                <button class="dropdown-item text-success" type="button" data-toggle="modal"
                                    data-target="#addLeadModal">
                                    <i class="fa fa-plus mr-2"></i> Tambah Journey Baru
                                </button>
                                <div class="dropdown-divider"></div>
                                <select class="form-control select2-dropdown" id="leadsSelect"
                                    style="width: 100%; border: none;">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>

            <!-- Container untuk multiple rows -->
            <div class="kanban-container">
                @if (!request()->query('leads'))
                    <div class="kanban-col d-flex flex-column justify-content-center gap-3 align-items-center justify-items-center"
                        style="height: 75vh; width: 100%;">
                        <h4>Pilih Leads</h4>
                        <div class="d-flex flex-row gap-5 flex-wrap justify-content-center align-items-center"
                            style="width: 80vh;">
                            @foreach ($leads as $lead)
                                <div onclick="window.location.href='/adm/crm-leads?leads={{ $lead->name }}'"
                                    class="d-flex flex-column justify-content-center align-items-center leads-box">
                                    {{ $lead->name }}
                                </div>
                            @endforeach
                            <div data-toggle="modal" data-target="#addLeadModal"
                                class="d-flex flex-row gap-2 justify-content-center align-items-center create-leads-box">
                                <i class="fa fa-plus"></i>
                                <span>Tambah Leads</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="kanban-row">
                        <div class="kanban-board">
                            <!-- Kolom kanban akan di-generate secara dinamis oleh JavaScript -->
                            <a class="kanban-column" id="add-pipeline"
                                href="/adm/crm-pipeline/create?leads={{ request()->query('leads') }}"
                                style="text-decoration: none;">
                                <div class="prospect-card-adder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="2" width="20" height="20" rx="4" ry="4"
                                            fill="none" stroke="currentColor" stroke-width="2" />
                                        <line x1="12" y1="8" x2="12" y2="16"
                                            stroke="currentColor" stroke-width="2" />
                                        <line x1="8" y1="12" x2="16" y2="12"
                                            stroke="currentColor" stroke-width="2" />
                                    </svg>
                                    <h5>Tambah Pipeline</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content_modal')
    <style>
        /* Toast Notification Styling */
        #toastNotification {
            min-width: 300px;
            max-width: 100%;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }

        #toastNotification .toast-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            padding: 0.75rem 1rem;
        }

        #toastNotification .toast-body {
            background-color: #ffffff;
            /* Warna dark modern */
            color: #272121;
            padding: 1rem;
            border-radius: 0 0 .25rem .25rem;
        }

        /* Warna khusus untuk tipe notifikasi */
        #toastNotification .toast-header.bg-success {
            background-color: #28a745 !important;
        }

        #toastNotification .toast-header.bg-danger {
            background-color: #dc3545 !important;
        }

        #toastNotification .toast-header.bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        #toastNotification .toast-header.bg-info {
            background-color: #17a2b8 !important;
        }

        /* Animasi toast */
        .toast {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .select2-container {
            z-index: 999999 !important;
        }

        .select2-dropdown {
            z-index: 999999 !important;
        }

        .select2-results__options {
            z-index: 999999 !important;
        }

        .modal {
            z-index: 1050 !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        .select2-container--open {
            z-index: 999999 !important;
        }
    </style>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLeadModal" tabindex="-1" role="dialog" aria-labelledby="addLeadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('adm.crm-leads.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLeadModalLabel">Tambah Leads Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Leads</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukkan nama Leads disini" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Masukkan deskripsi Leads disini">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('js_inline')
    <script type="text/javascript">
        function openDonaturLoyalModal() {
            let myModal = new bootstrap.Modal(document.getElementById('modal_donatur_loyal'));
            myModal.show();
        }

        $(document).ready(function() {
            // Inisialisasi Select2
            $('#leadsSelect').select2({
                placeholder: "Cari atau pilih Journey...",
                dropdownParent: $('#leadsDropdown').next('.dropdown-menu'),
                width: 'resolve',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.crm-leads.list') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                    type: item.type
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Handle ketika leads dipilih
            $('#leadsSelect').on('select2:select', function(e) {
                var data = e.params.data;
                $('#add-pipeline').attr('href', '/adm/crm-pipeline/create?leads=' + encodeURIComponent(data
                    .text));
                $('#crm_prospect-id').attr('href', '/adm/crm-prospect/create?leads=' + encodeURIComponent(
                    data.text))
                // console.log('Selected lead:', data.text);
                // Lakukan sesuatu dengan data leads yang dipilih
                // Contoh: window.location.href = '/leads/' + data.text;
                // history.pushState(null, null, `?leads=${data.text.toLowerCase()}`);
            });

            // Buka dropdown ketika tombol dropdown diklik
            $('#leadsDropdown').on('click', function() {
                $(this).next('.dropdown-menu').find('.select2-container').addClass(
                    'select2-container--open');
            });
        });

        $('#leadsSelect').on('select2:select', function(e) {
            var data = e.params.data;
            if (data.type) {
                window.location.href = '/adm/crm-pipeline?name=' + encodeURIComponent(data.name);
            }
        });

        document.getElementById('status_is_active').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('_status').textContent = 'Aktif';
                document.getElementById('status_is_active_hidden').value = '1';
            } else {
                document.getElementById('_status').textContent = 'Tidak Aktif';
                document.getElementById('status_is_active_hidden').value = '0';
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#program-select2").select2({
                dropdownParent: $('#modal_donatur_loyal'),
                placeholder: 'Cari Program',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.program.select2.all') }}",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data, params) {
                        var items = $.map(data.data, function(obj) {
                            let program_name = obj.title;
                            obj.id = obj.id;
                            obj.text = `${program_name}`
                            return obj;
                        });
                        params.page = params.page || 1
                        // console.log(items);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    },
                },
                templateResult: function(item) {
                    // console.log(item);
                    // No need to template the searching text
                    if (item.loading) {
                        return item.text;
                    }

                    var term = select2_query.term || '';
                    // var $result = markMatch(item.text, term);
                    var $result = item.text,
                        term
                    return $result;
                },
                language: {
                    searching: function(params) {
                        // Intercept the query as it is happening
                        select2_query = params
                        // Change this to be appropriate for your application
                        return 'Searching...';
                    }
                }
            });

            $("#donatur-select2").select2({
                dropdownParent: $('#modal_donatur_loyal'),
                placeholder: 'Cari Donatur',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.donatur.select2.all') }}",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data, params) {
                        var items = $.map(data.data, function(obj) {
                            let donatur_name = obj.name;
                            obj.id = obj.id;
                            obj.text = `${donatur_name}`
                            return obj;
                        });
                        params.page = params.page || 1
                        // console.log(items);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    },
                },
                templateResult: function(item) {
                    // console.log(item);
                    // No need to template the searching text
                    if (item.loading) {
                        return item.text;
                    }

                    var term = select2_query.term || '';
                    // var $result = markMatch(item.text, term);
                    var $result = item.text,
                        term
                    return $result;
                },
                language: {
                    searching: function(params) {
                        // Intercept the query as it is happening
                        select2_query = params
                        // Change this to be appropriate for your application
                        return 'Searching...';
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Variabel untuk menyimpan data
            let leadsData = {};
            let currentLeadsId = '{{ request()->query('leads') ?? 'null' }}';
            let pipelines = []; // Untuk menyimpan daftar pipeline

            // Fungsi untuk mengambil data dari server
            function fetchLeadsData() {
                if (!currentLeadsId) return;

                $.ajax({
                    url: '{{ url('adm/crm-pipeline/crm-pipeline-all') }}/' + currentLeadsId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Data leads berhasil diambil:', response);
                        leadsData = response.data;
                        pipelines = leadsData.crm_pipelines || [];

                        // Render kolom kanban berdasarkan pipeline
                        renderKanbanColumns();

                        // Render prospects untuk setiap pipeline
                        renderPipelinesAndProspects();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mengambil data leads:', error);
                        // Fallback ke data dummy jika diperlukan
                        leadsData = getFallbackData();
                        pipelines = leadsData.crm_pipelines || [];
                        renderKanbanColumns();
                        renderPipelinesAndProspects();
                    }
                });
            }

            // Fungsi untuk render kolom kanban berdasarkan pipeline
            function renderKanbanColumns() {
                const $kanbanBoard = $('.kanban-board');

                // Kosongkan semua kolom kecuali tombol tambah
                $kanbanBoard.children().not('.kanban-column[href]').remove();

                // Fungsi untuk memotong teks dan menambahkan tooltip
                const truncateTextWithTooltip = (text, maxWords) => {
                    if (!text) return 'Tidak ada deskripsi';

                    const words = text.split(' ');
                    if (words.length <= maxWords) return text;

                    const truncated = words.slice(0, maxWords).join(' ') + '...';
                    return `<span title="${text.replace(/"/g, '&quot;')}" style="cursor: help;">${truncated}</span>`;
                };

                // Buat kolom untuk setiap pipeline
                pipelines.forEach(pipeline => {
                    const columnId = `pipeline-${pipeline.id}-column`;

                    const $column = $(`
                        <div class="kanban-column">
                            <div class="kanban-column-header">
                                <a href="/adm/crm-pipeline/${pipeline.id}/edit/?leads=${currentLeadsId}">
                                    <h6 class="fw-bold">${pipeline.name}</h6>
                                </a>
                                <small>${truncateTextWithTooltip(pipeline.description, 5)}</small>
                                <div class="mt-1">
                                    <small class="text-muted">Deals: ${pipeline.percentage_deals}%</small>
                                </div>
                            </div>
                            <div class="kanban-column-body" id="${columnId}"></div>
                        </div>
                    `);

                    // Sisipkan sebelum tombol tambah
                    $column.insertBefore($kanbanBoard.find('.kanban-column[href]'));
                });
            }

            // Fungsi fallback jika API gagal (opsional)
            function getFallbackData() {
                console.warn('Menggunakan data fallback');
                return {
                    id: 1,
                    name: "Sample Leads",
                    crm_pipelines: [{
                        id: 1,
                        name: "Sample Pipeline",
                        description: "Deskripsi contoh",
                        percentage_deals: 50,
                        crm_prospects: [{
                            id: 1,
                            name: "Sample Prospect",
                            description: "Deskripsi prospect",
                            nominal: 1000000
                        }]
                    }]
                };
            }

            // Render prospects untuk setiap pipeline
            function renderPipelinesAndProspects() {
                pipelines.forEach(pipeline => {
                    const columnId = `pipeline-${pipeline.id}-column`;
                    const $columnBody = $(`#${columnId}`);

                    // Kosongkan kolom terlebih dahulu
                    $columnBody.empty();

                    // Tambahkan prospect cards
                    pipeline.crm_prospects.forEach(prospect => {
                        const prospectCard = createProspectCard(pipeline, prospect);
                        $columnBody.append(prospectCard);
                    });
                });

                // Inisialisasi SortableJS untuk memungkinkan drag and drop prospect antar pipeline
                initSortable();
            }

            // Fungsi untuk membuat card prospect
            function createProspectCard(pipeline, prospect) {
                // Fungsi untuk memotong teks dan menambahkan tooltip
                const truncateTextWithTooltip = (text, maxWords) => {
                    if (!text) return 'Tidak ada deskripsi';

                    const words = text.split(' ');
                    if (words.length <= maxWords) return text;

                    const truncated = words.slice(0, maxWords).join(' ') + '...';
                    return `<span title="${text.replace(/"/g, '&quot;')}" style="cursor: help;">${truncated}</span>`;
                };

                return $(`
                    <div class="kanban-card"
                         data-prospect-id="${prospect.id}"
                         data-pipeline-id="${pipeline.id}">
                        <div class="d-flex justify-content-between align-items-start flex-column">
                            <span class="badge bg-light text-dark mb-2">
                                ${formatDate(prospect.created_at)}
                            </span>
                            <div>
                                <h6 class="mb-1"><a href="/adm/crm-prospect/${prospect.id}?leads=${currentLeadsId}">${prospect.name}</a></h6>
                                <p class="mb-1">${truncateTextWithTooltip(prospect.description, 10)}</p>
                                <small class="text-muted">Nominal: ${formatCurrency(prospect.nominal)}</small>
                            </div>
                        </div>
                    </div>
                `);
            }

            // Format tanggal
            function formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            // Format mata uang
            function formatCurrency(amount) {
                if (!amount) return 'Rp 0';
                return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Inisialisasi drag and drop untuk prospect
            function initSortable() {
                pipelines.forEach(pipeline => {
                    const columnId = `pipeline-${pipeline.id}-column`;
                    const el = document.getElementById(columnId);

                    if (el) {
                        new Sortable(el, {
                            group: 'prospects',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            onEnd: function(evt) {
                                // Jika prospect dipindahkan ke pipeline yang berbeda
                                if (evt.from !== evt.to) {
                                    const prospectId = evt.item.dataset.prospectId;
                                    const newPipelineId = evt.to.id.replace('pipeline-', '')
                                        .replace('-column', '');
                                    updateProspectPipeline(prospectId, newPipelineId);
                                }
                            }
                        });
                    }
                });
            }

            // Update pipeline prospect ketika dipindahkan
            function updateProspectPipeline(prospectId, newPipelineId) {
                $.ajax({
                    url: `/adm/crm-prospect/${prospectId}/update-pipeline`,
                    method: 'POST',
                    data: {
                        new_pipeline_id: newPipelineId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Response update:', response);
                        if (response.success) {
                            toastr.success('Prospect berhasil dipindahkan');
                            // Refresh data setelah update berhasil
                            fetchLeadsData();
                        } else {
                            toastr.error('Gagal memindahkan prospect');
                            // Kembalikan ke posisi semula jika gagal
                            fetchLeadsData();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error update:', error);
                        toastr.error('Terjadi kesalahan saat memindahkan prospect');
                        // Kembalikan ke posisi semula jika error
                        fetchLeadsData();
                    }
                });
            }

            // Handler untuk tombol hapus prospect
            $(document).on('click', '.delete-prospect', function() {
                const prospectId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus prospect ini?')) {
                    deleteProspect(prospectId);
                }
            });

            // Fungsi untuk menghapus prospect
            function deleteProspect(prospectId) {
                $.ajax({
                    url: `/adm/crm-prospect/${prospectId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Prospect berhasil dihapus');
                            fetchLeadsData();
                        } else {
                            toastr.error('Gagal menghapus prospect');
                            fetchLeadsData();
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Terjadi kesalahan saat menghapus prospect');
                    }
                });
            }

            // Pertama kali load, ambil data dari server
            fetchLeadsData();

            // Handle ketika leads dipilih dari dropdown
            $('#leadsSelect').on('select2:select', function(e) {
                const data = e.params.data;
                if (data.text) {
                    currentLeadsId = data.text;
                    fetchLeadsData();
                    history.pushState(null, null, `?leads=${data.text.toLowerCase()}`);
                    window.location.reload();
                }
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
