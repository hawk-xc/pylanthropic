@extends('layouts.admin', [
    'second_title'    => 'Token Config',
    'header_title'    => 'Pengaturan Config',
    'sidebar_menu'    => 'token-config',
    'sidebar_submenu' => ''
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
 -->
@endsection

@section('css_inline')
    <style type="text/css">
        .btn-xs {       
            aspect-ratio: 1 / 1;
            font-size: 13px !important;
        }
        .short-url-input {
            background-color: #f8f9fa;
        }
        .modal {
            z-index: 1050 !important;
        }
        .modal-backdrop {
            z-index: 1040 !important;
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
                            <li class="breadcrumb-item active" aria-current="page">Pengaturan Token</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-token-config" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Token</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Logs Modal -->
    <div class="modal fade" id="logs-modal" tabindex="-1" aria-labelledby="logsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logsModalLabel">Token Update Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Old Token</th>
                                <th>New Token</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="logs-table-body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>

    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tiny.cloud/1/cwr0gleaw96v89pa0jnes11yfy617v1ef0nl4akq5qdl1cdn/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">

    var table = $('#table-token-config').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searching: false,
        order: [],
        ajax: "{{ route('adm.token-config.datatables') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'token', name: 'token', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at'},
        ]
    });

    // Delegated event for toggling token visibility
    $('#table-token-config').on('click', '.toggle-vis-btn', function() {
        var container = $(this).closest('.token-container');
        var input = container.find('.token-text');
        var eyeIcon = $(this).find('i');

        if (input.prop('type') === 'password') {
            input.prop('type', 'text');
            eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.prop('type', 'password');
            eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Copy button logic
    $('#table-token-config').on('click', '.copy-token-btn', function() {
        var tokenToCopy = $(this).data('token');
        navigator.clipboard.writeText(tokenToCopy).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Token copied!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });

    // Edit button logic
    $('#table-token-config').on('click', '.inline-edit-btn', function() {
        var container = $(this).closest('.token-container');
        var input = container.find('.token-text');
        
        // Ensure token is visible for editing
        input.prop('type', 'text');
        container.find('.toggle-vis-btn i').removeClass('fa-eye').addClass('fa-eye-slash');

        input.prop('readonly', false).focus();
        input[0].select();
    });

    // Blur event logic (when leaving the input)
    $('#table-token-config').on('blur', '.token-text', function(e) {
        var input = $(this);
        if (input.prop('readonly')) {
            return;
        }

        var container = input.closest('.token-container');
        var originalToken = container.data('token');
        var newToken = input.val();
        var id = container.data('id');

        input.prop('readonly', true);
        input.prop('type', 'password');
        container.find('.toggle-vis-btn i').removeClass('fa-eye-slash').addClass('fa-eye');

        if (newToken === originalToken) {
            return;
        }

        Swal.fire({
            title: 'Save Changes?',
            text: "The token has been modified. Do you want to save the changes?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'No, discard'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('adm.token-config.update', '') }}/" + id,
                    type: 'PUT',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        token: newToken
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Updated!', 'The token has been updated.', 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                            input.val(originalToken);
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                        input.val(originalToken);
                    }
                });
            } else {
                input.val(originalToken);
            }
        });
    });

    // Logs button click
    $('#table-token-config').on('click', '.logs-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');

        $('#logsModalLabel').text('Logs for ' + name);

        $.ajax({
            url: "{{ route('adm.token-config.logs', '') }}/" + id,
            type: 'GET',
            success: function (response) {
                var logsHtml = '';
                if (response.length > 0) {
                    response.forEach(function (log) {
                        logsHtml += '<tr>';
                        logsHtml += '<td>' + (log.user ? log.user.name : 'N/A') + '</td>';
                        logsHtml += '<td>' + log.old_token + '</td>';
                        logsHtml += '<td>' + log.new_token + '</td>';
                        logsHtml += '<td>' + new Date(log.created_at).toLocaleString() + '</td>';
                        logsHtml += '</tr>';
                    });
                } else {
                    logsHtml = '<tr><td colspan="4" class="text-center">No logs found.</td></tr>';
                }
                $('#logs-table-body').html(logsHtml);

                var logsModal = new bootstrap.Modal(document.getElementById('logs-modal'));
                logsModal.show();
            }
        });
    });

</script>
@endsection
