@extends('layouts.admin', [
    'second_title' => 'Program',
    'header_title' => 'List Program',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'program',
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('css_inline')
    <style type="text/css">
        .btn-xs {
            padding: 3px !important;
            font-size: 13px !important;
        }

        .badge-square {
            width: 28px;
            /* sesuaikan ukuran */
            height: 28px;
            /* hilangkan padding default */
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-10 fc-rtl">
                    <button class="btn btn-primary filter_program" id="filter-active" data-id="active"
                        data-val="1">Aktif</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-inactive" data-id="inactive"
                        data-val="0">Non Aktif</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-winning" data-id="winning"
                        data-val="0">> 8jt</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-publish15day" data-id="publish15day"
                        data-val="0">Baru Publish 15</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-end15day" data-id="end15day"
                        data-val="0">Berakhir 15 Hari</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-recom" data-id="recom"
                        data-val="0">Rekom</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-urgent" data-id="urgent"
                        data-val="0">Mendesak</button>
                    <button class="btn btn-outline-primary filter_program" id="filter-newest" data-id="newest"
                        data-val="0">Terbaru</button>
                    <!-- <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button> -->
                    <a href="{{ route('adm.program.create') }}" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                    <button class="btn btn-outline-info" id="refresh-datatable"><i class="fa fa-sync"></i> Refresh
                        Data</button>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-12">
                    <div class="row gx-3 align-items-center">
                        <div class="col-auto">
                            <span class="fw-bold">Filter :</span>
                        </div>
                        <div class="col">
                            <input type="text" id="program_title" placeholder="Judul Program"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <input type="text" id="donation_target" placeholder="Target Donasi"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <input type="text" id="organization_name" placeholder="Nama Mitra"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <select class="form-select form-select-sm" id="filter_status">
                                <option value="">-- Pilih Status --</option>
                                <option value="recommended">Pilihan</option>
                                <option value="urgent">Mendesak</option>
                                <option value="newest">Terbaru</option>
                                <option value="search">Pencarian</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="row gx-3 align-items-center">
                        <div class="col-auto">
                            <span class="fw-bold">Urutkan :</span>
                        </div>
                        <div class="col">
                            <select class="form-select form-select-sm" id="filter_sort">
                                <option value="">-- Pilih --</option>
                                <option value="donate_total">Jumlah Donasi</option>
                                <option value="spend_sum">Jumlah Pengeluaran</option>
                                <option value="spend_ads_campaign">Jumlah Pengeluaran Campaign</option>
                                <option value="payout_sum">Jumlah Penyaluran</option>
                                <option value="approved_at">Tanggal Publish</option>
                                <option value="end_date">Tanggal Berakhir</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="dir" id="dir_asc"
                                    value="asc" checked>
                                <label class="form-check-label" for="dir_asc">Dari Terkecil</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="dir" id="dir_desc"
                                    value="desc">
                                <label class="form-check-label" for="dir_desc">Dari Terbesar</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-primary" id="filter_sort_btn">Urutkan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-program" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Lembaga</th>
                        <th>Ads Campaign</th>
                        <th>Spend</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('content_modal')
    <!-- Modal Show Stats -->
    <div class="modal fade" id="modal_show_donate" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pt-2 pb-2">
                    <h1 class="modal-title fs-5" id="modalTitle">Donate Report</h1>
                    <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-4" id="modalBody">

                </div>
                <div class="modal-footer pt-2 pb-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Show Summary -->
    <div class="modal fade" id="modal_show_summary" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pt-2 pb-2">
                    <h1 class="modal-title fs-5" id="modalTitleSummary">...</h1>
                    <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-4" id="modalBodySummary">

                </div>
                <div class="modal-footer pt-2 pb-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Input Spend Budget -->
    <div class="modal fade" id="modal_inp_spend" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pt-2 pb-2">
                    <h1 class="modal-title fs-5" id="modalTitleSpend">Donate Report</h1>
                    <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-4">
                    <div id="modalBodySpend"></div>
                    <div class="table-responsive mt-1 mb-2">
                        <table id="table-spent" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="row gy-1 mt-3">
                        <div class="col-12">
                            <hr>
                        </div>
                        <input type="hidden" id="id_program" name="id_program" value="">
                        <div class="col-12 text-start">
                            <span class="fs-5 fw-semibold">Form Input Spend Budget</span>
                        </div>
                        <div class="col-4">
                            <input type="text" name="title" id="title" class="form-control form-control-sm"
                                value="Iklan FB">
                        </div>
                        <div class="col-3">
                            <input type="datetime-local" name="date" id="date_time"
                                class="form-control form-control-sm" value="{{ date('Y-m-d H:i') }}">
                        </div>
                        <div class="col-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">RP</span>
                                <input class="form-control form-control-sm" id="rupiah" name="amount"
                                    placeholder="0" type="text" value="" />
                            </div>
                        </div>
                        <div class="col-2 text-start">
                            <div class="form-check big-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="check11percent">
                                <label class="form-check-label" for="check11percent">+ 11%</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-2 pb-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="submit_spend">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .swal2-toast {
            width: 350px !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15) !important;
        }

        .swal2-title {
            font-size: 1.1rem !important;
        }

        .swal2-timer-progress-bar {
            background: rgba(0, 0, 0, 0.2) !important;
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

    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tiny.cloud/1/cwr0gleaw96v89pa0jnes11yfy617v1ef0nl4akq5qdl1cdn/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        // ====== UTIL & STATE ======
        const FILTER_BTN = '.filter_program';
        const ACTIVE_CLASS = 'btn-primary';
        const INACTIVE_CLASS = 'btn-outline-primary';

        let SORT_FIELD = 'payout_sum'; // default sesuai kebutuhan
        let SORT_DIR = 'desc';

        // Toggle helper utk tombol boolean (aktif/inaktif/dll)
        function setBtnState($btn, on) {
            $btn.attr('data-val', on ? '1' : '0')
                .toggleClass(ACTIVE_CLASS, on)
                .toggleClass(INACTIVE_CLASS, !on);
        }

        function toggleBtn($btn) {
            setBtnState($btn, $btn.attr('data-val') !== '1');
        }

        function initButtons() {
            $(FILTER_BTN).each(function() {
                setBtnState($(this), $(this).attr('data-val') === '1');
            });
        }

        // Kumpulkan semua flag dari tombol 0/1
        function getFlagFilters() {
            const params = {};
            $(FILTER_BTN).each(function() {
                params[$(this).data('id')] = $(this).attr('data-val') || '0';
            });
            return params;
        }

        // Mapping dropdown status -> flag controller
        function applyStatusSelection() {
            const val = $('#filter_status').val(); // '', recommended, urgent, newest, search
            // reset tiga flag dulu ke '0' (tanpa ngubah tombol lain)
            const m = {
                recom: 'filter-recom',
                urgent: 'filter-urgent',
                newest: 'filter-newest'
            };
            Object.entries(m).forEach(([flag, id]) => setBtnState($('#' + id), false));

            if (val === 'recommended') setBtnState($('#filter-recom'), true);
            if (val === 'urgent') setBtnState($('#filter-urgent'), true);
            if (val === 'newest') setBtnState($('#filter-newest'), true);
            // 'search' -> biarkan default; biasanya active=1 sudah ON dari tombol atas
        }

        // ====== DATA TABLE ======
        const table = $('#table-program').DataTable({
            processing: true,
            searching: false,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            columnDefs: [{
                width: "22%",
                targets: 0
            }],
            order: [
                [4, 'desc']
            ], // visual default; sorting "sesungguhnya" via sort/dir param
            ajax: {
                url: "{{ route('adm.program.datatables') }}",
                data: function(d) {
                    // Ambil flag boolean
                    const flags = getFlagFilters();
                    // Ambil filter teks
                    const program_title = $('#program_title').val() || '';
                    const organization_name = $('#organization_name').val() || '';

                    Object.assign(d, flags, {
                        program_title,
                        organization_name,
                        sort: SORT_FIELD,
                        dir: SORT_DIR
                    });
                }
            },
            columns: [{
                    data: 'title',
                    name: 'program.title'
                },
                {
                    data: 'nominal',
                    name: 'nominal',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'organization',
                    name: 'organization.name'
                },
                {
                    data: 'donate',
                    name: 'donate_total'
                }, // sinkron alias
                {
                    data: 'stats',
                    name: 'stats',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // ====== FILTER TOGGLE BUTTONS ======
        $(document).on('click', FILTER_BTN, function() {
            toggleBtn($(this));
            table.ajax.reload();
        });

        $('#refresh-datatable').on('click', function(e) {
            e.preventDefault();
            table.ajax.url("{{ route('adm.program.datatables') }}?refresh=true").load();
        });

        // ====== FILTER TEKS & STATUS (Cari) ======
        $('#filter_search').on('click', function() {
            applyStatusSelection(); // set flag dari dropdown status
            table.ajax.reload();
        });

        // ====== URUTKAN (opsi #2) ======
        $('#filter_sort_btn').on('click', function() {
            const allowed = ['payout_sum', 'donate_total', 'spend_sum', 'spend_ads_campaign', 'approved_at',
                'end_date'
            ];
            const picked = $('#filter_sort').val();
            const dir = $('input[name="dir"]:checked').val();

            if (allowed.includes(picked)) {
                SORT_FIELD = picked;
                SORT_DIR = (dir === 'asc' ? 'asc' : 'desc');
                table.ajax.reload();
            } else {
                // kalau kosong, biarkan default
                SORT_FIELD = 'payout_sum';
                SORT_DIR = 'desc';
                table.ajax.reload();
            }
        });

        // ====== (Optional) Sinkron klik header hanya utk kolom yg diizinkan ======
        const DT_SORT_MAP = {
            4: 'donate_total', // kolom "Ads Campaign" (isi donasi total) -> allowed
            // Jangan map kolom 0/3 karena controller tidak whitelist title/organization
        };
        $('#table-program').on('order.dt', function() {
            const order = table.order();
            if (order && order.length) {
                const [colIdx, dir] = order[0];
                if (DT_SORT_MAP[colIdx]) {
                    SORT_FIELD = DT_SORT_MAP[colIdx];
                    SORT_DIR = (dir === 'asc' ? 'asc' : 'desc');
                    table.ajax.reload(null, false);
                }
            }
        });

        // ====== INIT ======
        $(function() {
            initButtons();
            // default sort
            SORT_FIELD = 'payout_sum';
            SORT_DIR = 'desc';
            table.ajax.reload();
        });

        // ====== MODALS & ACTIONS (tetap sama, hanya minor perapihan) ======
        function showDonate(id, title) {
            $("#modalTitle").text(title);
            $.get("{{ route('adm.program.show.donate') }}", {
                "_token": "{{ csrf_token() }}",
                id
            }, function(html) {
                $("#modalBody").html(html);
                new bootstrap.Modal(document.getElementById('modal_show_donate')).show();
            });
        }

        function showSummary(id, title) {
            $("#modalTitleSummary").text(title);
            $.get("{{ route('adm.program.show.summary') }}", {
                "_token": "{{ csrf_token() }}",
                id
            }, function(html) {
                $("#modalBodySummary").html(html);
                new bootstrap.Modal(document.getElementById('modal_show_summary')).show();
            });
        }

        function hideFunc(name) {
            const el = document.querySelector(name);
            const modal = bootstrap.Modal.getInstance(el);
            modal?.hide();
        }
        $("#submit_spend").on("click", function() {
            const payload = {
                "_token": "{{ csrf_token() }}",
                "id_program": $("#id_program").val(),
                "title": $("#title").val(),
                "date_time": $("#date_time").val(),
                "nominal": $('#rupiah').val()
            };
            $.post("{{ route('adm.program.spend.submit') }}", payload, function(res) {
                if (res === 'success') {
                    table.ajax.reload(null, false);
                    hideFunc('#modal_inp_spend');
                    alert("Berhasil Disimpan");
                }
            });
        });

        function inpSpend(id, title) {
            $("#modalTitleSpend").text(title);
            $("#id_program").val(id);
            table_spent.ajax.url("{{ route('adm.program.spend.show') . '/?id=' }}" + id).load();
            new bootstrap.Modal(document.getElementById('modal_inp_spend')).show();
        }

        const table_spent = $('#table-spent').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            order: [
                [0, 'desc']
            ],
            ajax: "{{ route('adm.program.spend.show') . '/?id=1' }}",
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'nominal',
                    name: 'nominal'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'desc',
                    name: 'desc'
                },
            ]
        });

        // ====== +11% ======
        $("#check11percent").on("click", function() {
            let val = $('#rupiah').val().replaceAll(".", "");
            val = Number(val) || 0;
            const eleven = Math.ceil(val * 11 / 100);
            const res = $(this).is(':checked') ? val + eleven : Math.max(val - eleven, 0);
            $('#rupiah').val(formatRupiah(String(res), ""));
        });

        // ====== Format Rupiah ======
        const rupiah = document.getElementById("rupiah");
        if (rupiah) {
            rupiah.addEventListener("keyup", function() {
                this.value = formatRupiah(this.value, "");
            });
        }

        function formatRupiah(angka, prefix) {
            const number_string = (angka || '').replace(/[^,\d]/g, "");
            const split = number_string.split(",");
            const sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                const separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }
            rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? "" + rupiah : "");
        }

        // ====== SweetAlert (tanpa perubahan fungsional) ======
        @if (session('success'))
            Swal.fire({
                title: 'Aksi: Berhasil',
                icon: 'success',
                text: "{{ session('success') }}",
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 7000,
                timerProgressBar: true,
                toast: true,
                background: '#f8f9fa',
                backdrop: false
            }).then(() => {
                if ($('#table-program').length) {
                    $('#table-program').DataTable().ajax.reload();
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Aksi: Gagal',
                icon: 'error',
                text: "{{ session('error') }}",
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                toast: true,
                background: '#f8f9fa',
                backdrop: false
            });
        @endif
    </script>
@endsection
