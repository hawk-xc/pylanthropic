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
                                            <th>{{ date('F Y', strtotime($date[4])) }}</th>
                                            <th>{{ date('F Y', strtotime($date[5])) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Donasi All</td>
                                            <td>{{ number_format($count_donate_all[0]) }}</td>
                                            <td>{{ number_format($count_donate_all[1]) }}</td>
                                            <td>{{ number_format($count_donate_all[2]) }}</td>
                                            <td>{{ number_format($count_donate_all[3]) }}</td>
                                            <td>{{ number_format($count_donate_all[4]) }}</td>
                                            <td>{{ number_format($count_donate_all[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi All</td>
                                            <td>Rp. {{ number_format($sum_donate_all[0]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[1]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[2]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[3]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[4]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_all[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Terbayar</td>
                                            <td>{{ number_format($count_donate_paid[0]) }}</td>
                                            <td>{{ number_format($count_donate_paid[1]) }}</td>
                                            <td>{{ number_format($count_donate_paid[2]) }}</td>
                                            <td>{{ number_format($count_donate_paid[3]) }}</td>
                                            <td>{{ number_format($count_donate_paid[4]) }}</td>
                                            <td>{{ number_format($count_donate_paid[5]) }}</td>
                                        </tr>
                                        <tr style="font-weight: 700;">
                                            <td>Donasi Terbayar</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[0]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[1]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[2]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[3]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[4]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_paid[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Unpaid</td>
                                            <td>{{ number_format($count_donate_unpaid[0]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[1]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[2]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[3]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[4]) }}</td>
                                            <td>{{ number_format($count_donate_unpaid[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Unpaid</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[0]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[1]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[2]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[3]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[4]) }}</td>
                                            <td>Rp. {{ number_format($sum_donate_unpaid[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nominal Rata2</td>
                                            <td>Rp. {{ number_format($donate_average_all[0]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[1]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[2]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[3]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[4]) }}</td>
                                            <td>Rp. {{ number_format($donate_average_all[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Lihat Program</td>
                                            <td>{{ number_format($view_program[0]) }}</td>
                                            <td>{{ number_format($view_program[1]) }}</td>
                                            <td>{{ number_format($view_program[2]) }}</td>
                                            <td>{{ number_format($view_program[3]) }}</td>
                                            <td>{{ number_format($view_program[4]) }}</td>
                                            <td>{{ number_format($view_program[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Klik Donasi</td>
                                            @if($view_amount[0]>0 && $view_program[0]>0)
                                                <td>{{ number_format($view_amount[0]) }} ({{ round($view_amount[0]/$view_program[0]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_amount[0]) }}</td>
                                            @endif
                                            @if($view_amount[1]>0 && $view_program[1]>0)
                                                <td>{{ number_format($view_amount[1]) }} ({{ round($view_amount[1]/$view_program[1]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_amount[1]) }}</td>
                                            @endif
                                            @if($view_amount[2]>0 && $view_program[2]>0)
                                                <td>{{ number_format($view_amount[2]) }} ({{ round($view_amount[2]/$view_program[2]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_amount[2]) }}</td>
                                            @endif
                                            @if($view_amount[3]>0 && $view_program[3]>0)
                                                <td>{{ number_format($view_amount[3]) }} ({{ round($view_amount[3]/$view_program[3]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_amount[3]) }}</td>
                                            @endif
                                            @if($view_amount[4]>0 && $view_program[4]>0)
                                                <td>{{ number_format($view_amount[4]) }} ({{ round($view_amount[4]/$view_program[4]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_amount[4]) }}</td>
                                            @endif
                                            @if($view_amount[5]>0 && $view_program[5]>0)
                                                <td>{{ number_format($view_amount[5]) }} ({{ round($view_amount[5]/$view_program[5]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_amount[5]) }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Pilih Pembayaran</td>
                                            @if($view_payment[0]>0 && $view_amount[0]>0)
                                                <td>{{ number_format($view_payment[0]) }} ({{ round($view_payment[0]/$view_amount[0]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_payment[0]) }}</td>
                                            @endif
                                            @if($view_payment[1]>0 && $view_amount[1]>0)
                                                <td>{{ number_format($view_payment[1]) }} ({{ round($view_payment[1]/$view_amount[1]*100, 2) }}%)</td>
                                            @else
                                                <td>R{ number_format($view_payment[1]) }}</td>
                                            @endif
                                            @if($view_payment[2]>0 && $view_amount[2]>0)
                                                <td>{{ number_format($view_payment[2]) }} ({{ round($view_payment[2]/$view_amount[2]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_payment[2]) }}</td>
                                            @endif
                                            @if($view_payment[3]>0 && $view_amount[3]>0)
                                                <td>{{ number_format($view_payment[3]) }} ({{ round($view_payment[3]/$view_amount[3]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_payment[3]) }}</td>
                                            @endif
                                            @if($view_payment[4]>0 && $view_amount[4]>0)
                                                <td>{{ number_format($view_payment[4]) }} ({{ round($view_payment[4]/$view_amount[4]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_payment[4]) }}</td>
                                            @endif
                                            @if($view_payment[5]>0 && $view_amount[5]>0)
                                                <td>{{ number_format($view_payment[5]) }} ({{ round($view_payment[5]/$view_amount[5]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_payment[5]) }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Halaman Form</td>
                                            @if($view_form[0]>0 && $view_payment[0]>0)
                                                <td>{{ number_format($view_form[0]) }} ({{ round($view_form[0]/$view_payment[0]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_form[0]) }}</td>
                                            @endif
                                            @if($view_form[1]>0 && $view_payment[1]>0)
                                                <td>{{ number_format($view_form[1]) }} ({{ round($view_form[1]/$view_payment[1]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_form[1]) }}</td>
                                            @endif
                                            @if($view_form[2]>0 && $view_payment[2]>0)
                                                <td>{{ number_format($view_form[2]) }} ({{ round($view_form[2]/$view_payment[2]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_form[2]) }}</td>
                                            @endif
                                            @if($view_form[3]>0 && $view_payment[3]>0)
                                                <td>{{ number_format($view_form[3]) }} ({{ round($view_form[3]/$view_payment[3]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_form[3]) }}</td>
                                            @endif
                                            @if($view_form[4]>0 && $view_payment[4]>0)
                                                <td>{{ number_format($view_form[4]) }} ({{ round($view_form[4]/$view_payment[4]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_form[4]) }}</td>
                                            @endif
                                            @if($view_form[5]>0 && $view_payment[5]>0)
                                                <td>{{ number_format($view_form[5]) }} ({{ round($view_form[5]/$view_payment[5]*100, 2) }}%)</td>
                                            @else
                                                <td>{{ number_format($view_form[5]) }}</td>
                                            @endif
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
                                            <td>{{ number_format($count_donatur_all[4]) }}</td>
                                            <td>{{ number_format($count_donatur_all[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donatur Baru</td>
                                            <td>{{ number_format($count_donatur_new[0]) }}</td>
                                            <td>{{ number_format($count_donatur_new[1]) }}</td>
                                            <td>{{ number_format($count_donatur_new[2]) }}</td>
                                            <td>{{ number_format($count_donatur_new[3]) }}</td>
                                            <td>{{ number_format($count_donatur_new[4]) }}</td>
                                            <td>{{ number_format($count_donatur_new[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donatur Setia</td>
                                            <td>{{ number_format($count_donatur_old[0]) }}</td>
                                            <td>{{ number_format($count_donatur_old[1]) }}</td>
                                            <td>{{ number_format($count_donatur_old[2]) }}</td>
                                            <td>{{ number_format($count_donatur_old[3]) }}</td>
                                            <td>{{ number_format($count_donatur_old[4]) }}</td>
                                            <td>{{ number_format($count_donatur_old[5]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Hampir Donatur</td>
                                            <td>{{ number_format($count_donatur_hampir[0]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[1]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[2]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[3]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[4]) }}</td>
                                            <td>{{ number_format($count_donatur_hampir[5]) }}</td>
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
