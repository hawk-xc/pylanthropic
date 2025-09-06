@extends('layouts.admin', [
    'second_title' => 'Banner',
    'header_title' => 'Detail Banner',
    'sidebar_menu' => 'banners',
    'sidebar_submenu' => ''
])

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Banner</h6>
            <a href="{{ route('adm.banner.edit', $banner->id) }}" class="btn btn-warning btn-sm">Ubah</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 200px;">Judul</th>
                        <td>{{ $banner->title }}</td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td><a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a></td>
                    </tr>
                    <tr>
                        <th>Gambar</th>
                        <td>
                            @if($banner->image)
                                <img src="{{ asset($banner->image) }}" alt="Banner Image" style="max-width: 400px;" class="img-fluid rounded">
                            @else
                                Tidak Ada Gambar
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Durasi</th>
                        <td>{{ $banner->duration }} hari</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{!! $banner->is_publish ? '<span class="badge bg-success">Dipublikasikan</span>' : '<span class="badge bg-danger">Draft</span>' !!}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $banner->description ?? '-' }}</td>
                    </tr>
                     <tr>
                        <th>Dibuat Oleh</th>
                        <td>{{ $banner->created_by ? \App\Models\User::findOrFail($banner->created_by)->name : 'Tidak Diketahui' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $banner->created_at->format('d F Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ route('adm.banner.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection