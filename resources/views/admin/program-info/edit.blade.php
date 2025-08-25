@extends('layouts.admin', [
    'second_title' => 'Program',
    'header_title' => 'Edit Kabar Terbaru',
    'sidebar_menu' => 'program',
    'sidebar_submenu' => 'program_info',
])

@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.program.index') }}">Program</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.program-info.index') }}">Kabar Terbaru</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('adm.program-info.index') }}" class="btn btn-outline-primary">Kembali</a>
                </div>
            </div>
            <div class="divider"></div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('adm.program-info.update', $programInfo->id) }}" method="POST" class="row gy-4">
                @method('PUT')
                @include('admin.program-info._form')
            </form>
        </div>
    </div>
@endsection

@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('js_inline')
    <script>
        $(document).ready(function() {
            var select2_query;
            $("#program-select2").select2({
                placeholder: 'Cari Program',
                theme: 'bootstrap-5',
                allowClear: true,
                ajax: {
                    url: "{{ route('adm.program.select2.all') }}",
                    delay: 250,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    },
                    processResults: function (data, params) {
                        var items = $.map(data.data, function(obj){
                            obj.id = obj.id;
                            obj.text = obj.title;
                            return obj;
                        });
                        params.page = params.page || 1;
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.extra_data.last_page
                            }
                        };
                    },
                },
                templateResult: function (item) {
                    if (item.loading) {
                        return item.text;
                    }
                    var term = select2_query.term || '';
                    var $result = item.text;
                    return $result;
                },
                language: {
                    searching: function (params) {
                        select2_query = params;
                        return 'Searching...';
                    }
                }
            });
        });
    </script>
@endsection