@extends('layouts.admin', [
    'second_title'    => 'Laporan M to M',
    'header_title'    => 'Laporan Month To Month',
    'sidebar_menu'    => 'report',
    'sidebar_submenu' => 'mtm_report'
])


@section('css_plugins')
    
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-card mb-2 card">
                    <div class="card-body">
                        <div class="mt-3 row">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-hover mb-1">
                                    <thead>
                                        <tr>
                                            <th>Keterangan</th>
                                            <th>{{ date('F Y', strtotime($date[0])) }}</th>
                                            <th>{{ date('F Y', strtotime($date[1])) }}</th>
                                            <th>{{ date('F Y', strtotime($date[2])) }}</th>
                                            <th>{{ date('F Y', strtotime($date[3])) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Donasi All</td>
                                            <td>{{ number_format($count_donate_all[0]) }}</td>
                                            <td>{{ number_format($count_donate_all[1]) }}</td>
                                            <td>{{ number_format($count_donate_all[2]) }}</td>
                                            <td>{{ number_format($count_donate_all[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi All</td>
                                            <td>Rp. {{ number_format($sum_donate_all[0]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[1]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[2]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Terbayar</td>
                                            <td>{{ number_format($count_donate_paid[0]) }}</td>
                                            <td>{{ number_format($count_donate_paid[1]) }}</td>
                                            <td>{{ number_format($count_donate_paid[2]) }}</td>
                                            <td>{{ number_format($count_donate_paid[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Terbayar</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[0]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[1]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[2]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Unpaid</td>
                                            <td>{{ number_format($count_donate_unpaid[0]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[1]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[2]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Unpaid</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[0]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[1]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[2]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nominal Rata2</td>
                                            <td>Rp. {{ number_format($donate_average_all[0]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[1]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[2]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-center">DONATUR</th>
                                        </tr>
                                        <tr>
                                            <td>Donatur All</td>
                                            <td>{{ number_format($count_donatur_all[0]) }}</td>
                                            <td>{{ number_format($count_donatur_all[1]) }}</td>
                                            <td>{{ number_format($count_donatur_all[2]) }}</td>
                                            <td>{{ number_format($count_donatur_all[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donatur Baru</td>
                                            <td>{{ number_format($count_donatur_new[0]) }}</td>
                                            <td>{{ number_format($count_donatur_new[1]) }}</td>
                                            <td>{{ number_format($count_donatur_new[2]) }}</td>
                                            <td>{{ number_format($count_donatur_new[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donatur Setia</td>
                                            <td>{{ number_format($count_donatur_old[0]) }}</td>
                                            <td>{{ number_format($count_donatur_old[1]) }}</td>
                                            <td>{{ number_format($count_donatur_old[2]) }}</td>
                                            <td>{{ number_format($count_donatur_old[3]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Hampir Donatur</td>
                                            <td>{{ number_format($count_donatur_hampir[0]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[1]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[2]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[3]) }}</td>
                                        </tr>
                                    </tbody>
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
