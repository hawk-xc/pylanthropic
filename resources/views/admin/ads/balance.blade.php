@extends('layouts.admin', [
    'second_title'    => 'Tagihan FB Ads',
    'header_title'    => 'Tagihan FB Ads',
    'sidebar_menu'    => 'ads',
    'sidebar_submenu' => 'balance'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Ads</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <!-- <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('adm.program.create') }}" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah Program</a> -->
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th>BM1</th>
                        <th>BM4</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $data1['name'] }}</td>
                        <td>{{ $data4['name'] }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php
                                if($data1['status']==1) {
                                    echo '<span class="badge badge-sm badge-success">Aktif</span>';
                                } else {
                                    echo '<span class="badge badge-sm badge-danger">Tidak Aktif</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($data4['status']==1) {
                                    echo '<span class="badge badge-sm badge-success">Aktif</span>';
                                } else {
                                    echo '<span class="badge badge-sm badge-danger">Tidak Aktif</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tagihan Aktif</th>
                        <td>
                            <?php
                                if($data1['balance']>11999999) {
                                    echo '<span class="badge badge-sm badge-danger">'.number_format($data1['balance']).'</span>';
                                } elseif($data1['balance']>=10000000 && $data4['balance']<12000000) {
                                    echo '<span class="badge badge-sm badge-warning">'.number_format($data1['balance']).'</span>';
                                } else {
                                    echo 'Rp. '.number_format($data1['balance']);
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($data4['balance']>11999999) {
                                    echo '<span class="badge badge-sm badge-danger">'.number_format($data4['balance']).'</span>';
                                } elseif($data4['balance']>=10000000 && $data4['balance']<12000000) {
                                    echo '<span class="badge badge-sm badge-warning">'.number_format($data4['balance']).'</span>';
                                } else {
                                    echo 'Rp. '.number_format($data4['balance']);
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Belanja</th>
                        <td>Rp. {{ number_format($data1['total']) }}</td>
                        <td>Rp. {{ number_format($data4['total']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('content_modal')
    
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">

</script>
@endsection
