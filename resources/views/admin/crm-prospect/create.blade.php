@extends('layouts.admin', [
    'second_title' => 'Tambah CRM Prospect',
    'header_title' => 'Tambah CRM Prospect',
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
                        <li class="breadcrumb-item active" aria-current="page">Tambah Prospect</li>
                    </ol>
                </nav>
                <a href="{{ route('adm.crm-pipeline.index', ['type' => request()->query('type')]) }}"
                    class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <form id="prospect-form" action="{{ route('adm.crm-prospect.store') }}" method="post" accept-charset="utf-8">
                @csrf
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Formulir Tambah Prospect</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Prospect</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-user-star-line"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" placeholder="Contoh: Prospek Q4"
                                        value="{{ old('name') }}" required>
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
                                                {{ old('pipeline', $pipelines->first()->id ?? '') == $pipeline->id ? 'selected' : '' }}>
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
                                    <option value="donatur">Donatur (Menjadikan donatur menjadi donatur setia)</option>
                                    <option value="organization">Lembaga (melakukan Follow Up Lembaga yang sudah
                                        berkerja sama)</option>
                                    <option value="grab_organization">Lembaga hasil Grab (melakukan Follow Up lembaga hasil grab
                                        agar mau berkerja sama)</option>
                                </select>

                                @error('prospect_type')
                                    <div class="invalid-feedback d-block"><i class="ri-error-warning-line"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prospect-select2" class="form-label required" id="prospect-label">Pilih
                                    Donatur</label>
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
                                    name="nominal" id="rupiah" placeholder="100.000.000" value="{{ old('nominal') }}"
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
                                placeholder="Jelaskan detail prospek ini...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3 ml-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_potential" id="is_potential"
                                    value="1" {{ old('is_potential', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_potential">Status Potensial: <span
                                        id="_status"></span></label>
                                <input type="hidden" name="is_potential_hidden" id="is_potential_hidden"
                                    value="1">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end bg-light flex flew-row gap-3">
                        <button type="reset" class="btn btn-outline-danger">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i> Simpan Prospect
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
            // Konfigurasi pagination
            const initialLoad = 10; // Jumlah data pertama yang di-load
            const nextLoad = 15; // Jumlah data tambahan saat scroll

            // Fungsi untuk menginisialisasi Select2 dengan format response yang benar
            function initializeSelect2(elementId, placeholder, ajaxUrl, textMapping) {
                return $(elementId).select2({
                    placeholder: placeholder,
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownAutoWidth: true,
                    allowClear: true,
                    ajax: {
                        url: ajaxUrl,
                        delay: 300, // Delay sedikit lebih lama untuk mengurangi request
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term || '',
                                page: params.page || 1,
                                per_page: params.page ? nextLoad : initialLoad
                            };
                        },
                        processResults: function(data, params) {
                            // Pastikan response sesuai dengan format yang diharapkan
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

            // Konfigurasi untuk berbagai jenis prospek
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

            // Fungsi untuk mengupdate select prospect berdasarkan jenis yang dipilih
            function updateProspectSelect(type) {
                const config = prospectConfigs[type];
                const $select = $('#prospect-select2');
                const $label = $('#prospect-label');
                const $nominalGroup = $('.nominal-group');

                // Update label
                $label.text(config.label);

                // Hancurkan instance Select2 sebelumnya jika ada
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                // Kosongkan select
                $select.empty();

                // Inisialisasi Select2 baru dengan paginasi
                initializeSelect2('#prospect-select2', config.placeholder, config.url, config.textMapping);

                // Sembunyikan/tampilkan field nominal berdasarkan tipe
                if (type === 'donatur') {
                    $nominalGroup.show();
                    $('#rupiah').prop('required', true);
                } else {
                    $nominalGroup.hide();
                    $('#rupiah').prop('required', false);
                }
            }

            // Inisialisasi pertama kali
            $('#prospect_type').on('change', function() {
                updateProspectSelect($(this).val());
            }).trigger('change');

            // Inisialisasi Select2 untuk PIC dengan paginasi
            initializeSelect2(
                '#users-select2',
                'Cari PIC...',
                "{{ route('adm.users.select2.all') }}",
                item => item.name
            );

            // Handler untuk switch status potensial
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

            // Handler untuk reset form
            $('#prospect-form').on('reset', function() {
                $('#prospect_type').val('donatur').trigger('change');
                $('#users-select2').val(null).trigger('change');
                $('#pipeline').prop('selectedIndex', 0);
                $('#is_potential').prop('checked', true).trigger('change');
            });

            // Format input nominal ke Rupiah
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
@endsection
