@extends('layouts.admin', [
    'second_title' => 'Leads Lembaga',
    'header_title' => 'Leads Lembaga',
    'sidebar_menu' => 'leads',
    'sidebar_submenu' => 'org-list',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Leads Lembaga</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary leads-status-filter active" data-status-filter="">Semua</button>
                    <button class="btn btn-outline-primary leads-status-filter"
                        data-status-filter="interest">Potensial</button>
                    <button class="btn btn-outline-primary leads-status-filter" data-status-filter="wa">Ada WA</button>
                    <button class="btn btn-outline-primary leads-status-filter" data-status-filter="email">Ada
                        Email</button>
                    <button class="btn btn-outline-primary mr-1" id="refresh_datatable"><i class="fa fa-sync"></i>
                        Refresh</button>
                    <a href="{{ route('adm.leads.org.add') }}" target="_blank" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-12">
                    <div class="row gx-3 align-items-center">
                        <div class="col-auto"><span class="fw-bold">Filter :</span></div>
                        <div class="col"><input type="text" id="name_filter" placeholder="Nama Lembaga"
                                class="form-control form-control-sm"></div>
                        <div class="col"><input type="text" id="contact_filter" placeholder="Kontak"
                                class="form-control form-control-sm"></div>
                        <div class="col"><input type="text" id="address_filter" placeholder="Alamat"
                                class="form-control form-control-sm"></div>
                        <div class="col-auto"><button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="row gx-3 align-items-center">
                        <div class="col-auto"><span class="fw-bold">Urutkan :</span></div>
                        <div class="col">
                            <select class="form-select form-select-sm" id="filter_sort">
                                <option value="">-- Pilih --</option>
                                <option value="informasi_program">Potensi</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="dir" id="dir_asc"
                                    value="asc">
                                <label class="form-check-label" for="dir_asc">Dari Terkecil</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="dir" id="dir_desc"
                                    value="desc" checked>
                                <label class="form-check-label" for="dir_desc">Dari Terbesar</label>
                            </div>
                        </div>
                        <div class="col-auto"><button class="btn btn-sm btn-primary"
                                id="filter_sort_btn">Urutkan</button></div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-organization_list" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama Lembaga</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Potensi</th>
                        <th>Sosial Media</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('content_modal')
    <!-- Modal Update no Telp -->
    <div class="modal fade" id="modal_updatePhone" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
        aria-labelledby="updatephoneModalLabel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="update-phone" action="{{ route('adm.leads.org.phone.update') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="text" class="invisible" name="id" id="organization_id" style="display:none;" />
                    <div class="modal-header pt-2 pb-2">
                        <h1 class="modal-title fs-5" id="modalTitle">Update No Telp <span id="modal-data-name"></span>
                        </h1>
                        <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start pt-4">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor Telephone</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                placeholder="Masukkan nomor telephone" required>
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

    <!-- Modal Add to CRM -->
    <div class="modal fade" id="modal_add_to_crm" tabindex="-1" role="dialog" aria-labelledby="addToCrmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="add-to-crm-form" action="{{ route('adm.crm-prospect.store.from-grab') }}" method="POST">
                    @csrf
                    <input type="hidden" name="prospect_type" value="grab_organization">
                    <input type="hidden" name="prospect_id" id="crm_grab_organization_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addToCrmModalLabel">Tambah Prospek dari Grab Organization</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="crm_name" class="form-label">Nama Prospect</label>
                                <input type="text" class="form-control" name="name" id="crm_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="crm_leads" class="form-label">Leads</label>
                                <select class="form-control" name="lead_id" id="crm_leads" required
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="crm_pipeline" class="form-label">Pipeline</label>
                                <select class="form-control" name="pipeline" id="crm_pipeline" required
                                    style="width: 100%;" disabled></select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="crm_assign_to" class="form-label">Pilih PIC</label>
                                <select class="form-control" name="assign_to" id="crm_assign_to" required
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="crm_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="crm_description" name="description" rows="4"></textarea>
                        </div>
                        <div class="ml-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_potential" id="crm_is_potential"
                                value="1" checked>
                            <label class="form-check-label" for="crm_is_potential">Status Potensial</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        var SORT_FIELD = 'informasi_program';
        var SORT_DIR = 'desc';

        var table = $('#table-organization_list').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,
            order: [],
            dom: 'lrtip',
            pageLength: 25,
            language: {
                paginate: {
                    previous: "<",
                    next: ">"
                }
            },
            ajax: {
                url: "{{ route('adm.leads.org.datatables') }}",
                data: function(d) {
                    var activeFilter = $('.leads-status-filter.active').data('status-filter');
                    if (activeFilter === 'wa') {
                        d.ada_wa = 1;
                    }
                    if (activeFilter === 'email') {
                        d.ada_email = 1;
                    }
                    if (activeFilter === 'interest') {
                        d.interest = 1;
                    }

                    // Add custom search parameter
                    var name_search = $('#name_filter').val();
                    var contact_search = $('#contact_filter').val();
                    var address_search = $('#address_filter').val();
                    d.custom_search = [name_search, contact_search, address_search].filter(Boolean).join(' ').trim();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'contact',
                    name: 'contact'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                { 
                    data: 'informasi_program',
                    name: 'informasi_program'
                },
                {
                    data: 'socmed',
                    name: 'socmed'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Refresh button
        $("#refresh_datatable").on("click", function() {
            $('#name_filter, #contact_filter, #address_filter').val('');
            table.search('').order([]).draw();
        });

        // Status filter buttons
        $('.leads-status-filter').on('click', function() {
            $('.leads-status-filter').removeClass('active');
            $(this).addClass('active');
            table.ajax.reload();
        });

        // Search button
        $('#filter_search').on('click', function() {
            table.draw();
        });

        // Sort button
        $('#filter_sort_btn').on('click', function() {
            var picked = $('#filter_sort').val();
            var dir = $('input[name="dir"]:checked').val();

            if (picked === 'informasi_program') {
                table.order([3, dir]).draw();
            } else {
                table.order([]).draw(); // Reset order
            }
        });

        function firstChat(id, org) {
            var result = confirm("Ingin Frist Chat ke " + org + "?");
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('adm.leads.org.chat') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "type": 'fc'
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            alert('BERHASIL, first chat ke lembaga ' + data.org);
                        } else {
                            alert('GAGAL, frist chat ke lembaga');
                        }
                    }
                });
            }
        }

        function openUpdateOrganizationPhoneModal(id, name) {
            $('#modal-data-name').text(name);
            $('#organization_id').val(id);

            var grabdo_modal = new bootstrap.Modal(document.getElementById('modal_updatePhone'), {
                backdrop: 'static',
                keyboard: false
            });

            grabdo_modal.show();
        }

        $(document).ready(function() {
            $('#update-phone').submit(function(e) {
                e.preventDefault();

                var submitBtn = $(this).find('button[type="submit"]');
                var closeBtn = $(this).find('button[data-bs-dismiss="modal"]');

                submitBtn.prop('disabled', true);
                closeBtn.prop('disabled', true);
                submitBtn.text('Tunggu bentar ya...');

                var formData = $(this).serialize();
                var url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        callToast('success', response.message);
                        $('#modal_updatePhone').modal('hide');
                        $('#update-phone')[0].reset();
                        submitBtn.prop('disabled', false);
                        closeBtn.prop('disabled', false);
                        submitBtn.text('Update');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        var errorMessage = 'Terjadi kesalahan saat memproses data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        callToast('error', errorMessage);
                        submitBtn.prop('disabled', false);
                        closeBtn.prop('disabled', false);
                        submitBtn.text('Update');
                    }
                });
            });

            $('#modal_updatePhone').on('hidden.bs.modal', function() {
                var submitBtn = $(this).find('button[type="submit"]');
                var closeBtn = $(this).find('button[data-bs-dismiss="modal"]');
                submitBtn.prop('disabled', false);
                closeBtn.prop('disabled', false);
                submitBtn.text('Update');
                $('#update-phone')[0].reset();
            });

            var addToCrmModal = new bootstrap.Modal(document.getElementById('modal_add_to_crm'), {
                keyboard: false
            });

            $('#table-organization_list').on('click', '.open-add-to-crm-modal', function() {
                var orgId = $(this).data('id');
                var orgName = $(this).data('name');
                $('#crm_grab_organization_id').val(orgId);
                $('#crm_name').val(orgName + ' Prospect');
                $('#addToCrmModalLabel').text('Tambah Prospek untuk "' + orgName + '"');
                addToCrmModal.show();
            });

            function initializeSelect2(element, url, placeholder, textMapping, getDynamicData) {
                if (getDynamicData === void 0) { getDynamicData = null; }
                $(element).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#modal_add_to_crm'),
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            };
                            if (getDynamicData) {
                                $.extend(query, getDynamicData());
                            }
                            return query;
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.data.map(textMapping),
                                pagination: {
                                    more: (params.page * 10) < data.total
                                }
                            };
                        },
                        cache: true
                    }
                });
            }

            initializeSelect2('#crm_leads', "{{ route('adm.crm-leads.select2.all') }}", 'Pilih Leads',
                function(item) {
                    return { id: item.id, text: item.name };
                });

            initializeSelect2('#crm_pipeline', "{{ route('adm.crm-pipeline.select2.all') }}",
                'Pilih Pipeline',
                function(item) {
                    return { id: item.id, text: item.name };
                },
                function() {
                    return { lead_id: $('#crm_leads').val() };
                });

            initializeSelect2('#crm_assign_to', "{{ route('adm.users.select2.all') }}", 'Pilih PIC',
                function(item) {
                    return { id: item.id, text: item.name };
                });

            $('#crm_leads').on('change', function() {
                var leadId = $(this).val();
                var $pipelineSelect = $('#crm_pipeline');
                $pipelineSelect.val(null).trigger('change');
                if (leadId) {
                    $pipelineSelect.prop('disabled', false);
                } else {
                    $pipelineSelect.prop('disabled', true);
                }
            });

            $('#add-to-crm-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function(response) {
                        addToCrmModal.hide();
                        form[0].reset();
                        table.ajax.reload(null, false);
                        callToast('success', response.message);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        callToast('error', errorMessage);
                    }
                });
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
                didOpen: function(toast) {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }
    </script>
@endsection
