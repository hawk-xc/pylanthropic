@extends('layouts.admin', [
    'second_title' => 'Detail CRM Prospect',
    'header_title' => 'Detail CRM Prospect',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'crm-leads',
])

@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
@endsection

@section('css_inline')
    <style>
        .border {
            border: 1px solid #dee2e6 !important;
        }

        .border-top {
            border-top: 1px solid #dee2e6 !important;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .border-end {
            border-right: 1px solid #dee2e6 !important;
        }

        .list-group-item.active {
            background-color: #f0f7ff;
            color: #0d6efd;
            border-left: 3px solid #0d6efd !important;
        }

        .badge.bg-light {
            background-color: #f8f9fa !important;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 13px;
            width: 2px;
            height: 100%;
            background-color: #dee2e6;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            position: absolute;
            left: -2px;
            top: 0;
            z-index: 1;
        }

        .timeline li::after {
            content: '';
            display: table;
            clear: both;
        }

        @media (max-width: 767.98px) {
            .border-end {
                border-right: none !important;
                border-bottom: 1px solid #dee2e6 !important;
            }
        }
    </style>
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
                                    href="{{ route('adm.program.index') }}">CRM Leads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Prospect</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <form action="{{ route('adm.crm-prospect.destroy', $crm_prospect->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="leads" value="{{ request()->query('leads') }}">
                        <button type="button" class="btn btn-outline-danger delete-prospect-btn">
                            <i class="fa fa-trash-alt mr-1"></i> Hapus
                        </button>
                    </form>
                    <a class="btn btn-outline-primary"
                        href="{{ route('adm.crm-prospect.edit', $crm_prospect->id) }}?leads={{ request()->query('leads') }}">
                        <i class="fa fa-edit mr-1"></i> Edit
                    </a>
                    <a class="btn btn-outline-dark"
                        href={{ route('adm.crm-leads.index', ['leads' => request()->query('leads')]) }}>
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="divider"></div>

            <div class="container-fluid">
                <div class="row" style="height: 75vh;">
                    <!-- Sidebar User Info (Kiri) -->
                    <div class="col-md-4 col-lg-2 p-0 border-end">
                        <div class="d-flex flex-column">
                            <!-- User Profile -->
                            <div class="p-3">
                                <div class="d-flex flex-column align-items-start gap-3">
                                    <div>
                                        <h5 class="mb-0 fw-bold">
                                            <a
                                                href="{{ route('adm.donatur.show', $crm_prospect->crm_prospect_donatur->id) }}">
                                                {{ $crm_prospect->crm_prospect_donatur->name }}
                                            </a>
                                        </h5>
                                        <small class="text-muted">{{ $crm_prospect->crm_prospect_donatur->telp }}</small>
                                    </div>
                                    <div>
                                        <span class="d-block">Nominal :</span>
                                        <small>
                                            {{ 'Rp. ' . number_format($crm_prospect->nominal, 0, ',', '.') }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="d-block">Status potensi :</span>
                                        <small>
                                            {{ $crm_prospect->is_potential ? 'Potensial' : 'Tidak Potensial' }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="d-block">Deskripsi :</span>
                                        <small>
                                            {{ $crm_prospect->description }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- User Menu -->
                            <div class="flex-grow-1 overflow-auto">
                                <span class="d-block ml-3 mb-2">Tambah Aktifitas :</span>
                                <div class="flex-grow-1 overflow-auto">
                                    <div class="list-group list-group-flush">
                                        @php
                                            $menus = [
                                                ['icon' => 'fas fa-sticky-note', 'label' => 'Note'],
                                                ['icon' => 'fas fa-envelope', 'label' => 'Email'],
                                                ['icon' => 'fas fa-phone', 'label' => 'Call'],
                                                ['icon' => 'fas fa-comment', 'label' => 'WA'],
                                                ['icon' => 'fas fa-tasks', 'label' => 'Task'],
                                            ];
                                        @endphp
                                        <!-- Custom Action Menu: Note, Email, Call, Log, Task -->
                                        <div class="d-flex flex-wrap flex-row px-3 pb-3 justify-content-between">
                                            @foreach ($menus as $menu)
                                                <div
                                                    style="display: flex; flex-direction: row; gap: 5px; justify-content: center; align-items: center;">
                                                    <a href="javascript:void(0)"
                                                        onclick="event.preventDefault(); openProspectActivityAddModal('{{ $menu['label'] }}')"
                                                        style="text-decoration: none; display: flex; flex-direction: column; justify-content: center; align-items: center; cursor: pointer;">
                                                        <i class="fas {{ $menu['icon'] }} fs-5"
                                                            style="padding: 10px; background-color: #f0f7ff; border-radius: 100%"></i>
                                                        <span style="color: #3d4044;">{{ $menu['label'] }}</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Stats -->
                            <div class="p-3 border-top">
                                <div class="mb-3">
                                    PIC : <span class="fw-bold">{{ $crm_prospect_pic->name }}</span>
                                </div>
                                {{-- <div class="d-flex justify-content-between mb-2">
                                    <small>Prospect</small>
                                    <span class="badge bg-light text-dark border">12</span>
                                </div> --}}
                                {{-- <div class="d-flex justify-content-between mb-2">
                                    <small>Deals Won</small>
                                    <span class="badge bg-light text-dark border">8</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>Target</small>
                                    <span class="badge bg-light text-dark border">75%</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <!-- Leads Activity (Kanan) -->
                    <div class="col-md-8 col-lg-10 p-0">
                        <div class="d-flex flex-column">
                            <!-- Activity Header -->
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Aktifitas Prospect <span class="fw-bold"
                                        style="color: #5a63db;">{{ $crm_prospect->name }}</span> </h5>
                                {{-- <div>
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div> --}}
                            </div>

                            <!-- Activity Content -->
                            <div class="flex-grow-1 overflow-auto p-3" style="max-height: 30vh; overflow: scroll">
                                <!-- Activity Item -->
                                @forelse ($crm_prospect->crm_prospect_activities as $crm_prospect_activity)
                                    <div class="d-flex mb-3 pb-3 border-bottom">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-flex justify-content-center align-items-center"
                                                style="width: 40px; height: 40px;">
                                                @switch($crm_prospect_activity->type)
                                                    @case('wa')
                                                        <i class="fas fa-comment" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @case('sms')
                                                        <i class="fas fa-sms" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @case('email')
                                                        <i class="fas fa-envelope" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @case('call')
                                                        <i class="fas fa-phone" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @case('meeting')
                                                        <i class="fas fa-calendar-check" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @case('note')
                                                        <i class="fas fa-sticky-note" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @case('task')
                                                        <i class="fas fa-tasks" style="font-size: 21px; color: white;"></i>
                                                    @break

                                                    @default
                                                @endswitch

                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-1"><span
                                                        class="fw-bold">{{ $crm_prospect_activity->type }}</span> :
                                                    {{ $crm_prospect_activity->content }}</h6>
                                                <small class="text-muted">{{ $crm_prospect_activity->date }}</small>
                                            </div>
                                            <p class="mb-0 text-muted">
                                                {{ $crm_prospect_activity->description }}
                                            </p>
                                        </div>
                                    </div>
                                    @empty
                                        <div class="d-flex w-100 justify-content-center align-items-center align-content-center"
                                            style="color: #797d83;">
                                            <h6 class="mb-1">Belum ada Aktifitas</h6>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center"></div>

                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Riwayat Prospect <span class="fw-bold"
                                            style="color: #5a63db;">{{ $crm_prospect->name }}</span></h5>
                                    {{-- <div>
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div> --}}
                                </div>

                                <!-- Timeline Container -->
                                <div class="px-4 py-3 flex-grow-1 overflow-auto"
                                    style="max-height: 50vh; height: 30vh; overflow: scroll">
                                    <ul class="timeline list-unstyled position-relative">
                                        @forelse ($crm_prospect->crm_prospect_logs as $index => $crm_prospect_log)
                                            <li class="mb-5 position-relative ps-4">
                                                <div
                                                    class="timeline-icon {{ $loop->last ? 'bg-primary' : 'bg-success' }} text-white d-flex align-items-center justify-content-center">
                                                    @if ($loop->last)
                                                        <i class="fas fa-circle"></i>
                                                    @else
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                </div>
                                                <div class="timeline-content ml-4">
                                                    <h6 class="fw-bold mb-1">{{ $crm_prospect_log->pipeline_name }}
                                                        @if ($loop->last)
                                                            (Saat ini)
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted">{{ $crm_prospect_log->created_at }}</small>
                                                    <p class="mb-0 text-muted">Perubahan oleh :
                                                        {{ $crm_prospect_log->user->name }} </p>
                                                </div>
                                            </li>
                                        @empty
                                            <div class="d-flex w-100 justify-content-center align-items-center align-content-center"
                                                style="color: #797d83;">
                                                <h6 class="mb-1">Belum ada riwayat</h6>
                                            </div>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('content_modal')
        <!-- Modal Tambah Prospect Activity -->
        <div class="modal fade" id="modal_add_prospect_activity" class="modal fade" tabindex="-1" role="dialog"
            aria-hidden="true" aria-labelledby="prospectActivityAddModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="prospectActivityAddForm" action="{{ route('adm.crm-prospect-activity.store') }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="prospect_id" value="{{ $crm_prospect->id }}">
                        <div class="modal-header pt-2 pb-2">
                            <h1 class="modal-title fs-5" id="modalTitle">Tambah Aktifitas Prospect</h1>
                            <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start pt-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Aktifitas</label>
                                <input type="hidden" name="type_name" id="type_name" />
                                <select id="activityTypeSelect" id="type" name="type" class="form-select">
                                    @foreach ($menus as $menu)
                                        <option value="{{ strtolower($menu['label']) }}"
                                            {{ old('type') === $menu['label'] ? 'selected' : '' }}>
                                            <i class="{{ $menu['icon'] }}"></i> {{ $menu['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten</label>
                                <textarea class="form-control" id="contennt" name="content" rows="3"
                                    placeholder="Masukkan Konten Aktifitas Prospect"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Masukkan deskripsi Aktifitas Prospect"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="date_time" class="form-label">Tanggal dan Jam</label>
                                <input type="datetime-local" class="form-control" id="date_time" name="date_time"
                                    value="{{ date('Y-m-d\TH:i') }}">
                            </div>
                        </div>
                        <div class="modal-footer pt-2 pb-2">
                            <input type="hidden" id="id_trans" name="id_trans" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('js_plugins')
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection

    @section('js_inline')
        <script type="text/javascript">
            function openProspectActivityAddModal(type) {
                $('#activityTypeSelect').prop('disabled', true);

                $('#type_name').val(type.toLowerCase());

                $('#activityTypeSelect').val(function() {
                    // Cari value yang cocok (case-insensitive)
                    let matchedValue = '';
                    $(this).find('option').each(function() {
                        if ($(this).val().toLowerCase() === type.toLowerCase()) {
                            matchedValue = $(this).val();
                            return false; // break loop
                        }
                    });
                    return matchedValue;
                });

                $('#modal_add_prospect_activity').modal('show');
            }

            $(document).on('click', '.delete-prospect-btn', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data prospect akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    @endsection
