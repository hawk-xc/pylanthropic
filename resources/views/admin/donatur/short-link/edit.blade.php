@extends('layouts.admin', [
    'second_title' => 'Tautan Pendek Donasi Donatur',
    'header_title' => 'Tautan Pendek Donasi Donatur',
    'sidebar_menu' => 'person',
    'sidebar_submenu' => 'donatur',
])

@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        #result-container {
            transition: all 0.3s ease;
        }

        #copy-button {
            transition: all 0.2s ease;
        }

        #copy-button:hover {
            background-color: #835bc8;
        }

        #short-url {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.donatur.index') }}">Donatur</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tautan Pendek Donasi</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $donatur_short_link->donatur->name }}</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Tautan Pendek</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a href="{{ route('adm.donatur.shorten-link.index', $donatur_short_link->donatur->id) }}" class="btn btn-outline-dark"> kembali</a>
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

            <form action="{{ route('adm.donatur.shorten-link.update', $donatur_short_link->id) }}" method="post"
                accept-charset="utf-8" class="row gy-3">
                @csrf
                <input type="text" name="donatur_id" class="d-none" value="{{ $donatur_short_link->donatur->id }}"/>
                {{-- url tujuan --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Tampilan URL/Link Awal</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">
                            https://bantubersama.com/<span id="program-slug">{{ $donatur_short_link->program->slug }}</span><span id="nominal">{{ $donatur_short_link->amount ? '/checkout/' . $donatur_short_link->amount . '/' : '' }}</span><span id="payment_type_url">{{ $donatur_short_link->payment_type }}</span>?name={{ $donatur_short_link->donatur->name }}&telp={{ $donatur_short_link->donatur->telp }}</span>
                        <input disabled type="text" class="form-control form-control-sm" name="direct_link" id="direct_link"
                            name="direct_link" value="{{ old('direct_link') }}" placeholder="">
                    </div>
                </div>
                <input type="hidden" name="donatur_id" value="{{ $donatur_short_link->donatur->id }}">
                
                {{-- url asal --}}
                <div class="col-12">
                    <label class="form-label required fw-semibold">Nama Tautan {!! printRequired() !!}</label>
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Nama URL/Link" value="{{ old('name', $donatur_short_link->name) }}"
                        required>
                    @error('name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold required">Pilih Program {!! printRequired() !!}</label>
                    <select class="form-control form-control-sm" name="program" id="program-select2" required>
                        @if ($donatur_short_link->program_id)
                            <option value="{{ $donatur_short_link->program_id }}" selected>
                                {{ $donatur_short_link->program->title }}
                            </option>
                        @endif
                    </select>
                    @error('program')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="donasi_nominal" class="form-label">Nominal {!! printRequired() !!}</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">RP</span>
                        </div>
                        <input class="form-control form-control-sm" id="rupiah" name="amount"
                            placeholder="0" type="text" value="{{ old('amount', $donatur_short_link->amount) }}" required />
                    </div>
                    @error('amount')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold required">Pilih Metode Pembayaran {!! printRequired() !!}</label>
                    <select name="payment_type" id="payment_type" class="form-control form-control-sm" required>
                        @forelse($payment_types as $payment_type)
                            <option value="{{ $payment_type->key }}" {{ old('payment_type', $donatur_short_link->payment_type) == $payment_type->key ? 'selected' : '' }}>{{ $payment_type->name }}</option>
                        @empty
                            <span>
                                <option value="not_found">data kosong!</option>
                            </span>
                        @endforelse
                    </select>
                    @error('payment_type')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- keterangan --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Keterangan (opsional)</label>
                    <textarea class="form-control form-control-sm" name="description" row="6" placeholder="Keterangan/Deskripsi">{{ $donatur_short_link->description }}</textarea>
                </div>

                {{-- Status --}}
                <div class="col-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $donatur_short_link->is_active) === 1 ? 'checked' : '' }}>
                        <label class="form-check-label mt-1" for="is_active">
                            Aktif
                        </label>
                        @error('is_active')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="divider mb-2 mt-2"></div>
                </div>
                <div class="col-12 text-end" id="action-button">
                    <input type="reset" class="btn btn-danger" value="Reset">
                    <input type="submit" class="btn btn-info" value="Submit">
                </div>
            </form>
        </div>
        {{-- result box --}}
        <div id="result-container" class="mt-4" style="display: none;">
            <div class="card border-success">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="short-url" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="copy-button">
                                    <i class="metismenu-icon pe-7s-copy-file"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('adm.shorten-link.index') }}" class="btn btn-primary w-100">
                                <i class="fas fa-list"></i> Lihat semua Tautan Pendek
                            </a>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <strong>Original URL:</strong> <span id="original-url"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('js_inline')
    <script type="text/javascript">
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

        $(document).ready(function() {
            setTimeout(function() {
                $('#success-alert').fadeOut('slow');
                $('#error-alert').fadeOut('slow');
            }, 5000);
        });

        $(document).ready(function() {
            // Handle alert timeout
            setTimeout(function() {
                $('#success-alert').fadeOut('slow');
                $('#error-alert').fadeOut('slow');
            }, 5000);

            // Handle form submission
            $('form').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var submitButton = form.find('[type="submit"]');
                var originalButtonText = submitButton.val();

                // Show loading state
                submitButton.prop('disabled', true);
                submitButton.val('Processing...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show result container
                            $('#result-container').show();
                            $('#short-url').val(response.short_url);
                            $('#original-url').text('https://bantubersama.com/' + response.data
                                .direct_link);
                            $('#action-button').hide();

                            // Reset form
                            form[0].reset();
                        } else {
                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Error',
                            //     text: response.message
                            // });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    },
                    complete: function() {
                        // Reset button state
                        submitButton.prop('disabled', false);
                        submitButton.val(originalButtonText);
                    }
                });
            });

            // Handle copy button
            $(document).on('click', '#copy-button', function() {
                var shortUrlInput = $('#short-url');
                shortUrlInput.select();
                document.execCommand('copy');

                // Change button text temporarily
                var copyButton = $(this);
                var originalHtml = copyButton.html();
                copyButton.html('<i class="fas fa-check"></i> Copied!');

                // Reset button after 2 seconds
                setTimeout(function() {
                    copyButton.html(originalHtml);
                }, 2000);

                // Show tooltip
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'URL copied to clipboard',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });

        $("#program-select2").select2({
            placeholder: 'Cari Program',
            theme: 'bootstrap-5',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.program.select2.all') }}",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                },
                processResults: function (data, params) {
                    var items = $.map(data.data, function(obj){
                        let program_name = obj.title;
                        obj.id = obj.id;
                        obj.text = `${program_name}`;
                        // Store the slug in the option's data for later use
                        obj.slug = obj.slug;
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
            templateResult: function(data) {
                // Optional: Customize how options appear in the dropdown
                return data.text;
            }
        }).on('select2:select', function (e) {
            // When an option is selected, update the slug display
            var selectedData = e.params.data;
            $('#program-slug').text(selectedData.slug + '/');
        }).on('select2:clear', function (e) {
            // When selection is cleared, empty the slug display
            $('#program-slug').text('');
        });

        $('#rupiah').on('keyup', function(e) {
            $('#nominal').text('checkout/' + parseInt($(this).val().replace(/\./g, ''), 10) + '/');
            $(this).val(formatRupiah($(this).val(), ""));
        });

        $('#payment_type').on('change', function(e) {
            $('#payment_type_url').text($(this).val());
        });
    </script>
@endsection
