@extends('layouts.admin', [
    'second_title' => 'Tambah Tautan Pendek',
    'header_title' => 'Tambah Tautan Pendek',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'shorten_link',
])

@section('css_plugins')
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
                        <ol class="breadcrumb mb-0 pb-0 pl-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{ route('adm.shorten-link.index') }}">Tautan Pendek</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Tautan Pendek</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">

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

            <form action="{{ route('adm.shorten-link.store') }}" method="post" enctype="multipart/form-data"
                accept-charset="utf-8" class="row gy-3">
                @csrf
                {{-- url asal --}}
                <div class="col-12">
                    <label class="form-label required fw-semibold">Nama {!! printRequired() !!}</label>
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Nama URL/Link"
                        required>
                </div>

                {{-- url tujuan --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">URL/Link Tujuan {!! printRequired() !!}</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">
                            https://bantusesama.com/</span>
                        <input type="text" class="form-control form-control-sm" name="direct_link" id="direct_link"
                            name="direct_link" value="{{ old('direct_link') }}" placeholder="param=value&param2=value2"
                            required>
                        @error('direct_link')
                            <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- keterangan --}}
                <div class="col-12">
                    <label class="form-label fw-semibold required">Keterangan (opsional)</label>
                    <textarea class="form-control form-control-sm" name="description" row="6" placeholder="Keterangan/Deskripsi"></textarea>
                </div>

                {{-- Status --}}
                <div class="col-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                        <label class="form-check-label mt-1" for="is_active">
                            Aktif
                        </label>
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
@endsection


@section('js_inline')
    <script type="text/javascript">
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
    </script>
@endsection
