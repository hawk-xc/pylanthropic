@extends('layouts.admin', [
    'second_title' => 'Donatur',
    'header_title' => 'Edit Donatur',
    'sidebar_menu' => 'person',
    'sidebar_submenu' => 'donatur',
])


@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style type="text/css">
        .ck-editor__editable {
            min-height: 340px;
        }

        .fs-8 {
            font-size: 8px;
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
                                    href="{{ route('adm.program.index') }}">Donatur</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Donatur</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary" href={{ route('adm.donatur.index') }}>Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            <form action="{{ route('adm.donatur.update', $data->id) }}" method="post" accept-charset="utf-8"
                class="row gy-3">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Donatur {!! printRequired() !!}</label>
                    <input type="text" class="form-control form-control-sm" name="name" id="donatur_name" required
                        placeholder="Masukkan nama donatur" value="{{ old('name', $data->name) }}">
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">No Telp {!! printRequired() !!}</label>
                    <input type="text" class="form-control form-control-sm" name="telp" id="donatur_phone" required
                        placeholder="Masukkan nomor telepon" value="{{ old('telp', $data->telp) }}">
                    @error('telp')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Email {!! printRequired() !!}</label>
                    <input type="email" class="form-control form-control-sm" name="email" id="donatur_email" required
                        placeholder="Masukkan email" value="{{ old('email', $data->email) }}">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Agama {!! printRequired() !!}</label>
                    <select class="form-select form-select-sm" name="agama" id="agama" required>
                        <option value="" disabled {{ old('agama', $data->religion ?? '') ? '' : 'selected' }}>Pilih
                            Agama
                        </option>
                        <option value="islam" {{ old('agama', $data->religion ?? '') == 'islam' ? 'selected' : '' }}>Islam
                        </option>
                        <option value="kristen" {{ old('agama', $data->religion ?? '') == 'kristen' ? 'selected' : '' }}>
                            Kristen Protestan</option>
                        <option value="katolik" {{ old('agama', $data->religion ?? '') == 'katolik' ? 'selected' : '' }}>
                            Katolik</option>
                        <option value="hindu" {{ old('agama', $data->religion ?? '') == 'hindu' ? 'selected' : '' }}>Hindu
                        </option>
                        <option value="buddha" {{ old('agama', $data->religion ?? '') == 'buddha' ? 'selected' : '' }}>
                            Buddha
                        </option>
                        <option value="konghucu" {{ old('agama', $data->religion ?? '') == 'konghucu' ? 'selected' : '' }}>
                            Konghucu</option>
                    </select>
                    @error('agama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="want_to_contact" id="want_to_contact"
                            value="1" checked={{ old('want_to_contact', $data->want_to_contact) }}>
                        <label class="form-check-label fw-semibold" for="want_to_contact">
                            Berkenan Dihubungi
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="divider mb-2 mt-2"></div>
                </div>

                <div class="col-12 text-end">
                    <input type="reset" class="btn btn-danger" value="Reset">
                    <input type="submit" class="btn btn-info" value="Submit">
                </div>
            </form>
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
        $(document).ready(function() {
            $("#lembaga-select2").select2({
                placeholder: 'Cari Lembaga',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.organization.select2.all') }}",
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
                            let lembaga_name = obj.name;
                            obj.id = obj.id;
                            obj.text = `${lembaga_name}`;

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

            $("#kategori-select2").select2({
                placeholder: 'Cari Kategori Campaign',
                multiple: true,
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.category.select2.all') }}",
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
                            let kategori_name = obj.name;
                            obj.id = obj.id;
                            obj.text = `${kategori_name}`;

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

            // CKEDITOR
            ClassicEditor.create(document.querySelector('#editor'))
                .then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        });

        $("#program_title").on("keyup change", function() {
            var title = $(this).val();
            var title = title.length;
            if (title > 75) {
                $("#count_title").html(title + ' / 75');
                $("#count_title").addClass('text-danger');
            } else {
                $("#count_title").html(title + ' / 75');
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
                url: "{{ route('adm.program.create.check_url') }}",
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

        $("#button-addon1").on("click", function() {
            imageContentUpload('img1');
            $(this).attr('disabled', 'disabled');
        });

        $("#button-addon2").on("click", function() {
            imageContentUpload('img2');
            $(this).attr('disabled', 'disabled');
        });

        $("#button-addon3").on("click", function() {
            imageContentUpload('img3');
            $(this).attr('disabled', 'disabled');
        });

        function imageContentUpload(img) {
            var imgname = $('input[name=title]').val();
            var data = new FormData();

            data.append('file', $('#' + img)[0].files[0]);
            data.append('name', imgname);
            data.append('number', img);

            $.ajax({
                url: "{{ route('adm.program.image.content.submit') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#link_' + img).html(data.link);
                    $('#full_' + img).val(data.full);
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
            return false;
        }

        $("#copy_img1").on("click", function() {
            navigator.clipboard.writeText($('#full_img1').val());
        });

        $("#copy_img2").on("click", function() {
            navigator.clipboard.writeText($('#full_img2').val());
        });

        $("#copy_img3").on("click", function() {
            navigator.clipboard.writeText($('#full_img3').val());
        });

        var rupiah = document.getElementById("rupiah");
        rupiah.addEventListener("keyup", function(e) {
            rupiah.value = formatRupiah(this.value, "");
        });

        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // Editkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
        }
    </script>
@endsection
