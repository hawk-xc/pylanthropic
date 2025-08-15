@extends('layouts.admin', [
    'second_title' => 'Donatur',
    'header_title' => 'Donatur',
    'sidebar_menu' => 'person',
    'sidebar_submenu' => 'donatur',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .big-checkbox .form-check-input {
            width: 16px;
            height: 16px;
            margin-top: 3px !important;
        }

        .big-checkbox .form-check-label {
            margin-left: 6px;
        }

        .big-checkbox {
            min-height: auto !important;
        }
    </style>
@endsection


@section('content')
    <div class="main-card mb-2 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.donatur.index') }}">Donatur</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary" href={{ route('adm.donatur.index') }}>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <td>{{ ucwords($donatur->name) }}</td>
                    </tr>
                    <tr>
                        <th>Telp</th>
                        <td>
                            {{ $donatur->telp }}
                            {!! is_null($donatur->wa)
                                ? '<span class="badge badge-success badge-sm">Aktif</span>'
                                : '<span class="badge badge-danger badge-sm">Tidak Aktif</span>' !!}
                            {!! $donatur->want_to_contact == 1
                                ? '<span class="badge badge-success badge-sm">MAU</span>'
                                : '<span class="badge badge-danger badge-sm">Jangan Dihubungi</span>' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ isset($donatur->email) ? $donatur->email : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $donatur->is_muslim == 1 ? 'Islam' : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terdaftar</th>
                        <td>{{ date('Y-m-d H:i:s', strtotime($donatur->created_at)) }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Semua Donasi</th>
                        <td>Rp.{{ number_format($sum_donate_all, 0, ',', '.') }}
                            ({{ number_format($count_donate_all, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <th>Jumlah Donasi Dibayar</th>
                        <td>Rp.{{ number_format($sum_donate_paid, 0, ',', '.') }}
                            ({{ number_format($count_donate_paid, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <th>Jumlah Donasi Belum Dibayar</th>
                        <td>
                            Rp.{{ number_format($sum_donate_all - $sum_donate_paid, 0, ',', '.') }}
                            ({{ number_format($count_donate_all - $count_donate_paid, 0, ',', '.') }})
                        </td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <div class="main-card mb-2 mt-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <h5 class="fw-semibold">Donasi Tetap</h5>
                </div>
                <div class="col-10 fc-rtl">
                    <button class="btn btn-outline-primary mr-1" id="refresh_table_donate4"><i class="fa fa-sync"></i>
                        Refresh</button>
                    <button class="btn btn-primary" id="filter-54" onclick="openDonaturLoyalModal()">
                        <i class="fa fa-plus mr-1" id="filter-54-icon"></i> Tambah
                    </button>
                </div>
                <div class="divider"></div>
                <table id="table-donatur-loyal" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Periode Setiap</th>
                            <th>Nominal</th>
                            <th>Program</th>
                            <th>Deskripsi/Keterangan</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <div class="main-card mb-2 mt-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <h5 class="fw-semibold">Riwayat Donasi</h5>
                    </div>
                    <div class="col-10 fc-rtl">
                        <button class="btn btn-outline-primary filter_payment" id="filter-bni" data-id="bni">BNI</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bsi" data-id="bsi">BSI</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bri" data-id="bri">BRI</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-qris" data-id="qris">QRIS</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-gopay"
                            data-id="gopay">Gopay</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-mandiri"
                            data-id="mandiri">Mandiri</button>
                        <button class="btn btn-outline-primary" id="filter-fu"><i class="fa fa-filter mr-1"
                                id="filter-fu-icon"></i> Butuh FU</button>
                        <button class="btn btn-outline-primary" id="filter-1day"><i class="fa fa-filter mr-1"
                                id="filter-1day-icon"></i> Show Kemarin</button>
                        <button class="btn btn-outline-primary" id="filter-5day"><i class="fa fa-check mr-1"
                                id="filter-5day-icon"></i> Show 5 Hari</button>
                        <button class="btn btn-outline-primary mr-1" id="refresh_table_donate"><i class="fa fa-sync"></i>
                            Refresh</button>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row">
                    <div class="col-12 form-inline">
                        <span>Filter :</span>
                        <input type="text" id="donatur_name" placeholder="Nama Donatur"
                            class="form-control form-control-sm me-1 ms-2">
                        <input type="text" id="donatur_telp" placeholder="Telp Donatur ex: 8574..."
                            class="form-control form-control-sm me-1">
                        <input type="text" id="filter_nominal" placeholder="Nominal"
                            class="form-control form-control-sm me-1">
                        <input type="text" id="donatur_title" placeholder="Judul Program"
                            class="form-control form-control-sm me-1">
                        <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                    </div>
                </div>
                <div class="divider"></div>
                <table id="table-donatur" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jml Donasi</th>
                            <th>Judul</th>
                            <th>Staus</th>
                            <th>Tgl Donasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="fu_val" value="0">
        <input type="hidden" id="1day_val" value="0">
        <input type="hidden" id="5day_val" value="0">
        <input type="hidden" id="bni_val" value="0">
        <input type="hidden" id="bsi_val" value="0">
        <input type="hidden" id="bri_val" value="0">
        <input type="hidden" id="qris_val" value="0">
        <input type="hidden" id="mandiri_val" value="0">
        <input type="hidden" id="gopay_val" value="0">


        <div class="main-card mb-2 mt-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <h5 class="fw-semibold">Program di Donasi</h5>
                    </div>
                    <div class="col-9 fc-rtl">
                        <button class="btn btn-outline-primary filter_payment" id="filter-bni"
                            data-id="bni">Seminggu</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bsi"
                            data-id="bsi">Sebulan</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bsi"
                            data-id="bsi">Setahun</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bsi"
                            data-id="bsi">Islami</button>
                        <button class="btn btn-primary" id="filter-5day"><i class="fa fa-check mr-1"
                                id="filter-5day-icon"></i> Show 5 Hari</button>
                        <button class="btn btn-outline-primary mr-1" id="refresh_table_donate"><i class="fa fa-sync"></i>
                            Refresh</button>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row">
                    <div class="col-12 form-inline">
                        <span>Filter :</span>
                        <input type="text" id="program_title" placeholder="Judul Program"
                            class="form-control form-control-sm me-1 ms-2">
                        <input type="text" id="donatur_telp" placeholder="Dari/Ke Telp ex: 8574..."
                            class="form-control form-control-sm me-1">
                        <input type="text" id="filter_nominal" placeholder="Isi Chat"
                            class="form-control form-control-sm me-1">
                        <input type="text" id="donatur_title" placeholder="Keterangan"
                            class="form-control form-control-sm me-1">
                        <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                    </div>
                </div>
                <div class="divider"></div>
                <table id="table-donatur-program" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Judul Program</th>
                            <th>Detail Program</th>
                            <th>Nominal</th>
                            <th>Cerita</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="main-card mb-2 mt-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <h5 class="fw-semibold">Riwayat Chat</h5>
                    </div>
                    <div class="col-10 fc-rtl">
                        <button class="btn btn-outline-primary filter_payment" id="filter-bni"
                            data-id="bni">Masuk</button>
                        <button class="btn btn-outline-primary filter_payment" id="filter-bsi"
                            data-id="bsi">Keluar</button>
                        <button class="btn btn-primary" id="filter-5day"><i class="fa fa-check mr-1"
                                id="filter-5day-icon"></i> Show 5 Hari</button>
                        <button class="btn btn-outline-primary mr-1" id="refresh_table_donate"><i class="fa fa-sync"></i>
                            Refresh</button>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row">
                    <div class="col-12 form-inline">
                        <span>Filter :</span>
                        <input type="text" id="donatur_name" placeholder="Jenis"
                            class="form-control form-control-sm me-1 ms-2">
                        <input type="text" id="donatur_telp" placeholder="Dari/Ke Telp ex: 8574..."
                            class="form-control form-control-sm me-1">
                        <input type="text" id="filter_nominal" placeholder="Isi Chat"
                            class="form-control form-control-sm me-1">
                        <input type="text" id="donatur_title" placeholder="Keterangan"
                            class="form-control form-control-sm me-1">
                        <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                    </div>
                </div>
                <div class="divider"></div>
                <table id="table-donatur-chat" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Isi Chat</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
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
        <!-- Modal Tambah Donatur Loyal -->
        <div class="modal fade" id="modal_donatur_loyal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="donaturModalLabel">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="donaturLoyalForm" action="{{ route('adm.donatur-loyal.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="donatur_id" value="{{ $donatur->id }}">
                        <div class="modal-header pt-2 pb-2">
                            <h1 class="modal-title fs-5" id="modalTitle">Tambah Donasi Tetap</h1>
                            <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold required">Pilih Program</label>
                                <select class="form-control form-control-sm" name="program" id="program-select2"
                                    required></select>
                                @error('program')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="donasi_periode" class="form-label">Periode Donasi</label>
                                <select class="form-select" id="donasi_periode" name="donasi_periode"
                                    onchange="handlePeriodeChange(this.value)">
                                    <option value="daily">Harian (Jam pada tanggal)</option>
                                    <option value="weekly">Mingguan (Jam & Hari)</option>
                                    <option value="monthly">Bulanan (Per Tanggal)</option>
                                    <option value="yearly">Tahunan (Per Bulan)</option>
                                </select>
                                @error('donasi_periode')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="periode_fields"></div>
                            <div class="mt-2 mb-3" style="margin: auto;">
                                <label for="donasi_nominal" class="form-label">Nominal</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RP</span>
                                    </div>
                                    <input class="form-control form-control-lg" id="rupiah" name="amount"
                                        placeholder="0" type="text" value="" />
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="form-check big-checkbox mb-0 ms-1">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkgenap">
                                                <label class="form-check-label" for="checkgenap"> Genapkan</label>
                                            </div>
                                        </span>
                                    </div> --}}
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold required">Pilih Metode Pembayaran</label>
                                <select class="form-control form-control-sm" name="payment_type" id="payment_type-select2"
                                    required></select>
                                @error('payment_type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="donasi_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="donasi_description" name="donasi_description" rows="3"
                                    placeholder="Masukkan deskripsi donasi tetap"></textarea>
                            </div>
                            <div class="form-check form-switch mt-2 ml-3">
                                <input class="form-check-input" type="checkbox" name="status_donasi_tetap"
                                    id="status_donasi_tetap" value="1" checked>
                                <label class="form-check-label" for="status_donasi_tetap">Status Donasi Tetap (<span
                                        id="_status">Aktif</span>)</label>
                                <input type="hidden" name="status_donasi_tetap_hidden" id="status_donasi_tetap_hidden"
                                    value="1">
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

        <!-- Modal Edit Donatur Loyal -->
        <div class="modal fade" id="edit_modal_donatur_loyal" tabindex="-1" aria-labelledby="editDonaturModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="editDonaturLoyalForm" action="{{ route('adm.donatur-loyal.update', $donatur->id) }}"
                        method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="donatur_id" value="{{ $donatur->id }}">
                        <input type="hidden" id="donaturLoyalId" name="donatur_loyal_id" value="{{ $donatur->id }}">
                        <div class="modal-header pt-2 pb-2">
                            <h1 class="modal-title fs-5" id="modalTitle">Edit data Donasi Tetap</h1>
                            <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold required">Pilih Program</label>
                                <select class="form-control form-control-sm" name="program" id="edit_program-select2"
                                    required></select>
                                @error('program')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_donasi_periode" class="form-label">Periode Donasi</label>
                                <select class="form-select" id="edit_donasi_periode" name="donasi_periode"
                                    onchange="handlePeriodeChange(this.value, true)">
                                    <option value="daily">Harian (Jam pada tanggal)</option>
                                    <option value="weekly">Mingguan (Jam & Hari)</option>
                                    <option value="monthly">Bulanan (Per Tanggal)</option>
                                    <option value="yearly">Tahunan (Per Bulan)</option>
                                </select>
                            </div>
                            <div id="edit_periode_fields"></div>
                            <div class="mt-2 mb-3" style="margin: auto;">
                                <label for="donasi_nominal" class="form-label">Nominal</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RP</span>
                                    </div>
                                    <input class="form-control form-control-lg" id="edit_rupiah" name="amount"
                                        placeholder="0" type="text" value="" />
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="form-check big-checkbox mb-0 ms-1">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkgenap">
                                                <label class="form-check-label" for="checkgenap"> Genapkan</label>
                                            </div>
                                        </span>
                                    </div> --}}
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="donasi_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_donasi_description" name="donasi_description" rows="3"
                                    placeholder="Masukkan deskripsi donasi tetap"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold required">Pilih Metode Pembayaran</label>
                                <select class="form-control form-control-sm" name="payment_type" id="edit_payment_type-select2"
                                    required>
                                </select>
                                @error('payment_type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-check form-switch mt-2 ml-3">
                                <input class="form-check-input" type="checkbox" name="edit_status_donasi_tetap"
                                    id="edit_status_donasi_tetap" value="1" checked>
                                <label class="form-check-label" for="edit_status_donasi_tetap">Status Donasi Tetap (<span
                                        id="_edit_status">Aktif</span>)</label>
                                <input type="hidden" name="edit_status_donasi_tetap_hidden"
                                    id="edit_status_donasi_tetap_hidden" value="1">
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

        <!-- Modal FU Paid -->
        <div class="modal fade" id="modal_fu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header pt-2 pb-2">
                        <h1 class="modal-title fs-5" id="modalTitleFu">Modal title</h1>
                        <button type="button" class="btn-close pt-4" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pt-4">
                        <h5 class="mb-3">Kirim WA Dengan?</h5>
                        <input type="radio" class="btn-check" name="fu_name" id="fu_asli" autocomplete="off"
                            value="asli" checked>
                        <label class="btn btn-outline-primary" for="fu_asli">Sebut Nama Asli</label>
                        <input type="radio" class="btn-check" name="fu_name" id="fu_anda" autocomplete="off"
                            value="anda">
                        <label class="btn btn-outline-success" for="fu_anda">Sebut Dengan Anda</label>
                        <div>Kalau Nama tidak baik disebut di Wa maka pilih <strong>Sebut Dengan Anda</strong></div>
                    </div>
                    <div class="modal-footer pt-2 pb-2">
                        <input type="hidden" id="id_trans_fu" name="id_trans_fu" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="save_fu">Kirim WA</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js_plugins')
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
        </script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endsection

    @section('js_inline')
        <script type="text/javascript">
            function editStatus(id, status, nominal) {
                $("#id_trans").val(id);
                $("#rupiah").val(nominal.replace('Rp. ', ''));
                if (status == 'draft') {
                    document.getElementById("status_draft").disabled = true;
                    document.getElementById("status_draft").checked = true;
                    var status_show = 'BELUM DIBAYAR';
                } else if (status == 'success') {
                    document.getElementById("status_paid").disabled = true;
                    document.getElementById("status_paid").checked = true;
                    var status_show = 'SUDAH DIBAYAR';
                } else {
                    document.getElementById("status_cancel").disabled = true;
                    document.getElementById("status_cancel").checked = true;
                    var status_show = 'DIBATALKAN';
                }

                document.getElementById("checkgenap").checked = false;
                $("#modalTitle").html(nominal + ' - ' + status_show);
                let myModal = new bootstrap.Modal(document.getElementById('modal_status'));
                myModal.show();
            }

            function openDonaturLoyalModal() {
                let myModal = new bootstrap.Modal(document.getElementById('modal_donatur_loyal'));
                myModal.show();
            }

            function openEditDonaturLoyalModal(id) {
                fetch(`/adm/donatur-loyal/${id}/edit`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        // Tampilkan modal terlebih dahulu
                        let myEditModal = new bootstrap.Modal(document.getElementById('edit_modal_donatur_loyal'));
                        myEditModal.show();

                        // Isi form
                        const form = document.getElementById('editDonaturLoyalForm');
                        form.action = `/adm/donatur-loyal/${id}`;

                        // Set default value for program select2
                        const programSelect = $('#edit_program-select2');
                        if (data.data.program) {
                            var option = new Option(data.data.program.title, data.data.program.id, true, true);
                            programSelect.append(option).trigger('change');
                        }

                        // Set nilai periode donasi
                        const donasiPeriodeSelect = document.getElementById('edit_donasi_periode');
                        donasiPeriodeSelect.value = data.data.every_period;

                        // Panggil handlePeriodeChange untuk modal edit (parameter true)
                        handlePeriodeChange(data.data.every_period, true);

                        // Isi field periode sesuai dengan data
                        // Beri timeout untuk memastikan field sudah tergenerate
                        setTimeout(() => {
                            const prefix = 'edit_';
                            switch (data.data.every_period) {
                                case 'daily':
                                    if (data.data.every_time)
                                        document.getElementById(`${prefix}daily_time`).value = data.data.every_time;
                                    break;
                                case 'weekly':
                                    if (data.data.every_day)
                                        document.getElementById(`${prefix}weekly_day`).value = data.data.every_day
                                        .toLowerCase();
                                    if (data.data.every_time)
                                        document.getElementById(`${prefix}weekly_time`).value = data.data
                                        .every_time;
                                    break;
                                case 'monthly':
                                    if (data.data.every_date_period)
                                        document.getElementById(`${prefix}monthly_date`).value = data.data
                                        .every_date_period;
                                    break;
                                case 'yearly':
                                    if (data.data.every_month_period)
                                        document.getElementById(`${prefix}yearly_month`).value = data.data
                                        .every_month_period;
                                    break;
                            }
                        }, 500); // Timeout lebih lama untuk memastikan field sudah ada

                        // Isi field lainnya
                        document.getElementById('donaturLoyalId').value = data.data.id;
                        document.getElementById('edit_rupiah').value = formatRupiah(data.data.nominal.toString());
                        document.getElementById('edit_donasi_description').value = data.data.desc || '';

                        // Set default value for payment type select2
                        const paymentTypeSelect = $('#edit_payment_type-select2');
                        if (data.data.payment_type) {
                            const paymentType = data.data.payment_type;
                            const paymentText = paymentType.payment_code ?
                                `#${paymentType.id} ${paymentType.name} (${paymentType.payment_code})` :
                                `#${paymentType.id} ${paymentType.name}`;

                            var option = new Option(paymentText, paymentType.id, true, true);
                            paymentTypeSelect.append(option).trigger('change');
                        }

                        if (data.data.is_active) {
                            document.getElementById('_edit_status').textContent = 'Aktif';
                            document.getElementById('edit_status_donasi_tetap_hidden').value = '1';
                            document.getElementById('edit_status_donasi_tetap').checked = true;
                        } else {
                            document.getElementById('edit_status_donasi_tetap').checked = false;
                            document.getElementById('_edit_status').textContent = 'Tidak Aktif';
                            document.getElementById('edit_status_donasi_tetap_hidden').value = '0';
                        }

                        document.getElementById('id_trans').value = data.data.id;
                    })
                    .catch(error => {
                        console.error('Error details:', error);
                        alert('Gagal mengambil data donatur: ' + error.message);
                    });
            }

            var need_fu = $('#fu_val').val();
            var day5 = $('#5day_val').val();
            var day1 = $('#1day_val').val();

            $("#filter-5day").on("click", function() {
                let fil_5day = $('#5day_val').val();
                var need_fu = $('#fu_val').val();
                var fil_1day = $('#1day_val').val();
                var fil_bni = $('#bni_val').val();
                var fil_bsi = $('#bsi_val').val();
                var fil_bri = $('#bri_val').val();
                var fil_qris = $('#qris_val').val();
                var fil_gopay = $('#gopay_val').val();
                var fil_mandiri = $('#mandiri_val').val();
                if (fil_5day == 1) { // before click or want to change become 5 day off
                    $('#filter-5day-icon').removeClass('fa-check');
                    $('#filter-5day-icon').addClass('fa-filter');
                    $('#filter-5day').removeClass('btn-primary');
                    $('#filter-5day').addClass('btn-outline-primary');
                    $('#5day_val').val(0);
                    // donate_table(need_fu, fil_1day, 0, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                    donate_table();
                } else { // want to change become 5 day on
                    $('#filter-5day-icon').removeClass('fa-filter');
                    $('#filter-5day-icon').addClass('fa-check');
                    $('#filter-5day').removeClass('btn-outline-primary');
                    $('#filter-5day').addClass('btn-primary');
                    $('#5day_val').val(1);
                    // 1 day or yesterday button
                    $('#1day_val').val(0);
                    $('#filter-1day-icon').removeClass('fa-check');
                    $('#filter-1day-icon').addClass('fa-filter');
                    $('#filter-1day').removeClass('btn-primary');
                    $('#filter-1day').addClass('btn-outline-primary');
                    // donate_table(need_fu, 0, 1, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                    donate_table();
                }
            });

            $("#filter-1day").on("click", function() {
                let fil_1day = $('#1day_val').val();
                let fil_5day = $('#5day_val').val();
                var need_fu = $('#fu_val').val();
                var fil_bni = $('#bni_val').val();
                var fil_bsi = $('#bsi_val').val();
                var fil_bri = $('#bri_val').val();
                var fil_qris = $('#qris_val').val();
                var fil_gopay = $('#gopay_val').val();
                var fil_mandiri = $('#mandiri_val').val();
                if (fil_1day == 1) { // before click or want to change become 1 day off
                    $('#filter-5day-icon').removeClass('fa-check');
                    $('#filter-5day-icon').addClass('fa-filter');
                    $('#filter-5day').removeClass('btn-primary');
                    $('#filter-5day').addClass('btn-outline-primary');
                    $('#5day_val').val(0);
                    // donate_table(need_fu, 0, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                    donate_table();
                } else { // want to change become 1 day on
                    $('#filter-1day-icon').removeClass('fa-filter');
                    $('#filter-1day-icon').addClass('fa-check');
                    $('#filter-1day').removeClass('btn-outline-primary');
                    $('#filter-1day').addClass('btn-primary');
                    $('#1day_val').val(1);
                    // 5 day or yesterday button
                    $('#5day_val').val(0);
                    $('#filter-5day-icon').removeClass('fa-check');
                    $('#filter-5day-icon').addClass('fa-filter');
                    $('#filter-5day').removeClass('btn-primary');
                    $('#filter-5day').addClass('btn-outline-primary');
                    // donate_table(need_fu, 1, 0, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                    donate_table();
                }
            });

            $("#filter-fu").on("click", function() {
                let fil_5day = $('#5day_val').val();
                let fil_1day = $('#1day_val').val();
                var need_fu = $('#fu_val').val();
                var fil_bni = $('#bni_val').val();
                var fil_bsi = $('#bsi_val').val();
                var fil_bri = $('#bri_val').val();
                var fil_qris = $('#qris_val').val();
                var fil_gopay = $('#gopay_val').val();
                var fil_mandiri = $('#mandiri_val').val();
                if (need_fu == 0) {
                    $('#filter-fu-icon').removeClass('fa-filter');
                    $('#filter-fu-icon').addClass('fa-check');
                    $('#filter-fu').removeClass('btn-outline-primary');
                    $('#filter-fu').addClass('btn-primary');
                    $('#fu_val').val(1);
                    // donate_table(1, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                    donate_table();
                } else {
                    $('#filter-fu-icon').removeClass('fa-check');
                    $('#filter-fu-icon').addClass('fa-filter');
                    $('#filter-fu').removeClass('btn-primary');
                    $('#filter-fu').addClass('btn-outline-primary');
                    $('#fu_val').val(0);
                    // donate_table(0, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                    donate_table();
                }
            });

            $(".filter_payment").on("click", function() {
                let fil_5day = $('#5day_val').val();
                let fil_1day = $('#1day_val').val();
                var need_fu = $('#fu_val').val();
                var fil_bni = $('#bni_val').val();
                var fil_bsi = $('#bsi_val').val();
                var fil_bri = $('#bri_val').val();
                var fil_qris = $('#qris_val').val();
                var fil_gopay = $('#gopay_val').val();
                var fil_mandiri = $('#mandiri_val').val();
                var fil_payment = $(this).attr("data-id");

                if (fil_payment == 'bni') {
                    if (fil_bni == 0) {
                        $('#filter-bni').removeClass('btn-outline-primary');
                        $('#filter-bni').addClass('btn-primary');
                        $('#bni_val').val(1);
                        // donate_table(need_fu, fil_1day, fil_5day, 1, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                        donate_table();
                    } else {
                        $('#filter-bni').addClass('btn-outline-primary');
                        $('#filter-bni').removeClass('btn-primary');
                        $('#bni_val').val(0);
                        // donate_table(need_fu, fil_1day, fil_5day, 0, fil_bsi, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                        donate_table();
                    }
                } else if (fil_payment == 'bsi') {
                    if (fil_bsi == 0) {
                        $('#filter-bsi').removeClass('btn-outline-primary');
                        $('#filter-bsi').addClass('btn-primary');
                        $('#bsi_val').val(1);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, 1, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                        donate_table();
                    } else {
                        $('#filter-bsi').addClass('btn-outline-primary');
                        $('#filter-bsi').removeClass('btn-primary');
                        $('#bsi_val').val(0);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, 0, fil_bri, fil_qris, fil_gopay, fil_mandiri);
                        donate_table();
                    }
                } else if (fil_payment == 'bri') {
                    if (fil_bri == 0) {
                        $('#filter-bri').removeClass('btn-outline-primary');
                        $('#filter-bri').addClass('btn-primary');
                        $('#bri_val').val(1);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, 1, fil_qris, fil_gopay, fil_mandiri);
                        donate_table();
                    } else {
                        $('#filter-bri').addClass('btn-outline-primary');
                        $('#filter-bri').removeClass('btn-primary');
                        $('#bri_val').val(0);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, 0, fil_qris, fil_gopay, fil_mandiri);
                        donate_table();
                    }
                } else if (fil_payment == 'qris') {
                    if (fil_qris == 0) {
                        $('#filter-qris').removeClass('btn-outline-primary');
                        $('#filter-qris').addClass('btn-primary');
                        $('#qris_val').val(1);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, 1, fil_gopay, fil_mandiri);
                        donate_table();
                    } else {
                        $('#filter-qris').addClass('btn-outline-primary');
                        $('#filter-qris').removeClass('btn-primary');
                        $('#qris_val').val(0);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, 0, fil_gopay, fil_mandiri);
                        donate_table();
                    }
                } else if (fil_payment == 'gopay') {
                    if (fil_gopay == 0) {
                        $('#filter-gopay').removeClass('btn-outline-primary');
                        $('#filter-gopay').addClass('btn-primary');
                        $('#gopay_val').val(1);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, 1, fil_mandiri);
                        donate_table();
                    } else {
                        $('#filter-gopay').addClass('btn-outline-primary');
                        $('#filter-gopay').removeClass('btn-primary');
                        $('#gopay_val').val(0);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, 0, fil_mandiri);/
                        donate_table();
                    }
                } else { // mandiri
                    if (fil_mandiri == 0) {
                        $('#filter-mandiri').removeClass('btn-outline-primary');
                        $('#filter-mandiri').addClass('btn-primary');
                        $('#mandiri_val').val(1);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, 1);
                        donate_table();
                    } else {
                        $('#filter-mandiri').addClass('btn-outline-primary');
                        $('#filter-mandiri').removeClass('btn-primary');
                        $('#mandiri_val').val(0);
                        // donate_table(need_fu, fil_1day, fil_5day, fil_bni, fil_bsi, fil_bri, fil_qris, fil_gopay, 0);
                        donate_table();
                    }
                }
            });

            function hideFunc(name) {
                // const truck_modal = document.querySelector('#modal_status');
                const truck_modal = document.querySelector(name);
                const modal = bootstrap.Modal.getInstance(truck_modal);
                modal.hide();
            }

            $("#filter_search").on("click", function() {
                donate_table();
            });

            // function donate_table(need_fu_ar, day1_ar, day5_ar, bni_ar, bsi_ar, bri_ar, qris_ar, gopay_ar, mandiri_ar) {
            function donate_table() {
                let day5_ar = $('#5day_val').val();
                let day1_ar = $('#1day_val').val();
                let need_fu_ar = $('#fu_val').val();
                let bni_ar = $('#bni_val').val();
                let bsi_ar = $('#bsi_val').val();
                let bri_ar = $('#bri_val').val();
                let qris_ar = $('#qris_val').val();
                let gopay_ar = $('#gopay_val').val();
                let mandiri_ar = $('#mandiri_val').val();

                let donatur_name = $('#donatur_name').val();
                let donatur_telp = $('#donatur_telp').val();
                let filter_nominal = $('#filter_nominal').val();
                let donatur_title = $('#donatur_title').val();

                table.ajax.url("{{ route('adm.donate.datatables') }}/?donatur_id={{ $donatur->id }}&need_fu=" + need_fu_ar +
                    "&day1=" + day1_ar + "&day5=" + day5_ar + "&bni=" + bni_ar + "&bsi=" + bsi_ar + "&bri=" + bri_ar +
                    "&qris=" + qris_ar + "&gopay=" + gopay_ar + "&mandiri=" + mandiri_ar + "&donatur_name=" + encodeURI(
                        donatur_name) + "&donatur_telp=" + donatur_telp + "&filter_nominal=" + filter_nominal +
                    "&donatur_title=" + encodeURI(donatur_title)).load();
            }

            var table = $('#table-donatur').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [4, 'desc']
                ],
                ajax: "{{ route('adm.donate.datatables') }}/?donatur_id={{ $donatur->id }}&need_fu==" + need_fu +
                    "&day1=" + day1 + "&day5=" + day5,
                "columnDefs": [{
                        "width": "21%",
                        "targets": 0
                    },
                    {
                        "width": "14%",
                        "targets": 1
                    },
                    {
                        "width": "35%",
                        "targets": 2
                    },
                    {
                        "width": "16%",
                        "targets": 3
                    },
                    {
                        "width": "14%",
                        "targets": 4
                    },
                    {
                        "orderable": false,
                        "targets": 1
                    },
                    {
                        "orderable": false,
                        "targets": 2
                    },
                    {
                        "orderable": false,
                        "targets": 3
                    },
                ],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nominal_final',
                        name: 'nominal_final'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ]
            });

            $('#table-donatur thead tr').clone(true).appendTo('#table-donatur thead');
            $('#table-donatur tr:eq(1) th').each(function(i) {
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

            $("#refresh_table_donate").on("click", function() {
                table.ajax.reload();
            });


            var table_program = $('#table-donatur-program').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [4, 'desc']
                ],
                ajax: "{{ route('adm.donatur.program.datatables') }}/?donatur_id={{ $donatur->id }}&need_fu==" +
                    need_fu + "&day1=" + day1 + "&day5=" + day5,
                "columnDefs": [{
                        "width": "23%",
                        "targets": 0
                    },
                    {
                        "width": "15%",
                        "targets": 1
                    },
                    {
                        "width": "15%",
                        "targets": 2
                    },
                    {
                        "width": "35%",
                        "targets": 3
                    },
                    {
                        "width": "12%",
                        "targets": 4
                    },
                    {
                        "orderable": false,
                        "targets": 1
                    },
                    {
                        "orderable": false,
                        "targets": 2
                    },
                    {
                        "orderable": false,
                        "targets": 3
                    },
                ],
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'about',
                        name: 'about'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#table-donatur-program thead tr').clone(true).appendTo('#table-donatur-program thead');
            $('#table-donatur-program tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table_program.column(i).search() !== this.value) {
                        table_program
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            $("#refresh_table_donate").on("click", function() {
                table_program.ajax.reload();
            });


            var table_chat = $('#table-donatur-chat').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [4, 'desc']
                ],
                ajax: "{{ route('adm.donatur.chat.datatables') }}/?donatur_id={{ $donatur->id }}&need_fu==" +
                    need_fu + "&day1=" + day1 + "&day5=" + day5,
                "columnDefs": [{
                        "width": "15%",
                        "targets": 0
                    },
                    {
                        "width": "48%",
                        "targets": 1
                    },
                    {
                        "width": "15%",
                        "targets": 2
                    },
                    {
                        "width": "20%",
                        "targets": 3
                    },
                    {
                        "width": "12%",
                        "targets": 4
                    },
                    {
                        "orderable": false,
                        "targets": 1
                    },
                    {
                        "orderable": false,
                        "targets": 3
                    },
                ],
                columns: [{
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'text',
                        name: 'text'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'desc',
                        name: 'desc'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#table-donatur-chat thead tr').clone(true).appendTo('#table-donatur-chat thead');
            $('#table-donatur-chat tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table_chat.column(i).search() !== this.value) {
                        table_chat
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            $("#refresh_table_donate").on("click", function() {
                table_chat.ajax.reload();
            });

            $("#save_status").on("click", function() {
                var id_trans = $("#id_trans").val();
                var status = $('input[name="status"]:checked').val();
                var nominal = $('#rupiah').val();

                if (document.getElementById('checkboxwa').checked) {
                    var sendwa = 1;
                } else {
                    var sendwa = 0;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('adm.donate.status.edit') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id_trans": id_trans,
                        "sendwa": sendwa,
                        "status": status,
                        "nominal": nominal
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'success') {
                            // toast success
                            let status_id = '#status_' + id_trans;
                            if (status == 'draft') {
                                $(status_id).html(data.nominal +
                                    '<br><span class="badge badge-warning">BELUM DIBAYAR</span>');
                            } else if (status == 'success') {
                                $(status_id).html(data.nominal +
                                    '<br><span class="badge badge-success">SUDAH DIBAYAR</span>');
                            } else {
                                $(status_id).html(data.nominal +
                                    '<br><span class="badge badge-secondary">DIBATALKAN</span>');
                            }

                            hideFunc('#modal_status');
                        }
                    }
                });
            });

            function fuPaid(id, name, nominal) {
                $("#modalTitleFu").html(name + " - " + nominal);
                $("#id_trans_fu").val(id);

                let myModal = new bootstrap.Modal(document.getElementById('modal_fu'));
                myModal.show();
            }

            $("#save_fu").on("click", function() {
                var id_trans = $("#id_trans_fu").val();
                var status = $('input[name="fu_name"]:checked').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('adm.donate.fu.paid') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id_trans": id_trans,
                        "status": status
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 'success') {
                            hideFunc('#modal_fu');
                            // toast success
                            alert("Sudah dikirim");
                        }
                    }
                });
            });

            $('#playButton').on('click', () => {
                new Audio("{{ asset('public/audio/1.mp3') }}").play();
                document.querySelector('#playButton').innerHTML = '<i class="fa fa-volume-up mr-1"></i> ON';
            });

            $("#checkgenap").on("click", function() {
                let val_rupiah = $('#rupiah').val();
                val_rupiah = val_rupiah.slice(0, -3) + '000';
                $('#rupiah').val(val_rupiah);
                $('#rupiah').val(formatRupiah(document.getElementById("rupiah").value, ""));
            });

            var rupiah = document.getElementById("rupiah");
            rupiah.addEventListener("keyup", function(e) {
                // tambahkan 'Rp.' pada saat form di ketik
                // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
                rupiah.value = formatRupiah(this.value, "");
            });

            /* Fungsi formatRupiah */
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
            }
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("#program-select2").select2({
                    dropdownParent: $('#modal_donatur_loyal'),
                    placeholder: 'Cari Program',
                    theme: 'bootstrap-5',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('adm.program.select2.all') }}",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }

                            // Query parameters will be ?search=[term]&type=public
                            return query;
                        },
                        processResults: function(data, params) {
                            var items = $.map(data.data, function(obj) {
                                let program_name = obj.title;
                                obj.id = obj.id;
                                obj.text = `${program_name}`;

                                return obj;
                            });
                            params.page = params.page || 1;

                            // console.log(items);
                            // Transforms the top-level key of the response object from 'items' to 'results'
                            return {
                                results: items,
                                pagination: {
                                    more: params.page < data.last_page
                                }
                            };
                        },
                    },
                    templateResult: function(item) {
                        // console.log(item);
                        // No need to template the searching text
                        if (item.loading) {
                            return item.text;
                        }

                        var term = select2_query.term || '';
                        // var $result = markMatch(item.text, term);
                        var $result = item.text,
                            term;

                        return $result;
                    },
                    language: {
                        searching: function(params) {
                            // Intercept the query as it is happening
                            select2_query = params;

                            // Change this to be appropriate for your application
                            return 'Searching...';
                        }
                    }
                });

                $("#payment_type-select2").select2({
                    dropdownParent: $('#modal_donatur_loyal'),
                    placeholder: 'Cari Metode Pembayaran',
                    theme: 'bootstrap-5',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('adm.payment-type.select2.all') }}",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }

                            return query;
                        },
                        processResults: function(data, params) {
                            var items = $.map(data.data, function(obj) {
                                let payment_type_name = obj.payment_code ? '#' + obj.id + ' ' + obj.name + ' (' + obj.payment_code + ')' : '#' + obj.id + ' ' + obj.name;
                                obj.id = obj.id;
                                obj.text = payment_type_name;

                                return obj;
                            });
                            params.page = params.page || 1;

                            // console.log(items);
                            // Transforms the top-level key of the response object from 'items' to 'results'
                            return {
                                results: items,
                                pagination: {
                                    more: params.page < data.last_page
                                }
                            };
                        },
                    },
                    templateResult: function(item) {
                        if (item.loading) {
                            return item.text;
                        }

                        var term = select2_query.term || '';
                        var $result = item.text,
                            term;

                        return $result;
                    },
                    language: {
                        searching: function(params) {
                            select2_query = params;
                            return 'Searching...';
                        }
                    }
                });

                $("#edit_payment_type-select2").select2({
                    dropdownParent: $('#edit_modal_donatur_loyal'),
                    placeholder: 'Cari Metode Pembayaran',
                    theme: 'bootstrap-5',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('adm.payment-type.select2.all') }}",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }

                            return query;
                        },
                        processResults: function(data, params) {
                            var items = $.map(data.data, function(obj) {
                                let payment_type_name = obj.payment_code ? '#' + obj.id + ' ' + obj.name + ' (' + obj.payment_code + ')' : '#' + obj.id + ' ' + obj.name;
                                obj.id = obj.id;
                                obj.text = payment_type_name;

                                return obj;
                            });
                            params.page = params.page || 1;

                            return {
                                results: items,
                                pagination: {
                                    more: params.page < data.last_page
                                }
                            };
                        },
                    },
                    templateResult: function(item) {
                        if (item.loading) {
                            return item.text;
                        }

                        var term = select2_query.term || '';
                        var $result = item.text,
                            term;

                        return $result;
                    },
                    language: {
                        searching: function(params) {
                            select2_query = params;
                            return 'Searching...';
                        }
                    }
                });

                $("#edit_program-select2").select2({
                    dropdownParent: $('#edit_modal_donatur_loyal'),
                    placeholder: 'Cari Program',
                    theme: 'bootstrap-5',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('adm.program.select2.all') }}",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }

                            // Query parameters will be ?search=[term]&type=public
                            return query;
                        },
                        processResults: function(data, params) {
                            var items = $.map(data.data, function(obj) {
                                let program_name = obj.title;
                                obj.id = obj.id;
                                obj.text = `${program_name}`;

                                return obj;
                            });
                            params.page = params.page || 1;

                            // console.log(items);
                            // Transforms the top-level key of the response object from 'items' to 'results'
                            return {
                                results: items,
                                pagination: {
                                    more: params.page < data.last_page
                                }
                            };
                        },
                    },
                    templateResult: function(item) {
                        // console.log(item);
                        // No need to template the searching text
                        if (item.loading) {
                            return item.text;
                        }

                        var term = select2_query.term || '';
                        // var $result = markMatch(item.text, term);
                        var $result = item.text,
                            term;

                        return $result;
                    },
                    language: {
                        searching: function(params) {
                            // Intercept the query as it is happening
                            select2_query = params;

                            // Change this to be appropriate for your application
                            return 'Searching...';
                        }
                    }
                });
            });

            $(document).ready(function() {
                // Pastikan elemen toast ada di DOM
                if ($('#toastNotification').length === 0) {
                    $('body').append(`
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="toastNotification" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Notification</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body"></div>
                </div>
            </div>
        `);
                }

                let deleteId;
                const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

                // Tangani klik tombol delete
                $(document).on('click', '.delete', function() {
                    deleteId = $(this).data('id');
                    deleteModal.show();
                });

                // Inisialisasi toast
                const toastElement = $('#toastNotification')[0];
                const toast = toastElement ? new bootstrap.Toast(toastElement) : null;

                // Tangani submit form
                $('#donaturLoyalForm').on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const submitButton = form.find('button[type="submit"]');
                    const originalButtonText = submitButton.html();
                    const actionUrl = form.attr('action');

                    // Validasi URL action
                    if (!actionUrl) {
                        console.error('Form action URL is missing');
                        return;
                    }

                    // Tampilkan loading state
                    submitButton.prop('disabled', true);
                    submitButton.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                    );

                    // Kirim data ke server menggunakan AJAX jQuery
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(data) {
                            if (data.success) {
                                // Tampilkan pesan sukses
                                showToast('success', 'Berhasil', data.message);

                                // Tutup modal
                                $('#modal_donatur_loyal').modal('hide');

                                form[0].reset();
                                form.find('.is-invalid').removeClass('is-invalid');
                                form.find('.invalid-feedback').remove();

                                donaturLoyalTable.ajax.reload();
                            } else {
                                showToast('error', 'Gagal', data.message || 'Terjadi kesalahan');
                            }
                        },
                        error: function(xhr) {
                            // Handle error response
                            if (xhr.status === 405) {
                                showToast('error', 'Method Not Allowed',
                                    'Metode request tidak diizinkan oleh server');
                            } else if (xhr.responseJSON) {
                                const errData = xhr.responseJSON;
                                if (errData.errors) {
                                    showValidationErrors(errData.errors);
                                } else {
                                    showToast('error', 'Gagal', errData.message ||
                                        'Terjadi kesalahan');
                                }
                            } else {
                                showToast('error', 'Gagal', 'Terjadi kesalahan jaringan');
                                console.error('Error:', xhr);
                            }
                        },
                        complete: function() {
                            // Kembalikan button ke state semula
                            submitButton.prop('disabled', false);
                            submitButton.html(originalButtonText);
                        }
                    });
                });

                // Fungsi untuk menampilkan toast notifikasi
                function showToast(type, title, message) {
                    if (!toast) {
                        console.error('Toast element not initialized');
                        return;
                    }

                    const toastHeader = $('#toastNotification .toast-header');
                    const toastBody = $('#toastNotification .toast-body');

                    // Set warna berdasarkan type
                    toastHeader.removeClass('bg-success bg-danger text-white');
                    if (type === 'success') {
                        toastHeader.addClass('bg-success text-white');
                    } else {
                        toastHeader.addClass('bg-danger text-white');
                    }

                    // Set konten
                    $('#toastNotification .me-auto').text(title);
                    toastBody.text(message);

                    // Tampilkan toast
                    toast.show();
                }

                // Fungsi untuk menampilkan error validasi
                function showValidationErrors(errors) {
                    // Reset error sebelumnya
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Tampilkan error baru
                    $.each(errors, function(field, messages) {
                        const input = $('[name="' + field + '"]');
                        if (input.length) {
                            input.addClass('is-invalid');

                            // Tambahkan pesan error
                            input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                        }
                    });

                    showToast('error', 'Validasi Gagal', 'Terdapat kesalahan pada input form');
                }

                var donaturLoyalTable = $('#table-donatur-loyal').DataTable({
                    orderCellsTop: true,
                    fixedHeader: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    order: [
                        [0, 'desc'] // Default ordering by first column (ID)
                    ],
                    ajax: "{{ route('adm.donatur-loyal.datatables') }}/?donatur_id={{ $donatur->id }}",
                    "columnDefs": [{
                            "width": "20%", "targets": 0
                        },
                        {
                            "width": "15%", "targets": 1
                        },
                        {
                            "width": "15%", "targets": 2
                        },
                        {
                            "width": "20%", "targets": 3
                        },
                        {
                            "width": "10%", "targets": 4, "orderable": false
                        },
                        {
                            "width": "10%", "targets": 5, "orderable": false
                        },
                        {
                            "width": "10%", "targets": 6, "orderable": false
                        }
                    ],
                    columns: [{
                            data: 'schedule',
                            name: 'schedule'
                        },
                        {
                            data: 'nominal',
                            name: 'nominal'
                        },
                        {
                            data: 'program',
                            name: 'program.title'
                        },
                        {
                            data: 'desc',
                            name: 'desc'
                        },
                        {
                            data: 'payment',
                            name: 'payment'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#confirmDeleteButton').click(function() {
                    deleteModal.hide();

                    // Tampilkan loading
                    $(this).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...'
                        )
                        .prop('disabled', true);

                    // Kirim request delete
                    $.ajax({
                        url: "{{ route('adm.donatur-loyal.destroy', '') }}/" + deleteId,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                showToast('success', 'Berhasil', response.message);

                                // Refresh datatable
                                donaturLoyalTable.ajax.reload();
                            } else {
                                showToast('error', 'Gagal', response.message);
                            }
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            showToast('error', 'Gagal', message);
                        },
                        complete: function() {
                            $('#confirmDeleteButton').html('Hapus').prop('disabled', false);
                        }
                    });
                });
            });
        </script>

        {{-- form function script --}}
        <script>
            function handlePeriodeChange(val, isEditModal = false) {
                const prefix = isEditModal ? 'edit_' : '';
                const targetId = isEditModal ? 'edit_periode_fields' : 'periode_fields';

                let html = '';
                if (val === 'daily') {
                    html = `
            <div class="mb-3">
                <label for="${prefix}daily_time" class="form-label">Jam</label>
                <input type="time" class="form-control" id="${prefix}daily_time" name="daily_time">
            </div>
        `;
                } else if (val === 'weekly') {
                    html = `
            <div class="mb-3">
                <label for="${prefix}weekly_time" class="form-label">Jam</label>
                <input type="time" class="form-control" id="${prefix}weekly_time" name="weekly_time">
            </div>
            <div class="mb-3">
                <label for="${prefix}weekly_day" class="form-label">Hari</label>
                <select class="form-select" id="${prefix}weekly_day" name="weekly_day">
                    <option value="senin">Senin</option>
                    <option value="selasa">Selasa</option>
                    <option value="rabu">Rabu</option>
                    <option value="kamis">Kamis</option>
                    <option value="jumat">Jumat</option>
                    <option value="sabtu">Sabtu</option>
                    <option value="minggu">Minggu</option>
                </select>
            </div>
        `;
                } else if (val === 'monthly') {
                    html = `
            <div class="mb-3">
                <label for="${prefix}monthly_date" class="form-label">Tanggal (1-31)</label>
                <input type="number" min="1" max="31" class="form-control" id="${prefix}monthly_date" name="monthly_date">
            </div>
        `;
                } else if (val === 'yearly') {
                    html = `
            <div class="mb-3">
                <label for="${prefix}_yearly_month" class="form-label">Bulan</label>
                <select class="form-select" id="${prefix}yearly_month" name="yearly_month">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
        `;
                }

                document.getElementById(targetId).innerHTML = html;
            }
            document.addEventListener('DOMContentLoaded', function() {
                handlePeriodeChange(document.getElementById('donasi_periode').value);
            });
            document.getElementById('status_donasi_tetap').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('_status').textContent = 'Aktif';
                    document.getElementById('status_donasi_tetap_hidden').value = '1';
                } else {
                    document.getElementById('_status').textContent = 'Tidak Aktif';
                    document.getElementById('status_donasi_tetap_hidden').value = '0';
                }
            });
            document.getElementById('edit_status_donasi_tetap').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('_edit_status').textContent = 'Aktif';
                    document.getElementById('edit_status_donasi_tetap_hidden').value = '1';
                } else {
                    document.getElementById('_edit_status').textContent = 'Tidak Aktif';
                    document.getElementById('edit_status_donasi_tetap_hidden').value = '0';
                }
            });
        </script>
    @endsection
