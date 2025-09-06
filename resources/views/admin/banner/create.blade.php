@extends('layouts.admin', [
    'second_title' => 'Banners',
    'header_title' => 'Daftar Banner',
    'sidebar_menu' => 'banners',
    'sidebar_submenu' => ''
])

@section('content')
<div class="main-card mb-3 card">
    <div class="card-body">
        <h5 class="card-title">Formulir Tambah Banner</h5>
        <div class="divider"></div>
        <form action="{{ route('adm.banner.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.banner._form')
        </form>
    </div>
</div>
@endsection
