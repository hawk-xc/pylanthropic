@extends('layouts.admin', [
    'second_title'    => 'Laporan Penghimpunan',
    'header_title'    => 'Laporan Penghimpunan',
    'sidebar_menu'    => 'report',
    'sidebar_submenu' => 'collection'
])


@section('css_plugins')
    
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Donate Report</h5>
                        <div class="mt-3 row">
                            <div class="col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            @for($i=0; $i<=9; $i++)
                                                <th {{ $date_list_color[$i] }}>{{ $date_list[$i] }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JML Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success[0]) }}</td>
                                            <td>{{ number_format($donate_success[1]) }}</td>
                                            <td>{{ number_format($donate_success[2]) }}</td>
                                            <td>{{ number_format($donate_success[3]) }}</td>
                                            <td>{{ number_format($donate_success[4]) }}</td>
                                            <td>{{ number_format($donate_success[5]) }}</td>
                                            <td>{{ number_format($donate_success[6]) }}</td>
                                            <td>{{ number_format($donate_success[7]) }}</td>
                                            <td>{{ number_format($donate_success[8]) }}</td>
                                            <td>{{ number_format($donate_success[9]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rp Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success_rp[0]) }}</td>
                                            <td>{{ number_format($donate_success_rp[1]) }}</td>
                                            <td>{{ number_format($donate_success_rp[2]) }}</td>
                                            <td>{{ number_format($donate_success_rp[3]) }}</td>
                                            <td>{{ number_format($donate_success_rp[4]) }}</td>
                                            <td>{{ number_format($donate_success_rp[5]) }}</td>
                                            <td>{{ number_format($donate_success_rp[6]) }}</td>
                                            <td>{{ number_format($donate_success_rp[7]) }}</td>
                                            <td>{{ number_format($donate_success_rp[8]) }}</td>
                                            <td>{{ number_format($donate_success_rp[9]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar</td>
                                            <td>{{ number_format($donate_draft[0]) }}</td>
                                            <td>{{ number_format($donate_draft[1]) }}</td>
                                            <td>{{ number_format($donate_draft[2]) }}</td>
                                            <td>{{ number_format($donate_draft[3]) }}</td>
                                            <td>{{ number_format($donate_draft[4]) }}</td>
                                            <td>{{ number_format($donate_draft[5]) }}</td>
                                            <td>{{ number_format($donate_draft[6]) }}</td>
                                            <td>{{ number_format($donate_draft[7]) }}</td>
                                            <td>{{ number_format($donate_draft[8]) }}</td>
                                            <td>{{ number_format($donate_draft[9]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar Rp</td>
                                            <td>{{ number_format($donate_draft_rp[0]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[1]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[2]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[3]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[4]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[5]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[6]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[7]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[8]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[9]) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            @for($i=10; $i<=19; $i++)
                                                <th {{ $date_list_color[$i] }}>{{ $date_list[$i] }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JML Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success[10]) }}</td>
                                            <td>{{ number_format($donate_success[11]) }}</td>
                                            <td>{{ number_format($donate_success[12]) }}</td>
                                            <td>{{ number_format($donate_success[13]) }}</td>
                                            <td>{{ number_format($donate_success[14]) }}</td>
                                            <td>{{ number_format($donate_success[15]) }}</td>
                                            <td>{{ number_format($donate_success[16]) }}</td>
                                            <td>{{ number_format($donate_success[17]) }}</td>
                                            <td>{{ number_format($donate_success[18]) }}</td>
                                            <td>{{ number_format($donate_success[19]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rp Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success_rp[10]) }}</td>
                                            <td>{{ number_format($donate_success_rp[11]) }}</td>
                                            <td>{{ number_format($donate_success_rp[12]) }}</td>
                                            <td>{{ number_format($donate_success_rp[13]) }}</td>
                                            <td>{{ number_format($donate_success_rp[14]) }}</td>
                                            <td>{{ number_format($donate_success_rp[15]) }}</td>
                                            <td>{{ number_format($donate_success_rp[16]) }}</td>
                                            <td>{{ number_format($donate_success_rp[17]) }}</td>
                                            <td>{{ number_format($donate_success_rp[18]) }}</td>
                                            <td>{{ number_format($donate_success_rp[19]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar</td>
                                            <td>{{ number_format($donate_draft[10]) }}</td>
                                            <td>{{ number_format($donate_draft[11]) }}</td>
                                            <td>{{ number_format($donate_draft[12]) }}</td>
                                            <td>{{ number_format($donate_draft[13]) }}</td>
                                            <td>{{ number_format($donate_draft[14]) }}</td>
                                            <td>{{ number_format($donate_draft[15]) }}</td>
                                            <td>{{ number_format($donate_draft[16]) }}</td>
                                            <td>{{ number_format($donate_draft[17]) }}</td>
                                            <td>{{ number_format($donate_draft[18]) }}</td>
                                            <td>{{ number_format($donate_draft[19]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar Rp</td>
                                            <td>{{ number_format($donate_draft_rp[10]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[11]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[12]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[13]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[14]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[15]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[16]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[17]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[18]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[19]) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            @for($i=20; $i<=29; $i++)
                                                <th {{ $date_list_color[$i] }}>{{ $date_list[$i] }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JML Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success[20]) }}</td>
                                            <td>{{ number_format($donate_success[21]) }}</td>
                                            <td>{{ number_format($donate_success[22]) }}</td>
                                            <td>{{ number_format($donate_success[23]) }}</td>
                                            <td>{{ number_format($donate_success[24]) }}</td>
                                            <td>{{ number_format($donate_success[25]) }}</td>
                                            <td>{{ number_format($donate_success[26]) }}</td>
                                            <td>{{ number_format($donate_success[27]) }}</td>
                                            <td>{{ number_format($donate_success[28]) }}</td>
                                            <td>{{ number_format($donate_success[29]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rp Donasi Dibayar</td>
                                            <td>{{ number_format($donate_success_rp[20]) }}</td>
                                            <td>{{ number_format($donate_success_rp[21]) }}</td>
                                            <td>{{ number_format($donate_success_rp[22]) }}</td>
                                            <td>{{ number_format($donate_success_rp[23]) }}</td>
                                            <td>{{ number_format($donate_success_rp[24]) }}</td>
                                            <td>{{ number_format($donate_success_rp[25]) }}</td>
                                            <td>{{ number_format($donate_success_rp[26]) }}</td>
                                            <td>{{ number_format($donate_success_rp[27]) }}</td>
                                            <td>{{ number_format($donate_success_rp[28]) }}</td>
                                            <td>{{ number_format($donate_success_rp[29]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar</td>
                                            <td>{{ number_format($donate_draft[20]) }}</td>
                                            <td>{{ number_format($donate_draft[21]) }}</td>
                                            <td>{{ number_format($donate_draft[22]) }}</td>
                                            <td>{{ number_format($donate_draft[23]) }}</td>
                                            <td>{{ number_format($donate_draft[24]) }}</td>
                                            <td>{{ number_format($donate_draft[25]) }}</td>
                                            <td>{{ number_format($donate_draft[26]) }}</td>
                                            <td>{{ number_format($donate_draft[27]) }}</td>
                                            <td>{{ number_format($donate_draft[28]) }}</td>
                                            <td>{{ number_format($donate_draft[29]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Donasi Blm Dibayar Rp</td>
                                            <td>{{ number_format($donate_draft_rp[20]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[21]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[22]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[23]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[24]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[25]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[26]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[27]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[28]) }}</td>
                                            <td>{{ number_format($donate_draft_rp[29]) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Donasi Setiap Jam (WIB) Semua Campaign</h5>
                        <div class="mt-3 row">
                            <div class="col-sm-12">
                                <table class="table table-hover table-responsive mb-4">
                                    <thead>
                                        <tr>
                                            <th>01:00</th>
                                            <th>02:00</th>
                                            <th>03:00</th>
                                            <th>04:00</th>
                                            <th>05:00</th>
                                            <th>06:00</th>
                                            <th>07:00</th>
                                            <th>08:00</th>
                                            <th>09:00</th>
                                            <th>10:00</th>
                                            <th>11:00</th>
                                            <th>12:00</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ number_format($donate_perjam_count[1]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[2]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[3]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[4]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[5]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[6]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[7]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[8]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[9]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[10]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[11]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[12]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ number_format($donate_perjam_sum[1]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[2]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[3]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[4]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[5]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[6]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[7]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[8]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[9]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[10]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[11]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[12]) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <thead>
                                        <tr>
                                            <th>13:00</th>
                                            <th>14:00</th>
                                            <th>15:00</th>
                                            <th>16:00</th>
                                            <th>17:00</th>
                                            <th>18:00</th>
                                            <th>19:00</th>
                                            <th>20:00</th>
                                            <th>21:00</th>
                                            <th>22:00</th>
                                            <th>23:00</th>
                                            <th>24:00</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ number_format($donate_perjam_count[13]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[14]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[15]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[16]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[17]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[18]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[19]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[20]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[21]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[22]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[23]) }}</td>
                                            <td>{{ number_format($donate_perjam_count[0]) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ number_format($donate_perjam_sum[13]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[14]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[15]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[16]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[17]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[18]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[19]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[20]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[21]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[22]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[23]) }}</td>
                                            <td>{{ number_format($donate_perjam_sum[0]) }}</td>
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
