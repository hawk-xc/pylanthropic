@extends('layouts.admin', [
    'second_title' => 'Kategori Program',
    'header_title' => 'Tambah Kategori',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'category',
])

@section('css_plugins')
@endsection


@section('css_inline')
    <style type="text/css">
        .required:after {
            content: "*";
            color: red;
        }

        .required:after {
            content: "*";
            color: red;
        }

        .sort-box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }

        .sort-box {
            width: 50px;
            height: 50px;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            background-color: white;
        }

        .sort-box:hover {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .sort-box.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .sort-box.disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            border-color: #ced4da;
        }

        .sort-box.disabled:hover {
            box-shadow: none;
            border-color: #ced4da;
        }

        .load-more-btn {
            margin-top: 15px;
        }

        #sort-value {
            font-weight: bold;
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
                                    href="{{ route('adm.program-category.index') }}">Kategori</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Kategori</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="divider"></div>

            @if (session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger" id="error-alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('adm.program-category.store') }}" method="post" enctype="multipart/form-data"
                accept-charset="utf-8" class="row gy-3">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Kategori (max 50 karakter) {!! printRequired() !!} - <span
                            id="count_title" class="fw-normal"></span></label>
                    <input type="text" class="form-control form-control-sm" name="title" id="program_title"
                        placeholder="Masukkan Nama Kategori" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">URL Kategori (<span class="" id="status_url">Belum
                            Dicek</span>) {!! printRequired() !!}</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><input type="checkbox" class="mr-2" id="edit_url"> Edit</span>
                        <span class="input-group-text">{{ url('/') . '/programs?kategori=' }}</span>
                        <input type="text" class="form-control" name="url" placeholder="dermawan" id="url"
                            value="{{ old('url') }}" readonly>
                        <span class="input-group-text p-0"><button class="btn btn-sm btn-info" id="cek_url"
                                type="button">Cek & Lanjut</button></span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Urutan Kategori {!! printRequired() !!}</label>
                    <p>Pilih posisi urutan untuk kategori ini. Posisi yang sudah terisi ditandai dengan warna abu-abu.</p>

                    <input type="hidden" name="sort_number" id="sort-input" value="{{ old('sort_number', '') }}" required>
                    <p>Posisi terpilih: <span id="sort-value">Belum dipilih</span></p>

                    <div class="sort-box-container" id="sort-boxes">
                        {{-- sort box --}}
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary load-more-btn" id="load-more">
                        Tampilkan lebih banyak
                    </button>

                    @error('sort_number')
                        <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 p-3">
                    <div class="d-flex flex-row border p-3 rounded">
                        <div id="inputFileContainer" class="col-12">
                            <label class="form-label fw-semibold">Logo Kategori {!! printRequired() !!}</label>
                            <input type="file" class="form-control form-control-sm" name="logo_image" id="imageUpload">
                            @error('logo_image')
                                <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 p-3 rounded col-12" style="background-color: rgb(224, 243, 255);">
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input input-lg mb-2" type="checkbox" name="is_show" id="is_show">
                            <label class="form-check-label fw-bold" for="is_show">
                                Tampilkan Kategori
                            </label>
                        </div>
                        <small class="text-dark">
                            Kategori ini akan ditampilkan di halaman utama bantubersama.com.
                        </small>
                    </div>

                    <div class="col-12">
                        <div class="divider mb-2 mt-2"></div>
                    </div>
                    <div class="col-12 text-end">
                        <input type="reset" class="btn btn-danger" value="Reset">
                        <input type="submit" class="btn btn-info" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
@endsection

@section('js_inline')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-alert').fadeOut('slow');
                $('#error-alert').fadeOut('slow');
            }, 5000);

            $('#imageUpload').on('change', function(e) {
                const file = e.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Cek apakah imageContainer sudah ada
                        if ($('#imageContainer').length === 0) {
                            // Buat dan sisipkan imageContainer sebelum inputFileContainer
                            const imageContainer = `
                        <div id="imageContainer" class="col-2 d-flex justify-content-center align-items-center">
                            <img src="" alt="" class="img-fluid" />
                        </div>
                    `;
                            $('#inputFileContainer').removeClass('col-12').addClass(
                                'col-10');
                            $('#inputFileContainer').before(imageContainer);
                        }

                        // Set gambar yang dipilih ke <img>
                        $('#imageContainer img').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert('Silakan pilih file gambar yang valid.');
                }
            });
        });

        $("#program_title").on("keyup change", function() {
            var title = $(this).val();
            var title = title.length;
            if (title > 50) {
                $("#count_title").html(title + ' / 50');
                $("#count_title").addClass('text-danger');
            } else {
                $("#count_title").html(title + ' / 50');
                $("#count_title").removeClass('text-warning');
            }
        });

        $("#program_title").on("blur", function() {
            var title = $(this).val();
            var title = title.toLowerCase();
            var title = title.replace(/[^a-zA-Z0-9 ]/g, '');
            var title = title.replace(/ /g, "-");
            var title = title.replace(/--/g, "-");
            var title = encodeURI(title);
            var title = title.replace(/[^a-zA-Z0-9-]/g, '');
            $("#url").val(title);
        });

        $("#cek_url").on("click", function() {
            var url_data = $('#url').val();

            $.ajax({
                url: "{{ route('adm.program-category.create.check_url') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    "url": url_data
                },
                success: function(data) {
                    if (data == 'valid') {
                        console.log(data);
                        $('#status_url').html('Valid');
                        $('#status_url').removeClass('text-danger');
                        $('#status_url').addClass('text-success');
                    } else {
                        console.log(data);
                        $('#status_url').html('Sudah Dipakai');
                        $('#status_url').removeClass('text-success');
                        $('#status_url').addClass('text-danger');
                    }

                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
        });

        $("#edit_url").on("click", function() {
            if ($("#edit_url").is(':checked')) {
                document.getElementById('url').removeAttribute('readonly');
            } else {
                document.getElementById('url').readOnly = true;
            }
        });

        $(document).ready(function() {
            // Kode yang sudah ada tetap sama

            // Fungsi untuk mengelola kotak urutan
            let currentMax = 10;
            const occupiedPositions = @json($occupiedPositions ?? []); // Data dari controller

            function renderSortBoxes(max) {
                $('#sort-boxes').empty();
                for (let i = 1; i <= max; i++) {
                    const isOccupied = occupiedPositions.includes(i);
                    const isSelected = $('#sort-input').val() == i;

                    const box = $('<div>')
                        .addClass('sort-box')
                        .addClass(isOccupied ? 'disabled' : '')
                        .addClass(isSelected ? 'active' : '')
                        .text(i)
                        .attr('data-value', i);

                    if (!isOccupied) {
                        box.on('click', function() {
                            $('.sort-box').removeClass('active');
                            $(this).addClass('active');
                            $('#sort-input').val(i);
                            $('#sort-value').text(i);
                        });
                    }

                    $('#sort-boxes').append(box);
                }
            }

            // Inisialisasi kotak urutan
            renderSortBoxes(currentMax);

            // Tombol "Tampilkan lebih banyak"
            $('#load-more').on('click', function() {
                currentMax += 10;
                renderSortBoxes(currentMax);

                if (currentMax >= 50) { // Batas maksimal
                    $(this).prop('disabled', true);
                }
            });

            // Set nilai awal jika ada dari old input
            if ($('#sort-input').val()) {
                $('#sort-value').text($('#sort-input').val());
            }
        });
    </script>
@endsection
