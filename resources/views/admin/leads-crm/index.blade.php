@extends('layouts.admin', [
    'second_title' => 'Leads CRM',
    'header_title' => 'Leads CRM',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'crm-pipeline',
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
            gap: 5px;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 8px;
            min-height: 700px;
            min-width: 100%;
            /* Minimum lebar penuh container */
        }

        /* Kolom individual */
        .kanban-column {
            min-width: 280px;
            width: 280px;
            /* Lebar tetap untuk kolom */
            background: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            /* Mencegah kolom menyusut */
        }

        .kanban-column-header {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            background: #e9ecef;
            border-radius: 5px;
            font-weight: bold;
        }

        .kanban-column-body {
            flex-grow: 1;
            min-height: 200px;
            padding: 5px;
            background: #fff;
            border-radius: 5px;
            overflow-y: auto;
            /* Scroll vertikal jika diperlukan */
        }

        .kanban-card {
            background: white;
            border-radius: 5px;
            padding: 15px;
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
            margin-bottom: 15px;
        }
        
        .kanban-card {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                        </ol>
                    </nav>
                    <div class="d-flex flex-col gap-2">
                        <a href="{{ route('adm.crm-prospect-activity.create', ['type' => request()->query('type')]) }}"
                            class="btn btn-primary">
                            Tambah Prospect Activity
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="leadsDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="width: 100%; text-align: left;">
                                Pilih Leads
                            </button>
                            <div class="dropdown-menu" aria-labelledby="leadsDropdown" style="width: 100%;">
                                <button class="dropdown-item text-success" type="button" data-toggle="modal"
                                    data-target="#addLeadModal">
                                    <i class="fa fa-plus mr-2"></i> Tambah Leads Baru
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
                <div class="kanban-row">
                    <div class="kanban-board">
                        <!-- Kolom kanban akan di-generate secara dinamis oleh JavaScript -->
                        <a class="kanban-column"
                            href="{{ route('adm.crm-prospect.create', ['type' => request()->query('type')]) }}"
                            style="text-decoration: none;">
                            <div class="prospect-card-adder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="2" width="20" height="20" rx="4" ry="4"
                                        fill="none" stroke="currentColor" stroke-width="2" />
                                    <line x1="12" y1="8" x2="12" y2="16" stroke="currentColor"
                                        stroke-width="2" />
                                    <line x1="8" y1="12" x2="16" y2="12" stroke="currentColor"
                                        stroke-width="2" />
                                </svg>
                                <h5>Tambah Prospect</h5>
                            </div>
                        </a>
                    </div>
                </div>
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
                <form action="{{ route('adm.crm-pipeline.store') }}" method="post">
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
                            <label for="type" class="form-label">Tipe Leads</label>
                            <input type="text" class="form-control" id="type" name="type"
                                placeholder="Masukkan Type Leads disini" value={{ old('type') }} required>
                        </div>
                        <div class="mb-3">
                            <label for="percentage_deals" class="form-label">Percentage Deals</label>
                            <input type="number" class="form-control" id="percentage_deals" name="percentage_deals"
                                min="1" max="100" placeholder="Masukkan persentase deals disini"
                                value="{{ old('percentage_deals') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Masukkan deskripsi Leads disini">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-check form-switch mt-2 ml-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="status_is_active"
                                value="1" checked>
                            <label class="form-check-label" for="status_is_active">Status Leads (<span
                                    id="_status">Aktif</span>)</label>
                            <input type="hidden" name="is_active_hidden" id="status_is_active_hidden" value="1">
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
            // Variabel untuk menyimpan data leads
            let leadsData = [];

            // Fungsi untuk mengambil data dari server
            function fetchLeadsData() {
                $.ajax({
                    url: '{{ route('adm.leads-crm.list') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // console.log('Data berhasil diambil:', response);

                        // Transform data untuk disesuaikan dengan format yang dibutuhkan
                        leadsData = response.data.map(lead => {
                            return {
                                id: lead.id,
                                name: lead.donatur_data.name,
                                program: lead.program_data.title,
                                phone: lead.donatur_data
                                    .telp, // Anda bisa menambahkan field phone jika ada
                                stage: lead.lead_stage,
                                donatur_data: lead.donatur_data,
                                program_data: lead.program_data,
                                description: lead.description,
                                created_at: lead.created_at
                            };
                        });

                        renderCards();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mengambil data:', error);
                        // Fallback ke data dummy jika diperlukan
                        leadsData = getFallbackData();
                        renderCards();
                    }
                });
            }

            // Fungsi fallback jika API gagal (opsional)
            function getFallbackData() {
                console.warn('Menggunakan data fallback');
                return [{
                        id: 1,
                        name: "John Doe",
                        program: "Masjid Jamhariya",
                        phone: "08123456789",
                        stage: "contacted"
                    },
                    // ... data dummy lainnya
                ];
            }

            // Render cards ke kolom masing-masing
            function renderCards() {
                // Kosongkan semua kolom terlebih dahulu
                $('.kanban-column-body').empty();

                // Isi cards berdasarkan stage
                leadsData.forEach(lead => {
                    const card = $(`
                    <div class="kanban-card" data-lead-id="${lead.id}" data-stage="${lead.stage}">
                        <h6>${lead.name}</h6>
                        <p class="mb-3"><bold>${lead.program}</bold></p>
                        <div class="text-right mt-2">
                            <a data-id="${lead.id}" href="{{ route('adm.program.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    </div>
                `);

                    $(`#${lead.stage}-column`).append(card);
                });

                // Inisialisasi SortableJS untuk setiap kolom
                initSortable();
            }

            // Inisialisasi drag and drop untuk semua kolom
            function initSortable() {
                const columns = [
                    'contacted', 'report', 'offering',
                    'certificate', 'offering-loyal', 'offering-cross',
                    'remark', 'followup', 'closed'
                ];

                columns.forEach(column => {
                    const elementId = `${column}-column`;
                    const el = document.getElementById(elementId);

                    if (el) {
                        new Sortable(el, {
                            group: 'leads',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            onEnd: function(evt) {
                                updateLeadStage(evt);
                            }
                        });
                    }
                });
            }

            // Update stage lead ketika dipindahkan
            function updateLeadStage(evt) {
                const leadId = evt.item.dataset.leadId;
                const newStage = evt.to.id.replace('-column', '');

                $.ajax({
                    url: `/adm/update-leads-crm-list/${leadId}`,
                    method: 'POST',
                    data: {
                        new_stage: newStage,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // console.log('Response update:', response);
                        if (response.success) {
                            // Refresh data setelah update berhasil
                            fetchLeadsData();
                            // toastr.success('Stage lead berhasil diupdate');
                        } else {
                            // toastr.error('Gagal mengupdate stage');
                            // Kembalikan ke posisi semula jika gagal
                            fetchLeadsData();
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.error('Error update:', error);
                        // toastr.error('Terjadi kesalahan saat mengupdate');
                        // Kembalikan ke posisi semula jika error
                        fetchLeadsData();
                    }
                });
            }

            // Handler untuk tombol edit
            $(document).on('click', '.edit-lead', function() {
                const leadId = $(this).data('id');
                alert(`Edit lead dengan ID: ${leadId}`);
            });

            // Handler untuk tombol hapus
            $(document).on('click', '.delete-lead', function() {
                const leadId = $(this).data('id');
                if (confirm(`Apakah Anda yakin ingin menghapus lead dengan ID: ${leadId}?`)) {
                    $.ajax({
                        url: '/leads/' + leadId, // Sesuaikan dengan route Anda
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Lead berhasil dihapus:', response);
                            fetchLeadsData(); // Refresh data
                        },
                        error: function(xhr, status, error) {
                            console.error('Gagal menghapus lead:', error);
                        }
                    });
                }
            });

            // Pertama kali load, ambil data dari server
            fetchLeadsData();
        });

        $(document).ready(function() {
            // Inisialisasi Select2
            $('#leadsSelect').select2({
                placeholder: "Cari atau pilih leads...",
                dropdownParent: $('#leadsDropdown').next('.dropdown-menu'),
                width: 'resolve',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.crm-pipeline.list') }}",
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
                console.log('Selected lead:', data);
                // Lakukan sesuatu dengan data leads yang dipilih
                // Contoh: window.location.href = '/leads/' + data.id;
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
                window.location.href = '/adm/crm-pipeline?type=' + encodeURIComponent(data.type);
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
            // Variabel untuk menyimpan data pipeline
            let pipelineData = {};
            let currentPipelineId = '{{ request()->query('type') ?? 'null' }}';
            let prospects = []; // Untuk menyimpan daftar prospect

            // Fungsi untuk mengambil data dari server
            function fetchPipelineData() {
                if (!currentPipelineId) return;

                $.ajax({
                    url: '{{ url('adm/crm-prospect-activity-list') }}/' + currentPipelineId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Data pipeline berhasil diambil:', response);
                        pipelineData = response.data;
                        prospects = pipelineData.crm_prospect || [];

                        // Render kolom kanban berdasarkan prospect
                        renderKanbanColumns();

                        // Render activities untuk setiap prospect
                        renderProspectsAndActivities();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mengambil data pipeline:', error);
                        // Fallback ke data dummy jika diperlukan
                        pipelineData = getFallbackData();
                        prospects = pipelineData.crm_prospect || [];
                        renderKanbanColumns();
                        renderProspectsAndActivities();
                    }
                });
            }

            // Fungsi untuk render kolom kanban berdasarkan prospect
            function renderKanbanColumns() {
                const $kanbanBoard = $('.kanban-board');

                // Kosongkan semua kolom kecuali tombol tambah
                $kanbanBoard.children().not('.kanban-column[href]').remove();

                // Buat kolom untuk setiap prospect
                prospects.forEach(prospect => {
                    const columnId = `prospect-${prospect.id}-column`;

                    const $column = $(`
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <h6>${prospect.name}</h6>
                            <small>${prospect.description || 'Tidak ada deskripsi'}</small>
                            <div class="mt-1">
                                <small class="text-muted">Nominal: ${formatCurrency(prospect.nominal)}</small>
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
                    name: "Sample Pipeline",
                    crm_prospect: [{
                        id: 1,
                        name: "Sample Prospect",
                        description: "Deskripsi contoh",
                        nominal: 1000000,
                        crm_prospect_activity: [{
                            id: 1,
                            type: "email",
                            content: "Sample activity",
                            description: "Deskripsi activity",
                            date: "2025-07-12 15:44:00"
                        }]
                    }]
                };
            }

            // Render activities untuk setiap prospect
            function renderProspectsAndActivities() {
                prospects.forEach(prospect => {
                    const columnId = `prospect-${prospect.id}-column`;
                    const $columnBody = $(`#${columnId}`);

                    // Kosongkan kolom terlebih dahulu
                    $columnBody.empty();
                    
                    // Tambahkan activity cards
                    prospect.crm_prospect_activity.forEach(activity => {
                        const activityCard = createActivityCard(prospect, activity);
                        $columnBody.append(activityCard);
                    });
                });

                // Inisialisasi SortableJS untuk memungkinkan drag and drop activity antar prospect
                initSortable();
            }

            // Fungsi untuk membuat card activity
            function createActivityCard(prospect, activity) {
                return $(`
                <div class="kanban-card" 
                     data-activity-id="${activity.id}" 
                     data-prospect-id="${prospect.id}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${activity.type.toUpperCase()}</h6>
                            <p class="mb-1">${activity.content}</p>
                            <small class="text-muted">${activity.description || 'Tidak ada deskripsi'}</small>
                        </div>
                        <span class="badge bg-light text-dark">
                            ${formatDate(activity.date)}
                        </span>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button class="btn btn-xs btn-outline-primary view-activity mr-1" 
                                data-id="${activity.id}">
                            <i class="fa fa-eye mr-1"></i> Lihat
                        </button>
                        <button class="btn btn-xs btn-outline-danger delete-activity" 
                                data-id="${activity.id}">
                            <i class="fa fa-trash mr-1"></i> Hapus
                        </button>
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
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Format mata uang
            function formatCurrency(amount) {
                if (!amount) return 'Rp 0';
                return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Inisialisasi drag and drop untuk activity
            function initSortable() {
                prospects.forEach(prospect => {
                    const columnId = `prospect-${prospect.id}-column`;
                    const el = document.getElementById(columnId);

                    if (el) {
                        new Sortable(el, {
                            group: 'activities',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            onEnd: function(evt) {
                                // Jika activity dipindahkan ke prospect yang berbeda
                                if (evt.from !== evt.to) {
                                    const activityId = evt.item.dataset.activityId;
                                    const newProspectId = evt.to.id.replace('prospect-', '')
                                    .replace('-column', '');
                                    updateActivityProspect(activityId, newProspectId);
                                }
                            }
                        });
                    }
                });
            }

            // Update prospect activity ketika dipindahkan
            function updateActivityProspect(activityId, newProspectId) {
                $.ajax({
                    url: `/adm/crm-prospect-activity/${activityId}/update-prospect`,
                    method: 'POST',
                    data: {
                        new_prospect_id: newProspectId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Response update:', response);
                        if (response.success) {
                            toastr.success('Activity berhasil dipindahkan');
                            // Refresh data setelah update berhasil
                            fetchPipelineData();
                        } else {
                            toastr.error('Gagal memindahkan activity');
                            // Kembalikan ke posisi semula jika gagal
                            fetchPipelineData();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error update:', error);
                        toastr.error('Terjadi kesalahan saat memindahkan activity');
                        // Kembalikan ke posisi semula jika error
                        fetchPipelineData();
                    }
                });
            }

            // Handler untuk tombol lihat activity
            $(document).on('click', '.view-activity', function() {
                const activityId = $(this).data('id');
                showActivityModal(activityId);
            });

            // Handler untuk tombol hapus activity
            $(document).on('click', '.delete-activity', function() {
                const activityId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus activity ini?')) {
                    deleteActivity(activityId);
                }
            });

            // Fungsi untuk menampilkan modal activity
            function showActivityModal(activityId) {
                $.ajax({
                    url: `/adm/crm-prospect-activity/${activityId}/info`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const activity = response.data;
                            const modalContent = `
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Activity</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Prospect:</strong> ${activity.prospect.name}</p>
                                        <p><strong>Type:</strong> ${activity.type}</p>
                                        <p><strong>Content:</strong> ${activity.content}</p>
                                        <p><strong>Description:</strong> ${activity.description || '-'}</p>
                                        <p><strong>Date:</strong> ${formatDate(activity.date)}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        `;

                            $('#activityModal').html(modalContent).modal('show');
                        } else {
                            toastr.error('Gagal mengambil data activity');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Terjadi kesalahan saat mengambil data');
                    }
                });
            }

            // Fungsi untuk menghapus activity
            function deleteActivity(activityId) {
                $.ajax({
                    url: `/adm/crm-prospect-activity/${activityId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Activity berhasil dihapus');
                            fetchPipelineData();
                        } else {
                            toastr.error('Gagal menghapus activity');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Terjadi kesalahan saat menghapus activity');
                    }
                });
            }

            // Pertama kali load, ambil data dari server
            fetchPipelineData();

            // Handle ketika pipeline dipilih dari dropdown
            $('#leadsSelect').on('select2:select', function(e) {
                const data = e.params.data;
                if (data.id) {
                    currentPipelineId = data.id;
                    fetchPipelineData();
                    history.pushState(null, null, `?pipeline_id=${data.id}`);
                }
            });
        });
    </script>
@endsection
