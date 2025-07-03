@extends('layouts.admin', [
    'second_title' => 'Leads CRM',
    'header_title' => 'Leads CRM',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'leads-crm',
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

        .kanban-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .kanban-row {
            display: flex;
            gap: 5px;
        }

        .kanban-board {
            display: flex;
            gap: 5px;
            overflow-x: auto;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 8px;
            min-height: 700px;
            width: 100%;
        }
        
        .kanban-column {
            /* min-width: 220px; */
            background: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
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
        }
        
        .kanban-card {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: move;
            border-left: 4px solid #4285f4;
        }
        
        .kanban-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .sortable-ghost {
            opacity: 0.5;
            background: #dee2e6;
            border: 2px dashed #4285f4;
        }
        
        .sortable-chosen {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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
                    <div>
                        <button class="btn btn-primary" id="filter-54" onclick="openDonaturLoyalModal()">
                            <i class="fa fa-plus mr-1" id="filter-54-icon"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            <div class="divider"></div>

            <!-- Container untuk multiple rows -->
            <div class="kanban-container">
                <!-- Row 1 -->
                <div class="kanban-row">
                    <div class="kanban-board">
                        <div class="kanban-column">
                            <div class="kanban-column-header">LEADS</div>
                            <div class="kanban-column-body" id="contacted-column"></div>
                        </div>

                        <div class="kanban-column">
                            <div class="kanban-column-header">LAP. PROGRAM & COMPRO</div>
                            <div class="kanban-column-body" id="report-column"></div>
                        </div>

                        <div class="kanban-column">
                            <div class="kanban-column-header">PENAWARAN</div>
                            <div class="kanban-column-body" id="offering-column"></div>
                        </div>

                        <div class="kanban-column">
                            <div class="kanban-column-header">SERTI DONATUR ISTIMEWA</div>
                            <div class="kanban-column-body" id="certificate-column"></div>
                        </div>
                        
                        <div class="kanban-column">
                            <div class="kanban-column-header">TAWARAN DONATUR SETIA</div>
                            <div class="kanban-column-body" id="offering-loyal-column"></div>
                        </div>

                        <div class="kanban-column">
                            <div class="kanban-column-header">TAWARAN CROSS PROGRAM</div>
                            <div class="kanban-column-body" id="offering-cross-column"></div>
                        </div>

                        <div class="kanban-column">
                            <div class="kanban-column-header">UCAPAN KHUSUS 1JT</div>
                            <div class="kanban-column-body" id="remark-column"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_modal')
        <!-- Modal Tambah Donatur Loyal -->
        <div class="modal fade" id="modal_donatur_loyal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="donaturModalLabel">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="donaturLoyalForm" action="{{ route('adm.leads-crm.store') }}" method="post">
                        @csrf
                        <div class="modal-header pt-2 pb-2">
                            <h1 class="modal-title fs-5" id="modalTitle">Tambah Leads</h1>
                            <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start pt-4">
                            <div class="mb-3">
                                <label class="form-label required">Pilih Program</label>
                                <select class="form-control form-control-sm" name="program" id="program-select2"
                                    required></select>
                                @error('program')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Pilih Donatur</label>
                                <select class="form-control form-control-sm" name="donatur" id="donatur-select2"
                                    required></select>
                                @error('donatur')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="donasi_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="donasi_description" name="donasi_description" rows="3"
                                    placeholder="Masukkan deskripsi donasi tetap"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer pt-2 pb-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Donatur Loyal -->
        <div class="modal fade" id="edit_modal_donatur_loyal" tabindex="-1" aria-labelledby="editDonaturModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="editDonaturLoyalForm" action="{{ route('adm.donatur-loyal.update', 1) }}"
                        method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-header pt-2 pb-2">
                            <h1 class="modal-title fs-5" id="modalTitle">Edit data Donasi Tetap</h1>
                            <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold required">Pilih Program</label>
                                <input type="text" class="form-control form-control-sm" name="program"
                                    id="edit_program" disabled>
                                @error('edit_program')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_donasi_periode" class="form-label">Periode Donasi</label>
                                <select class="form-select" id="edit_donasi_periode" name="donasi_periode"
                                    onchange="handlePeriodeChange(this.value, true)">
                                    <option value="daily">Harian (Jam pada tanggal)</option>
                                    <option value="weekly">Mingguan (Jam & Hari)</option>
                                    <option value="monthly">Bulanan (Per Tanggal)</option>
                                    <option value="yearly">Tahunan (Per Bulan)</option>
                                </select>
                            </div>
                            <div id="edit_periode_fields"></div>
                            <div class="mt-2 mb-3" style="margin: auto;">
                                <label for="donasi_nominal" class="form-label">Nominal</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RP</span>
                                    </div>
                                    <input class="form-control form-control-lg" id="edit_rupiah" name="amount"
                                        placeholder="0" type="text" value="" />
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="form-check big-checkbox mb-0 ms-1">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkgenap">
                                                <label class="form-check-label" for="checkgenap"> Genapkan</label>
                                            </div>
                                        </span>
                                    </div> --}}
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="donasi_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_donasi_description" name="donasi_description" rows="3"
                                    placeholder="Masukkan deskripsi donasi tetap"></textarea>
                            </div>
                            <div class="form-check form-switch mt-2 ml-3">
                                <input class="form-check-input" type="checkbox" name="edit_status_donasi_tetap"
                                    id="edit_status_donasi_tetap" value="1" checked>
                                <label class="form-check-label" for="edit_status_donasi_tetap">Status Donasi Tetap (<span
                                        id="_edit_status">Aktif</span>)</label>
                                <input type="hidden" name="edit_status_donasi_tetap_hidden"
                                    id="edit_status_donasi_tetap_hidden" value="1">
                            </div>
                        </div>
                        <div class="modal-footer pt-2 pb-2">
                            <input type="hidden" id="id_trans" name="id_trans" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
<script>
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
                url: '{{ route("adm.leads-crm.list") }}',
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
                            phone: lead.donatur_data.telp, // Anda bisa menambahkan field phone jika ada
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
            return [
                { id: 1, name: "John Doe", program: "Masjid Jamhariya", phone: "08123456789", stage: "contacted" },
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
            if(confirm(`Apakah Anda yakin ingin menghapus lead dengan ID: ${leadId}?`)) {
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
@endsection