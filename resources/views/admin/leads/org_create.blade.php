@extends('layouts.admin', [
    'second_title'    => 'Add Org - Leads',
    'header_title'    => 'Tambah Lembaga - Leads',
    'sidebar_menu'    => 'leads',
    'sidebar_submenu' => 'leads'
])


@section('css_plugins')

@endsection


@section('css_inline')
    <style type="text/css">
        
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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('adm.leads.index') }}">Leads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Organization</li>
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

            <form action="{{ route('adm.leads.org.store') }}" method="POST" accept-charset="utf-8" class="row gy-3">
                @csrf
                <div class="col-8">
                    <label class="form-label fw-semibold">Nama Lembaga</label>
                    <input type="text" class="form-control form-control-sm" name="name" value="" required>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">No Telp / WA</label>
                    <input type="text" class="form-control form-control-sm" name="phone" value="">
                </div>
                <div class="col-8">
                    <label class="form-label fw-semibold">Alamat</label>
                    <input type="text" class="form-control form-control-sm" name="address" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="text" class="form-control form-control-sm" name="email" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Instagram</label>
                    <input type="text" class="form-control form-control-sm" name="ig" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Facebook</label>
                    <input type="text" class="form-control form-control-sm" name="fb" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Youtube</label>
                    <input type="text" class="form-control form-control-sm" name="yt" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Twitter</label>
                    <input type="text" class="form-control form-control-sm" name="tw" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">FB Pixel</label>
                    <input type="text" class="form-control form-control-sm" name="pixel" value="">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">GTM</label>
                    <input type="text" class="form-control form-control-sm" name="gtm" value="">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Keterangan Organisasi</label>
                    <textarea class="form-control form-control-sm" name="desc"></textarea>
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
@endsection


@section('js_inline')
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 5000);
    });

</script>
@endsection
