@extends('layouts.admin', [
    'second_title' => 'Detail CRM Prospect',
    'header_title' => 'Detail CRM Prospect',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'crm-leads',
])

@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
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
                                    href="{{ route('adm.program.index') }}">CRM Leads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Prospect</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <a class="btn btn-outline-primary"
                        href={{ route('adm.crm-leads.index', ['leads' => request()->query('leads')]) }}>Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection