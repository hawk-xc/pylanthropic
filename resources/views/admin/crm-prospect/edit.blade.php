@extends('layouts.admin', [
    'second_title' => 'Edit CRM Prospect',
    'header_title' => 'Edit CRM Prospect',
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
                        <li class="breadcrumb-item"><a href="{{ route('adm.crm-leads.index') }}">Leads CRM</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Prospect</li>
                    </ol>
                </nav>
                <a href="{{ url()->previous() }}"
                    class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <form id="prospect-form" action="{{ route('adm.crm-prospect.update', $crm_prospect->id) }}"
                method="post" accept-charset="utf-8">
                @csrf
                @method('PUT')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Formulir Edit Prospect</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Prospect</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-user-star-line"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" placeholder="Contoh: Prospek Q4"
                                        value="{{ old('name', $crm_prospect->name) }}" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block"><i class="ri-error-warning-line"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pipeline" class="form-label">Pipeline</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-node-tree"></i></span>
                                    <select class="form-select @error('pipeline') is-invalid @enderror" name="pipeline"
                                        id="pipeline" required>
                                        <option value="">Pilih Pipeline</option>
                                        @forelse ($pipelines as $pipeline)
                                            <option value="{{ $pipeline->id }}"
                                                {{ old('pipeline', $crm_prospect->crm_pipeline_id) == $pipeline->id ? 'selected' : '' }}>
                                                {{ $pipeline->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Tidak ada pipeline tersedia</option>
                                        @endforelse
                                    </select>
                                </div>
                                @error('pipeline')
                                    <div class="invalid-feedback d-block"><i class="ri-error-warning-line"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="prospect_type" class="form-label">Jenis Prospek</label>
                                <select class="form-select" id="prospect_type" name="prospect_type">
                                    <option value="donatur" {{ old('prospect_type', $crm_prospect->prospect_type) == 'donatur' ? 'selected' : '' }}>Donatur (Menjadikan donatur menjadi donatur setia)</option>
                                    <option value="organization" {{ old('prospect_type', $crm_prospect->prospect_type) == 'organization' ? 'selected' : '' }}>Lembaga (melakukan Follow Up Lembaga yang sudah berkerja sama)</option>
                                    <option value="grab_organization" {{ old('prospect_type', $crm_prospect->prospect_type) == 'grab_organization' ? 'selected' : '' }}>Lembaga hasil Grab (melakukan Follow Up lembaga hasil grab agar mau berkerja sama)</option>
                                </select>
                                @error('prospect_type')
                                    <div class="invalid-feedback d-block"><i class="ri-error-warning-line"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prospect-select2" class="form-label required" id="prospect-label">Pilih Data</label>
                                <select class="form-control @error('prospect_id') is-invalid @enderror" name="prospect_id"
                                    id="prospect-select2" required></select>
                                @error('prospect_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="users-select2" class="form-label required">Pilih PIC</label>
                                <select class="form-control @error('assign_to') is-invalid @enderror" name="assign_to"
                                    id="users-select2" required></select>
                                @error('assign_to')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 nominal-group">
                            <label for="nominal" class="form-label">Nominal Prospek</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('nominal') is-invalid @enderror"
                                    name="nominal" id="rupiah" placeholder="100.000.000"
                                    value="{{ old('nominal', number_format($crm_prospect->nominal, 0, ',', '.')) }}"
                                    required>
                            </div>
                            @error('nominal')
                                <div class="invalid-feedback d-block"><i class="ri-error-warning-line"></i>
                                    {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                placeholder="Jelaskan detail prospek ini...">{{ old('description', $crm_prospect->description) }}</textarea>
                        </div>

                        <div class="mb-3 ml-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_potential" id="is_potential"
                                    value="1" {{ old('is_potential', $crm_prospect->is_potential) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_potential">Status Potensial: <span
                                        id="_status"></span></label>
                                <input type="hidden" name="is_potential_hidden" value="{{ $crm_prospect->is_potential ? 1 : 0 }}" id="is_potential_hidden">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end bg-light">
                        <button type="reset" class="btn btn-outline-danger">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i> Update Prospect
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            // Store initial values for reset
            const initialValues = {
                pipeline: "{{ old('pipeline', $crm_prospect->crm_pipeline_id) }}",
                prospect_type: "{{ old('prospect_type', $crm_prospect->prospect_type) }}",
                is_potential: {{ old('is_potential', $crm_prospect->is_potential) ? 'true' : 'false' }},
                picId: "{{ old('assign_to', $crm_prospect->assign_to) }}",
                picText: `{{ old('assign_to_text', optional($crm_prospect->crm_prospect_pic)->name) }}`,
                prospectId: "{{ old('prospect_id', $prospectId) }}",
                prospectText: `{!! old('prospect_id_text', $prospectText) !!}`
            };

            const initialLoad = 10;
            const nextLoad = 15;

            function initializeSelect2(elementId, placeholder, ajaxUrl, textMapping) {
                return $(elementId).select2({
                    placeholder: placeholder,
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownAutoWidth: true,
                    allowClear: true,
                    ajax: {
                        url: ajaxUrl,
                        delay: 300,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term || '',
                                page: params.page || 1,
                                per_page: params.page ? nextLoad : initialLoad
                            };
                        },
                        processResults: function(data, params) {
                            const items = data.results || data.data || [];
                            const more = (params.page || 1) < (data.last_page || Math.ceil(data.total /
                                nextLoad));

                            return {
                                results: items.map(item => ({
                                    id: item.id,
                                    text: textMapping(item)
                                })),
                                pagination: {
                                    more: more
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                    minimumResultsForSearch: 0
                });
            }

            const prospectConfigs = {
                donatur: {
                    label: 'Pilih Donatur',
                    placeholder: 'Cari Donatur...',
                    url: "{{ route('adm.donatur.select2.all') }}",
                    textMapping: item => `${item.name} (${item.telp})`
                },
                organization: {
                    label: 'Pilih Organization',
                    placeholder: 'Cari Organization...',
                    url: "{{ route('adm.organization.select2.all') }}",
                    textMapping: item => item.name
                },
                grab_organization: {
                    label: 'Pilih Grab Organization',
                    placeholder: 'Cari Grab Organization...',
                    url: "{{ route('adm.grab-organization.select2.all') }}",
                    textMapping: item => item.name
                }
            };

            function updateProspectSelect(type) {
                const config = prospectConfigs[type];
                const $select = $('#prospect-select2');
                const $label = $('#prospect-label');
                const $nominalGroup = $('.nominal-group');

                $label.text(config.label);

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.empty();

                initializeSelect2('#prospect-select2', config.placeholder, config.url, config.textMapping);

                if (type === 'donatur') {
                    $nominalGroup.show();
                    $('#rupiah').prop('required', true);
                } else {
                    $nominalGroup.hide();
                    $('#rupiah').prop('required', false);
                }
            }

            $('#prospect_type').on('change', function() {
                updateProspectSelect($(this).val());
            }).trigger('change');

            if (initialValues.prospectId && initialValues.prospectText) {
                let option = new Option(initialValues.prospectText, initialValues.prospectId, true, true);
                $('#prospect-select2').append(option).trigger('change');
            }

            const picSelect = initializeSelect2(
                '#users-select2',
                'Cari PIC...',
                "{{ route('adm.users.select2.all') }}",
                item => item.name
            );

            if (initialValues.picId && initialValues.picText) {
                let option = new Option(initialValues.picText, initialValues.picId, true, true);
                picSelect.append(option).trigger('change');
            }

            $('#is_potential').on('change', function() {
                const statusSpan = $('#_status');
                if (this.checked) {
                    statusSpan.text('Ya')
                        .removeClass('text-danger fw-normal')
                        .addClass('text-success fw-bold');
                    $('#is_potential_hidden').val('1');
                } else {
                    statusSpan.text('Tidak')
                        .removeClass('text-success fw-bold')
                        .addClass('text-danger fw-normal');
                    $('#is_potential_hidden').val('0');
                }
            }).trigger('change');

            $('button[type="reset"]').on('click', function(e) {
                e.preventDefault();
                
                // Reset simple form elements
                $('#name').val("{{ $crm_prospect->name }}");
                $('#pipeline').val(initialValues.pipeline);
                $('#description').val(`{{ $crm_prospect->description }}`);
                $('#rupiah').val("{{ number_format($crm_prospect->nominal, 0, ',', '.') }}");
                $('#is_potential').prop('checked', initialValues.is_potential).trigger('change');

                // Reset PIC
                $('#users-select2').empty().append(new Option(initialValues.picText, initialValues.picId, true, true)).trigger('change');

                // Reset Prospect Type and Data
                $('#prospect_type').val(initialValues.prospect_type).trigger('change');
                setTimeout(function() {
                    $('#prospect-select2').empty().append(new Option(initialValues.prospectText, initialValues.prospectId, true, true)).trigger('change');
                }, 150);
            });

            $('#rupiah').on('keyup', function(e) {
                this.value = formatRupiah(this.value, "");
            });

            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
            }
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
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
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
