@extends('layouts.admin', [
    'second_title' => 'Banners',
    'header_title' => 'Daftar Banner',
    'sidebar_menu' => 'banners',
    'sidebar_submenu' => ''
])

@section('content')
<div class="main-card mb-3 card">
    <div class="card-body">
        <h5 class="card-title">Formulir Ubah Banner</h5>
        <div class="divider"></div>
        <form action="{{ route('adm.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.banner._form', ['banner' => $banner])
        </form>
    </div>
</div>
@endsection
