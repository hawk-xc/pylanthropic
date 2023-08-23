@extends('layouts.admin', [
    'second_title'    => 'Laporan Settlement',
    'header_title'    => 'Laporan Settlement',
    'sidebar_menu'    => 'report',
    'sidebar_submenu' => 'settlement'
])


@section('css_plugins')
    
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="mt-3 row">
                            <div class="col-sm-6">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Trans ID</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Trans ID</th>
                                            <th>Nominal</th>
                                            <th>Jenis</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@endsection


@section('content_modal')
    
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
@endsection


@section('js_inline')
<script type="text/javascript">

</script>
@endsection
