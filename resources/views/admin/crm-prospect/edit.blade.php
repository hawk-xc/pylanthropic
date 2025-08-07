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
                <a href="{{ route('adm.crm-pipeline.index', ['type' => request()->query('type')]) }}"
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
                            <div class="col-md-6 mb-3">
                                <label for="donatur-select2" class="form-label required">Pilih Donatur</label>
                                <select class="form-control @error('donatur') is-invalid @enderror" name="donatur"
                                    id="donatur-select2" required></select>
                                @error('donatur')
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

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_potential" id="is_potential"
                                    value="1" {{ old('is_potential', $crm_prospect->is_potential) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_potential">Status Potensial: <span
                                        id="_status"></span></label>
                                <input type="hidden" name="is_potential_hidden"
                                    value="{{ $crm_prospect->is_potential ? 1 : 0 }}" id="is_potential_hidden">
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            function initializeSelect2(elementId, placeholder, ajaxUrl, textMapping, initialId, initialText) {
                const selectElement = $(elementId);

                selectElement.select2({
                    placeholder: placeholder,
                    theme: 'bootstrap-5',
                    allowClear: true,
                    ajax: {
                        url: ajaxUrl,
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
                                        text: textMapping(item)
                                    };
                                }),
                                pagination: {
                                    more: params.page < data.last_page
                                }
                            };
                        }
                    }
                });

                if (initialId && initialText) {
                    let option = new Option(initialText, initialId, true, true);
                    selectElement.append(option).trigger('change');
                }
            }

            const donaturId = "{{ old('donatur', $crm_prospect->donatur_id) }}";
            const donaturText = "{{ old('donatur_text', $crm_prospect->crm_prospect_donatur->name ?? '') }}";
            const picId = "{{ old('assign_to', $crm_prospect->assign_to) }}";
            const picText = "{{ old('assign_to_text', $crm_prospect->crm_prospect_pic->name ?? '') }}";

            initializeSelect2('#donatur-select2', 'Cari Donatur...', "{{ route('adm.donatur.select2.all') }}",
                item => `${item.name} (${item.telp})`, donaturId, donaturText);

            initializeSelect2('#users-select2', 'Cari PIC...', "{{ route('adm.users.select2.all') }}", item => item
                .name, picId, picText);

            $('#is_potential').on('change', function() {
                const statusSpan = $('#_status');
                if (this.checked) {
                    statusSpan.text('Ya').removeClass('text-danger fw-normal').addClass(
                        'text-success fw-bold');
                    $('#is_potential_hidden').val('1');
                } else {
                    statusSpan.text('Tidak').removeClass('text-success fw-bold').addClass(
                        'text-danger fw-normal');
                    $('#is_potential_hidden').val('0');
                }
            }).trigger('change');

            $('#prospect-form').on('reset', function() {
                initializeSelect2('#donatur-select2', 'Cari Donatur...',
                    "{{ route('adm.donatur.select2.all') }}", item => `${item.name} (${item.telp})`,
                    donaturId, donaturText);
                initializeSelect2('#users-select2', 'Cari PIC...', "{{ route('adm.users.select2.all') }}",
                    item => item.name, picId, picText);

                $('#pipeline').val("{{ old('pipeline', $crm_prospect->crm_pipeline_id) }}");
                $('#is_potential').prop('checked', {{ old('is_potential', $crm_prospect->is_potential) ? 'true' : 'false' }})
                    .trigger('change');
            });

            var rupiah = document.getElementById("rupiah");
            rupiah.addEventListener("keyup", function(e) {
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
@endsection