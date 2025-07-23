@extends('layouts.admin', [
    'second_title' => 'Leads Lembaga',
    'header_title' => 'Leads Lembaga',
    'sidebar_menu' => 'leads',
    'sidebar_submenu' => 'org-list',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
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
                    <button class="btn btn-outline-primary btn_filter" id="fil_interest"
                        data-id="interest">Potensial</button>
                    <button class="btn btn-outline-primary btn_filter" id="fil_wa" data-id="wa">Ada WA</button>
                    <button class="btn btn-outline-primary btn_filter" id="fil_email" data-id="email">Ada Email</button>
                    <button class="btn btn-outline-primary btn_filter" id="refresh_datatable">Refresh</button>
                    <a href="{{ route('adm.leads.org.add') }}" target="_blank" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-organization_list" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama Lembaga</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Sosial Media</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" id="interest_val" value="0">
    <input type="hidden" id="wa_val" value="0">
    <input type="hidden" id="email_val" value="0">
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
                        <h1 class="modal-title fs-5" id="modalTitle">Update No Telp <span id="modal-data-name"></span></h1>
                        <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
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
@endsection


@section('js_inline')
    <script type="text/javascript">
        $.fn.DataTable.ext.pager.numbers_length = 15;
        var table = $('#table-organization_list').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            order: [],
            language: {
                paginate: {
                    previous: "<",
                    next: ">"
                }
            },
            ajax: "{{ route('adm.leads.org.datatables') }}",
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
                    data: 'socmed',
                    name: 'socmed'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
        $('#table-organization_list thead tr').clone(true).appendTo('#table-organization_list thead');
        $('#table-organization_list tr:eq(1) th').each(function(i) {
            var title = $(this).text();
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

        $("#refresh_datatable").on("click", function() {
            table.ajax.reload();
        });

        function donate_table() {
            let interest_ar = $('#interest_val').val();
            let wa_ar = $('#wa_val').val();
            let email_ar = $('#email_val').val();

            table.ajax.url("{{ route('adm.leads.org.datatables') }}/?interest=" + interest_ar + "&ada_wa=" + wa_ar +
                "&ada_email=" + email_ar).load();
        }

        // Filter
        $(".btn_filter").on("click", function() {
            let fil_interest = $('#interest_val').val();
            let fil_wa = $('#wa_val').val();
            let fil_email = $('#email_val').val();
            var fil_btn = $(this).attr("data-id");

            if (fil_btn == 'interest') {
                if (fil_interest == 0) {
                    $('#fil_interest').removeClass('btn-outline-primary');
                    $('#fil_interest').addClass('btn-primary');
                    $('#interest_val').val(1);
                    donate_table();
                } else {
                    $('#fil_interest').addClass('btn-outline-primary');
                    $('#fil_interest').removeClass('btn-primary');
                    $('#interest_val').val(0);
                    donate_table();
                }
            } else if (fil_btn == 'wa') {
                if (fil_wa == 0) {
                    $('#fil_wa').removeClass('btn-outline-primary');
                    $('#fil_wa').addClass('btn-primary');
                    $('#wa_val').val(1);
                    donate_table();
                } else {
                    $('#fil_wa').addClass('btn-outline-primary');
                    $('#fil_wa').removeClass('btn-primary');
                    $('#wa_val').val(0);
                    donate_table();
                }
            } else if (fil_btn == 'email') {
                if (fil_email == 0) {
                    $('#fil_email').removeClass('btn-outline-primary');
                    $('#fil_email').addClass('btn-primary');
                    $('#email_val').val(1);
                    donate_table();
                } else {
                    $('#fil_email').addClass('btn-outline-primary');
                    $('#fil_email').removeClass('btn-primary');
                    $('#email_val').val(0);
                    donate_table();
                }
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
                        // Show success toast
                        callToast('success', response.message);

                        // Hide modal
                        $('#modal_updatePhone').modal('hide');

                        // Reset form
                        $('#update-phone')[0].reset();

                        // Reset buttons state
                        submitBtn.prop('disabled', false);
                        closeBtn.prop('disabled', false);
                        submitBtn.text('Update');

                        // Reload datatable
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
                        callToast('error', errorMessage);

                        // Reset buttons state even on error
                        submitBtn.prop('disabled', false);
                        closeBtn.prop('disabled', false);
                        submitBtn.text('Update');
                    }
                });
            });

            $('#modal_updatePhone').on('hidden.bs.modal', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                const closeBtn = $(this).find('button[data-bs-dismiss="modal"]');

                submitBtn.prop('disabled', false);
                closeBtn.prop('disabled', false);
                submitBtn.text('Update');
                $('#update-phone')[0].reset();
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
