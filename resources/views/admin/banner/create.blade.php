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

@section('js_plugins')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('js_inline')
    <script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['type'] }}',
                title: '{{ session('message')['text'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
                },
                background: '{{ session('message')['type'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['type'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection