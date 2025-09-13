@extends('layouts.admin', [
    'second_title' => 'Lembaga',
    'header_title' => 'Lembaga',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'organization',
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
                            <li class="breadcrumb-item active" aria-current="page">Lembaga</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary org-status-filter active" data-status-filter="">Semua</button>
                    <button class="btn btn-outline-primary org-status-filter" data-status-filter="regular">Biasa</button>
                    <button class="btn btn-outline-primary org-status-filter" data-status-filter="verified">Terverifikasi</button>
                    <button class="btn btn-outline-primary org-status-filter" data-status-filter="banned">Banned</button>
                    <button class="btn btn-outline-primary org-status-filter" data-status-filter="verif_org">Lembaga</button>
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_org"><i class="fa fa-sync"></i>
                        Refresh</button>
                    <a href="{{ route('adm.organization.create') }}" target="_blank" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-12">
                    <div class="row gx-3 align-items-center">
                        <div class="col-auto"><span class="fw-bold">Filter :</span></div>
                        <div class="col"><input type="text" id="name_filter" placeholder="Nama" class="form-control form-control-sm"></div>
                        <div class="col"><input type="text" id="phone_filter" placeholder="Telepon" class="form-control form-control-sm"></div>
                        <div class="col"><input type="text" id="email_filter" placeholder="Email" class="form-control form-control-sm"></div>
                        <div class="col-auto"><button class="btn btn-sm btn-primary" id="filter_search">Cari</button></div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="row gx-3 align-items-center">
                        <div class="col-auto"><span class="fw-bold">Urutkan :</span></div>
                        <div class="col">
                            <select class="form-select form-select-sm" id="filter_sort">
                                <option value="">-- Pilih --</option>
                                <option value="total_donation_nominal">Total Donasi</option>
                                <option value="total_ads_nominal">Total Pengeluaran Ads</option>
                                <option value="total_nominal_payout">Total Penyaluran</option>
                                <option value="dss">DSS</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="dir" id="dir_asc" value="asc">
                                <label class="form-check-label" for="dir_asc">Dari Terkecil</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="dir" id="dir_desc" value="desc" checked>
                                <label class="form-check-label" for="dir_desc">Dari Terbesar</label>
                            </div>
                        </div>
                        <div class="col-auto"><button class="btn btn-sm btn-primary" id="filter_sort_btn">Urutkan</button></div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-organization" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Rangkuman</th>
                        <th>Informasi Donasi</th>
                        <th>DSS</th>
                        <th>Alamat</th>
                        <th>Nama Alias</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('content_modal')
    <div class="modal fade" id="modal_add_aliases" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
        aria-labelledby="donaturModalLabel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="grabdo-platform" action="{{ route('adm.org.new.alias') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="text" class="d-none" id="platform_id" name="platform_name">
                    <div class="modal-header pt-2 pb-2">
                        <h1 class="modal-title fs-5" id="modalTitle">Tambah Alias <span id="modal-data-name"></span></h1>
                        <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start pt-4">
                        <div class="mb-3">
                            <label for="aliases" class="form-label">Alias Baru</label>
                            <div class="tag-input-container">
                                <div id="tag-badges" class="d-flex flex-wrap gap-2 mb-2">
                                    <!-- Tag badges akan muncul di sini -->
                                </div>
                                <input type="text" class="form-control" id="aliases" name="aliases"
                                    placeholder="Masukkan nama alias baru. Tekan Enter untuk memisahkan data">
                                <input type="hidden" id="aliases-hidden" name="aliases_array">
                            </div>
                            <small class="text-muted">Tekan Enter setelah setiap alias</small>
                        </div>
                    </div>

                    <div class="modal-footer pt-2 pb-2">
                        <input type="hidden" id="id_organization" name="id_organization" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan data</button>
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

        .tag-badge {
            display: inline-flex;
            align-items: center;
            background-color: #e9ecef;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }

        .tag-remove {
            margin-left: 0.5em;
            cursor: pointer;
            color: #6c757d;
        }

        .tag-remove:hover {
            color: #dc3545;
        }
    </style>
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
    let refreshCache = false;
    let SORT_FIELD = '';
    let SORT_DIR = 'desc';

    // Initialize DataTable
    var table = $('#table-organization').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searching: false,
        order: [],
        dom: 'lrtip', // Remove the global search input (f)
        ajax: {
            url: "{{ route('adm.org.datatables') }}",
            data: function(d) {
                if (refreshCache) {
                    d.refresh = true;
                    refreshCache = false; // Reset flag
                }
                // Add status filter parameter
                const activeFilter = $('.org-status-filter.active').data('status-filter');
                if (activeFilter) {
                    d.status_filter = activeFilter;
                } else {
                    delete d.status_filter; // Remove parameter if 'Semua' is selected
                }

                // Add new filters
                d.name = $('#name_filter').val();
                d.phone = $('#phone_filter').val();
                d.email = $('#email_filter').val();

                // Add sort
                d.sort = SORT_FIELD;
                d.dir = SORT_DIR;
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'contact', name: 'contact' },
            { data: 'summary', name: 'summary' },
            { data: 'finance', name: 'finance', orderable: false, searchable: false },
            { data: 'dss', name: 'dss', orderable: true, searchable: false },
            { data: 'address', name: 'address' },
            { data: 'alias_names', name: 'alias_names' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });

    // Refresh table button
    $("#refresh_table_org").on("click", function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Refreshing...');

        refreshCache = true;

        table.ajax.reload(function(json) {
            // This callback is executed after the reload is complete
            btn.prop('disabled', false).html('<i class="fa fa-sync"></i> Refresh');
            callToast('success', 'Cache berhasil dihapus, data dimuat ulang.');
        }, false); // false to keep pagination
    });

    // Filter buttons for organization status
    $('.org-status-filter').on('click', function() {
        // Remove active class from all buttons and add to the clicked one
        $('.org-status-filter').removeClass('active');
        $(this).addClass('active');

        // Reload DataTable to apply filter
        table.ajax.reload(null, false); // null for callback, false to keep pagination
    });

    // Search button
    $('#filter_search').on('click', function() {
        table.ajax.reload();
    });

    // Sort button
    $('#filter_sort_btn').on('click', function() {
        const allowed = ['total_donation_nominal', 'total_ads_nominal', 'total_nominal_payout', 'dss'];
        const picked = $('#filter_sort').val();
        const dir = $('input[name="dir"]:checked').val();

        if (allowed.includes(picked)) {
            SORT_FIELD = picked;
            SORT_DIR = (dir === 'asc' ? 'asc' : 'desc');
            table.ajax.reload();
        } else {
            SORT_FIELD = '';
            SORT_DIR = 'asc';
            table.ajax.reload();
        }
    });

    const DT_SORT_MAP = {
        4: 'dss', 
    };
    $('#table-organization').on('order.dt', function() {
        const order = table.order();
        if (order && order.length) {
            const [colIdx, dir] = order[0];
            if (DT_SORT_MAP[colIdx]) {
                SORT_FIELD = DT_SORT_MAP[colIdx];
                SORT_DIR = (dir === 'asc' ? 'asc' : 'desc');
                table.ajax.reload(null, false);
            }
        }
    });


    // Global tag management variables
    let tags = [];
    const $tagInput = $('#aliases');
    const $tagContainer = $('#tag-badges');
    const $hiddenInput = $('#aliases-hidden');

    // Modal functions
    function openAddAliasModal(id, name, aliasNames) {
        // Parse and validate alias names
        if (typeof aliasNames === 'string') {
            try {
                aliasNames = JSON.parse(aliasNames);
            } catch (e) {
                aliasNames = [];
            }
        }

        if (!Array.isArray(aliasNames)) {
            aliasNames = [];
        }

        // Set modal values
        $('#id_organization').val(id);
        $('#modal-data-name').text(name);

        // Initialize tags
        tags = [...aliasNames];
        updateTagDisplay();
        updateHiddenInput();

        // Show modal
        $('#modal_add_aliases').modal('show');
    }

    // Tag management functions
    function addTag(tag) {
        tag = tag.trim();
        if (tag && !tags.includes(tag)) {
            tags.push(tag);
            updateTagDisplay();
            updateHiddenInput();
        }
    }

    function removeTag(tag) {
        tags = tags.filter(t => t !== tag);
        updateTagDisplay();
        updateHiddenInput();
    }

    function updateTagDisplay() {
        $tagContainer.empty();
        tags.forEach(tag => {
            $tagContainer.append(`
                <span class="badge bg-secondary badge-sm d-inline-flex align-items-center">
                    ${tag.replace(/</g, "&lt;").replace(/>/g, "&gt;")}
                    <button type="button" class="ms-1 btn-close btn-close-white" style="font-size: 0.65rem;" onclick="removeTag('${tag.replace(/'/g, "\\'")}')"></button>
                </span>
            `);
        });
    }

    function updateHiddenInput() {
        $hiddenInput.val(JSON.stringify(tags));
    }

    // Document ready
    $(document).ready(function() {
        // Modal configuration
        $('#modal_add_aliases').modal({
            backdrop: 'static',
            keyboard: false
        });

        // Tag input handling
        $tagInput.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const inputValue = $tagInput.val().trim();
                if (inputValue) {
                    addTag(inputValue);
                    $tagInput.val('');
                }
            }
        });

        // Handle paste event
        $tagInput.on('paste', function(e) {
            setTimeout(() => {
                const pastedText = $tagInput.val();
                const tagsToAdd = pastedText.split(/[\n,]/).filter(tag => tag.trim());
                if (tagsToAdd.length > 0) {
                    e.preventDefault();
                    tagsToAdd.forEach(tag => addTag(tag));
                    $tagInput.val('');
                }
            }, 0);
        });

        // Form submission
        $('#grabdo-platform').submit(function(e) {
            e.preventDefault();

            // Disable buttons during submission
            const submitBtn = $(this).find('button[type="submit"]');
            const closeBtn = $(this).find('button[data-bs-dismiss="modal"]');

            submitBtn.prop('disabled', true).text('Tunggu bentar ya...');
            closeBtn.prop('disabled', true);

            // Submit form via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: (response) => {
                    callToast(response.status, response.message);
                    $('#modal_add_aliases').modal('hide');
                    table.ajax.reload(null, false);
                },
                error: (xhr) => {
                    const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data';
                    callToast('error', errorMessage);
                },
                complete: () => {
                    submitBtn.prop('disabled', false).text('Simpan data');
                    closeBtn.prop('disabled', false);
                }
            });
        });

        // Reset modal when closed
        $('#modal_add_aliases').on('hidden.bs.modal', function() {
            tags = [];
            $tagContainer.empty();
            $hiddenInput.val('[]');
            $tagInput.val('');
        });
    });

    // Toast notification function
    function callToast(status, message) {
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: status,
            title: message,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded shadow-sm px-3 py-2 border-0'
            },
            background: status === 'success' ? '#d1fae5' : '#fee2e2',
            color: status === 'success' ? '#065f46' : '#b91c1c',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }
</script>
@endsection
