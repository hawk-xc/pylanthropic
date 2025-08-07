@extends('layouts.admin', [
    'second_title' => 'Tambah CRM Pipeline',
    'header_title' => 'Tambah CRM Pipeline',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'crm-leads',
])

@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adm.crm-leads.index') }}">CRM Leads</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Pipeline</li>
                    </ol>
                </nav>
                <a href='/adm/crm-leads?leads={{ request()->query('leads') }}' class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <form id="pipeline-form" action="{{ route('adm.crm-pipeline.store') }}" method="post" accept-charset="utf-8">
                @csrf
                @php
                    $leadsQuery = strtolower(request()->query('leads'));
                @endphp

                <input type="hidden" id="leads-query" value="{{ $leadsQuery }}">
                <input type="hidden" name="leads_id" id="hidden-leads-id">

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Formulir Tambah Pipeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Pipeline</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-suitcase-line"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" placeholder="Contoh: Prospek Baru"
                                        value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="percentage_deals" class="form-label">Opportunity (%)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-percent-line"></i></span>
                                    <input type="number"
                                        class="form-control @error('percentage_deals') is-invalid @enderror"
                                        id="percentage_deals" name="percentage_deals" min="1" max="100"
                                        placeholder="e.g., 50" value="{{ old('percentage_deals') }}" required>
                                </div>
                                @error('percentage_deals')
                                    <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Pilih Leads</label>
                            <select class="form-control @error('leads_id') is-invalid @enderror" name="leads_id"
                                id="leads-select2" required></select>
                            @error('leads_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                placeholder="Jelaskan tentang pipeline ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i>
                                    {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 ml-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="status_is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="status_is_active">Status: <span
                                        id="_status"></span></label>
                                <input type="hidden" name="is_active_hidden" id="status_is_active_hidden" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end bg-light flex flex-row gap-3">
                        <button type="reset" class="btn btn-outline-danger">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i> Simpan Pipeline
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.tiny.cloud/1/wphaz17bf6i1tsqq7cjt8t5w6r275bw3b8acq6u2gi4hnan4/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            let leadsQuery = $('#leads-query').val(); // dari input hidden
            let leadsSelect = $("#leads-select2");

            leadsSelect.select2({
                placeholder: 'Cari dan pilih leads...',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.crm-leads.list') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            }),
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    }
                }
            });

            // Auto-select based on existing data or query
            let initialLeadsId = "{{ old('leads_id', $pipeline->leads_id) }}";
            let initialLeadsName = "{{ old('leads_name', $pipeline->leads->name ?? '') }}";

            if (initialLeadsId && initialLeadsName) {
                let option = new Option(initialLeadsName, initialLeadsId, true, true);
                leadsSelect.append(option).trigger('change');
                if (leadsQuery) {
                    leadsSelect.prop('disabled', true);
                }
            }

            // Auto-select berdasarkan query jika tersedia
            if (leadsQuery) {
                $.ajax({
                    url: "{{ route('adm.crm-leads.list') }}",
                    data: {
                        search: leadsQuery
                    },
                    success: function(response) {
                        const matched = response.data.find(item => item.name.toLowerCase() ===
                            leadsQuery);
                        if (matched) {
                            let option = new Option(matched.name, matched.id, true, true);
                            leadsSelect.append(option).trigger('change');
                            $('#hidden-leads-id').val(matched.id);
                            leadsSelect.prop('disabled', true);
                        }
                    }
                });
            }

            $('#status_is_active').on('change', function() {
                const statusSpan = $('#_status');
                if ($(this).is(':checked')) {
                    statusSpan.text('Aktif').removeClass('text-danger fw-normal').addClass(
                        'text-success fw-bold');
                    $('#status_is_active_hidden').val('1');
                } else {
                    statusSpan.text('Tidak Aktif').removeClass('text-success fw-bold').addClass(
                        'text-danger fw-normal');
                    $('#status_is_active_hidden').val('0');
                }
            }).trigger('change'); // Trigger on load to set initial state

            $('#pipeline-form').on('reset', function() {
                // Reset select2, but respect disabled state
                if (!leadsSelect.prop('disabled')) {
                    leadsSelect.val(null).trigger('change');
                }

                // Reset status switch
                $('#status_is_active').prop('checked', true).trigger('change');
            });
        });
    </script>

    <script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['status'] }}',
                title: '{{ session('message')['message'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-start justify-content-start align-item-start justify-item-start'
                },
                background: '{{ session('message')['status'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['status'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection

