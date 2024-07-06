@extends('layouts.admin', [
    'second_title'    => 'Edit Campaign',
    'header_title'    => 'Edit Campaign',
    'sidebar_menu'    => 'ads',
    'sidebar_submenu' => 'campaign'
])


@section('css_plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection


@section('css_inline')

@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Campaign</li>
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

            <form action="{{ route('adm.ads.update', $ads->id) }}" method="POST" accept-charset="utf-8" class="row gy-3">
                @csrf
                @method('PUT')
                <div class="col-9">
                    <label class="form-label fw-semibold">Judul Campaign - <span id="count_title" class="fw-normal"></span></label>
                    <input type="text" class="form-control form-control-sm" name="title" id="program_title" value="{{ $ads->name }}" readonly>
                </div>
                <div class="col-3">
                    <label class="form-label fw-semibold">Total Belanja</label>
                    <input type="text" class="form-control form-control-sm" name="ref_code" value="{{ number_format($ads->spend, 0, ',', '.') }}" readonly>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Program</label>
                    <select class="form-control form-control-sm" name="program" id="program-select2" required>
                        <option value="{{ $ads->program_id }}">{{ $ads->title }}</option>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Ref Code</label>
                    <input type="text" class="form-control form-control-sm" name="ref_code" value="{{ $ads->ref_code }}">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Status Aktif </label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="active" type="radio" id="active_yes" value="1" 
                        {{ ($ads->is_active==1) ? 'checked' : ''}}>
                        <label class="form-check-label" for="active_yes">Aktif</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="active" type="radio" id="active_no" value="0" 
                        {{ ($ads->is_active==0) ? 'checked' : ''}}>
                        <label class="form-check-label" for="active_no">Tidak Aktif</label>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">Start Time</label>
                    <input type="text" class="form-control form-control-sm" name="start_time" value="{{ date('d-m-Y H:i', strtotime($ads->start_time)) }}" readonly>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
    $(document).ready(function() {
        $("#program-select2").select2({
            placeholder: 'Cari Lembaga',
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

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    var items = $.map(data.data, function(obj){
                        let lembaga_name = obj.title+' - '+number_format(obj.nominal_approved);
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
            templateResult: function (item) {
                // console.log(item);
                // No need to template the searching text
                if (item.loading) {
                    return item.text;
                }

                var term = select2_query.term || '';
                // var $result = markMatch(item.text, term);
                var $result = item.text, term;

                return $result;
            },
            language: {
                searching: function (params) {
                    // Intercept the query as it is happening
                    select2_query = params;

                    // Change this to be appropriate for your application
                    return 'Searching...';
                }
            }
        });

        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 5000);
    });


    function number_format(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return 'Rp.' + x1 + x2;
    }
</script>
@endsection
