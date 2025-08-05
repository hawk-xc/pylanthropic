@extends('layouts.admin', [
    'second_title'    => 'Edit Lembaga',
    'header_title'    => 'Edit Lembaga',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'organization'
])


@section('css_plugins')

@endsection


@section('css_inline')
    <style type="text/css">
        .required:after {
            content:"*";
            color:red;
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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('adm.organization.index') }}">Lembaga</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Lembaga</li>
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

            <form action="{{ route('adm.organization.update', $data->id) }}" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required fw-semibold">Nama Lembaga</label>
                                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Isi nama lembaga" value="{{ $data->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold required">Tentang Lembaga</label>
                                    <textarea class="form-control form-control-sm" name="about" rows="6" placeholder="Bisa isi nama lembaga" required>{{ $data->about }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alamat</label>
                                    <input type="text" class="form-control form-control-sm" name="address" placeholder="Bisa isi Indonesia" value="{{ $data->address }}">
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi PIC</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Nama PIC</label>
                                        <input type="text" class="form-control form-control-sm" placeholder="Opsional" name="pic_name" value="{{ $data->pic_fullname }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">NIK PIC</label>
                                        <input type="text" class="form-control form-control-sm" placeholder="Opsional" name="pic_nik" value="{{ $data->pic_nik }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Gambar PIC</label>
                                        <input type="file" class="form-control form-control-sm" name="pic_image" id="pic_image" onchange="previewImage(this, '#preview-pic-image')">
                                        <img id="preview-pic-image" src="{{ $data->pic_image ? url('/public/images/fundraiser/'.$data->pic_image) : '#' }}" alt="Preview Gambar PIC" style="max-width: 100px; max-height: 100px; margin-top: 10px; {{ $data->pic_image ? '' : 'display: none;' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required fw-semibold">Status</label>
                                    <select class="form-control form-control-sm" name="status" required>
                                        <option disabled value>--Pilih--</option>
                                        <option value="regular" @if($data->status=='regular') {{'selected'}} @endif>Biasa</option>
                                        <option value="verified" @if($data->status=='verified') {{'selected'}} @endif>Terverifikasi Perorangan</option>
                                        <option value="verif_org" @if($data->status=='verif_org') {{'selected'}} @endif>Terverifikasi Lembaga</option>
                                        <option value="banned" @if($data->status=='banned') {{'selected'}} @endif>Banned</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold required">Nomor Telpon</label>
                                    <input type="text" class="form-control form-control-sm" name="phone" placeholder="08..." value="{{ $data->phone }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold required">Email</label>
                                    <input type="email" class="form-control form-control-sm" name="mail" placeholder="Contoh: mail@mail.com" value="{{ $data->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Logo <a href="{{ url('/public/images/fundraiser/'.$data->logo) }}" target="_blank"><i class="fa fa-image"></i></a></label>
                                    <input type="file" class="form-control form-control-sm" name="logo" id="logo" onchange="previewImage(this, '#preview-logo')">
                                    <img id="preview-logo" src="{{ $data->logo ? url('/public/images/fundraiser/'.$data->logo) : '#' }}" alt="Preview Logo" style="max-width: 100px; max-height: 100px; margin-top: 10px; {{ $data->logo ? '' : 'display: none;' }}">
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-body text-end">
                                <input type="reset" class="btn btn-danger" value="Reset">
                                <input type="submit" class="btn btn-info" value="Submit">
                            </div>
                        </div>
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
    });

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result).show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
