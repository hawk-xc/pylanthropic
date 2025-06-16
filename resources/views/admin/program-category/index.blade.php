@extends('layouts.admin', [
    'second_title' => 'Kategori Program',
    'header_title' => 'List Kategori',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'category',
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
                            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-10 fc-rtl">
                    {{-- <button class="btn btn-primary filter_program" id="filter-active" data-id="active"
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
                        data-val="0">Terbaru</button> --}}
                    <!-- <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button> -->
                    <a href="{{ route('adm.program-category.create') }}" class="btn btn-outline-primary"><i
                            class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-program-category" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Ditampilkan</th>
                        <th>Total Program</th>
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
    <div class="modal fade" id="modal_show_donate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <div class="modal fade" id="modal_show_summary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="datetime-local" name="date" id="date_time" class="form-control form-control-sm"
                                value="{{ date('Y-m-d H:i') }}">
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
        $(".filter_program").on("click", function() {
            var fil_name = $(this).attr("data-id");
            var fil_val = $(this).attr("data-val");

            if (fil_name == 'active') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else if (fil_name == 'inactive') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else if (fil_name == 'winning') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else if (fil_name == 'publish15day') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else if (fil_name == 'end15day') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else if (fil_name == 'recom') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else if (fil_name == 'urgent') {
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            } else { // newest
                if (fil_val == 0) {
                    $(this).removeClass('btn-outline-primary');
                    $(this).addClass('btn-primary');
                    $(this).attr("data-val", "1");
                    program_table();
                } else {
                    $(this).addClass('btn-outline-primary');
                    $(this).removeClass('btn-primary');
                    $(this).attr("data-val", "0");
                    program_table();
                }
            }
        });

        function program_table() {
            let active = $('#filter-active').attr("data-val");
            let inactive = $('#filter-inactive').attr("data-val");
            let winning = $('#filter-winning').attr("data-val");
            let publish15day = $('#filter-publish15day').attr("data-val");
            let end15day = $('#filter-end15day').attr("data-val");
            let recom = $('#filter-recom').attr("data-val");
            let urgent = $('#filter-urgent').attr("data-val");
            let newest = $('#filter-newest').attr("data-val");

            table.ajax.url("{{ route('adm.program.datatables') }}/?active=" + active + "&inactive=" + inactive +
                "&winning=" + winning + "&publish15day=" + publish15day + "&end15day=" + end15day + "&recom=" + recom +
                "&urgent=" + urgent + "&newest=" + newest).load();
        }

        var table = $('#table-program-category').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            ajax: {
                url: "{{ route('adm.program-category.datatables') }}",
                data: function(d) {
                    d.is_show = $('#filter-is-show').val(); // Jika ada filter tambahan
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'is_show',
                    name: 'is_show'
                },
                {
                    data: 'program_count',
                    name: 'programs_count'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            columnDefs: [{
                    width: "30%",
                    targets: 0
                },
                {
                    width: "15%",
                    targets: 1
                },
                {
                    width: "15%",
                    targets: 2
                },
                {
                    width: "10%",
                    targets: 3
                }
            ]
        });

        // Jika ada filter tambahan
        $('#filter-is-show').change(function() {
            table.ajax.reload();
        });

        function showDonate(id, title) {
            $("#modalTitle").html(title);

            $.ajax({
                type: "GET",
                url: "{{ route('adm.program.show.donate') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    $("#modalBody").html(data);
                }
            });

            let myModal = new bootstrap.Modal(document.getElementById('modal_show_donate'));
            myModal.show();
        }

        function showSummary(id, title) {
            $("#modalTitleSummary").html(title);

            $.ajax({
                type: "GET",
                url: "{{ route('adm.program.show.summary') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    $("#modalBodySummary").html(data);
                }
            });

            let myModal = new bootstrap.Modal(document.getElementById('modal_show_summary'));
            myModal.show();
        }

        function hideFunc(name) {
            const truck_modal = document.querySelector(name);
            const modal = bootstrap.Modal.getInstance(truck_modal);
            modal.hide();
        }

        $("#submit_spend").on("click", function() {
            var id_program = $("#id_program").val();
            var title = $("#title").val();
            var date_time = $("#date_time").val();
            var nominal = $('#rupiah').val();

            $.ajax({
                type: "POST",
                url: "{{ route('adm.program.spend.submit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id_program": id_program,
                    "title": title,
                    "date_time": date_time,
                    "nominal": nominal
                },
                success: function(data) {
                    console.log(data);
                    if (data == 'success') {
                        table.ajax.reload();
                        hideFunc('#modal_inp_spend');
                        // toast success
                        alert("Berhasil Disimpan");
                    }
                }
            });
        });

        function inpSpend(id, title) {
            $("#modalTitleSpend").html(title);
            $("#id_program").val(id);

            table_spent.ajax.url("{{ route('adm.program.spend.show') . '/?id=' }}" + id).load();

            let myModal = new bootstrap.Modal(document.getElementById('modal_inp_spend'));
            myModal.show();
        }

        var table_spent = $('#table-spent').DataTable({
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

        $("#check11percent").on("click", function() {
            var val_rupiah = $('#rupiah').val();
            val_rupiah = val_rupiah.replaceAll(".", "");
            val_rupiah = Number(val_rupiah);
            var rupiah_11 = Math.ceil(val_rupiah * 11 / 100);
            console.log(val_rupiah);

            if ($('#check11percent').is(':checked')) {
                console.log(rupiah_11);
                $('#rupiah').val(val_rupiah + rupiah_11);
                let rupiah_fix = formatRupiah(document.getElementById("rupiah").value, "");
                $('#rupiah').val(rupiah_fix);
            } else {
                $('#rupiah').val(val_rupiah - rupiah_11);
                let rupiah_fix = formatRupiah(document.getElementById("rupiah").value, "");
                $('#rupiah').val(rupiah_fix);
            }
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

    <script>
        $(document).ready(function() {
            // Cek apakah elemen yang diperlukan ada di halaman ini
            if ($('#same_as_thumbnail').length && $('input[name="thumbnail"]').length) {
                // Fungsi toggle thumbnail
                function toggleThumbnailInput() {
                    const thumbnailCol = $('input[name="thumbnail"]').closest('.col-6');
                    if ($('#same_as_thumbnail').is(':checked')) {
                        thumbnailCol.hide();
                        $('input[name="thumbnail"]').removeAttr('required');
                    } else {
                        thumbnailCol.show();
                        $('input[name="thumbnail"]').attr('required', 'required');
                    }
                }

                // Inisialisasi awal
                toggleThumbnailInput();

                // Event listener
                $('#same_as_thumbnail').on('change', toggleThumbnailInput);

                console.log('Thumbnail toggle script initialized');
            }
        });

        // SweetAlert notifications
        // SweetAlert notifications dengan posisi kanan bawah dan auto close
        @if (session('success'))
            Swal.fire({
                title: 'Aksi: Berhasil',
                icon: 'success',
                text: "{{ session('success') }}",
                position: 'bottom-end', // Posisi kanan bawah
                showConfirmButton: false, // Sembunyikan tombol OK
                timer: 7000, // Auto close setelah 3 detik (3000ms)
                timerProgressBar: true, // Tampilkan progress bar
                toast: true, // Tampilan seperti toast notification
                background: '#f8f9fa', // Warna background
                backdrop: false // Nonaktifkan backdrop
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
                timer: 5000, // Error lebih lama (5 detik)
                timerProgressBar: true,
                toast: true,
                background: '#f8f9fa',
                backdrop: false
            });
        @endif
    </script>
@endsection
