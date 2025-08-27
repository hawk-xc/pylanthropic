@extends('layouts.admin', [
    'second_title'    => 'Penyaluran',
    'header_title'    => 'Tambah Penyaluran',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program_payout'
])

@section('content')
    @include('admin.payout._form')
@endsection