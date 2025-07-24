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
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0 pl-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{ route('adm.crm-leads.index') }}">CRM Leads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Pipeline</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl d-flex flex-row justify-content-end gap-2">
                    <form id="deleteForm" action="{{ route('adm.crm-pipeline.destroy', $pipeline->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="leads" value="{{ request()->query('leads') }}">
                        <button type="button" class="btn btn-outline-danger" id="deleteButton">
                            <i class="fa fa-trash"></i> Hapus Data Ini
                        </button>
                    </form>

                    <a class="btn btn-outline-primary"
                        href='/adm/crm-leads?leads={{ request()->query('leads') }}'>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            <form action="{{ route('adm.crm-pipeline.update', $pipeline->id) }}" method="post" accept-charset="utf-8"
                class="row gy-3">
                @csrf
                @method('put')
                @php
                    $leadsQuery = strtolower(request()->query('leads'));
                @endphp

                <input type="hidden" id="leads-query" value="{{ $leadsQuery }}">
                <input type="hidden" name="leads_id" id="hidden-leads-id">

                <div class="col-12">
                    <label class="form-label">Nama Pipeline</label>
                    <input type="text" class="form-control" name="name" id="name"
                        placeholder="Masukkan Nama Pipeline" value="{{ old('name', $pipeline->name) }}" required>
                    @error('name')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label required">Pilih Leads</label>
                    <select class="form-control form-control-sm" name="leads_id" id="leads-select2" required></select>
                    @error('leads_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="percentage_deals" class="form-label">Percentage Deals</label>
                    <input type="number" class="form-control" id="percentage_deals" name="percentage_deals" min="1"
                        max="100" placeholder="Masukkan persentase deals disini"
                        value="{{ old('percentage_deals', $pipeline->percentage_deals) }}" required>
                    @error('percentage_deals')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan deskripsi">{{ old('description', $pipeline->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2 ml-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="status_is_active"
                            value="1" {{ old('is_active', $pipeline->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_is_active">Status Pipeline (<span
                                id="_status">{{ $pipeline->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>)</label>
                        <input type="hidden" name="is_active_hidden" id="status_is_active_hidden" value="1">
                    </div>
                </div>

                <div class="col-12">
                    <div class="divider mb-2 mt-2"></div>
                </div>
                <div class="col-12 text-end">
                    <input type="reset" class="btn btn-danger" value="Reset">
                    <input type="submit" class="btn btn-info" value="Update">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            let leadsQuery = $('#leads-query').val();
            let leadsSelect = $("#leads-select2");

            leadsSelect.select2({
                placeholder: 'Cari Leads',
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
                        var items = $.map(data.data, function(obj) {
                            obj.id = obj.id;
                            obj.text = obj.name;
                            return obj;
                        });
                        params.page = params.page || 1;

                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    }
                },
                templateResult: function(item) {
                    if (item.loading) return item.text;
                    return item.text;
                },
                language: {
                    searching: function(params) {
                        select2_query = params;
                        return 'Searching...';
                    }
                }
            });

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
                            // Tambahkan option dan pilih secara otomatis
                            let option = new Option(matched.name, matched.id, true, true);
                            leadsSelect.append(option).trigger('change');

                            // Salin id ke input hidden agar tetap terkirim
                            $('#hidden-leads-id').val(matched.id);

                            // Disable input agar tidak bisa diubah
                            leadsSelect.prop('disabled', true);
                        }
                    }
                });
            }

            $('#status_is_active').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#_status').text('Aktif');
                    $('#status_is_active_hidden').val('1');
                } else {
                    $('#_status').text('Tidak Aktif');
                    $('#status_is_active_hidden').val('0');
                }
            });

            $('#deleteButton').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $('#deleteForm').submit();
                    }
                });
            });
        });
    </script>

    <script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['type'] }}',
                title: '{{ session('message')['text'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-start justify-content-start align-item-start justify-item-start'
                },
                background: '{{ session('message')['type'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['type'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection
