@extends('layouts.admin', [
    'second_title' => 'Leads',
    'header_title' => 'Leads',
    'sidebar_menu' => 'leads',
    'sidebar_submenu' => 'grab_do',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
     -->
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Leads Program</li>
                            <li class="breadcrumb-item active" aria-current="page">Grab Do Platform</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-grabdo_platform" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name Platform/Yayasan</th>
                        <th>data yang diperoleh</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 999999;"></div>
    </div>
@endsection

@section('content_modal')
    <!-- Modal Tambah Donatur Loyal -->
    <div class="modal fade" id="modal_grabdo" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
        aria-labelledby="donaturModalLabel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="grabdo-platform" action="{{ route('adm.leads.grabdo.platform-data') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="text" class="d-none" id="platform_name" name="platform_name">
                    <div class="modal-header pt-2 pb-2">
                        <h1 class="modal-title fs-5" id="modalTitle">Grab Do <span id="modal-data-name"></span></h1>
                        <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start pt-4">
                        <div class="mb-3">
                            <label for="page_number" class="form-label">Halaman yang diambil</label>
                            <input type="number" class="form-control" id="page_number" name="page_number"
                                placeholder="Masukkan nomor halaman" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_count" class="form-label">Banyak data</label>
                            <input type="number" class="form-control" id="data_count" value="1000" name="data_count"
                                placeholder="Masukkan banyak data yang akan digrab" required>
                        </div>
                        <div class="mb-3">
                            <label for="title_search" class="form-label">Pencarian title data</label>
                            <input type="search" class="form-control" id="title_search" value="a" name="title_search"
                                placeholder="Cari data berdasarkan title, eg. bantu">
                        </div>
                    </div>
                    <div class="modal-footer pt-2 pb-2">
                        <input type="hidden" id="id_trans" name="id_trans" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Grab</button>
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
        var table = $('#table-grabdo_platform').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            responsive: true,
            order: [],
            ajax: "{{ route('adm.leads-platform.datatables') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'program_count',
                    name: 'program_count'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $('#table-grabdo_platform thead tr').clone(true).appendTo('#table-grabdo_platform thead');
        $('#table-grabdo_platform tr:eq(1) th').each(function(i) {
            var title = $(this).text();

            if (i === 2 || i === 3) {
                $(this).html('');
                return;
            }

            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title +
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

        function openDonaturLoyalModal(name) {
            $('#modal-data-name').text(name);
            $('#platform_name').val(name.toLowerCase().replace(/\s/g, '_'));
            // let myModal = new bootstrap.Modal(document.getElementById('modal_grabdo'));
            var grabdo_modal = new bootstrap.Modal(document.getElementById('modal_grabdo'), {
                backdrop: 'static',
                keyboard: false
            });
            grabdo_modal.show();
        }

        $(document).ready(function() {
            // Konfigurasi modal agar tidak tertutup saat klik di luar
            $('#modal_grabdo').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#grabdo-platform').submit(function(e) {
                e.preventDefault();

                // Disable buttons and change text
                const submitBtn = $(this).find('button[type="submit"]');
                const closeBtn = $(this).find('button[data-bs-dismiss="modal"]');

                submitBtn.prop('disabled', true);
                closeBtn.prop('disabled', true);
                submitBtn.text('Tunggu bentar ya...');

                // Get form data
                const formData = $(this).serialize();
                const url = $(this).attr('action');

                // Send AJAX request
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        callToast(response.status, response.message);
                        // showToast('success', 'Sukses', 'Data berhasil diproses');

                        // Close modal when success
                        $('#modal_grabdo').modal('hide');

                        // Reset buttons state (optional, if modal will be shown again)
                        submitBtn.prop('disabled', false);
                        closeBtn.prop('disabled', false);
                        submitBtn.text('Grab');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        // Handle error if needed
                        console.error(xhr.responseText);

                        // Show error toast
                        let errorMessage = 'Terjadi kesalahan saat memproses data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast('danger', 'Error', errorMessage);

                        // Reset buttons state even on error
                        submitBtn.prop('disabled', false);
                        closeBtn.prop('disabled', false);
                        submitBtn.text('Grab');
                    }
                });
            });

            // Reset button state when modal is closed manually
            $('#modal_grabdo').on('hidden.bs.modal', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                const closeBtn = $(this).find('button[data-bs-dismiss="modal"]');

                submitBtn.prop('disabled', false);
                closeBtn.prop('disabled', false);
                submitBtn.text('Grab');
            });
        });

        function callToast(status, message) {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: status,
                title: message,
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-start justify-content-start align-item-start justify-item-start'
                },
                background: status === 'success' ? '#d1fae5' : '#fee2e2',
                color: status === 'success' ? '#065f46' : '#b91c1c',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }
    </script>
@endsection
