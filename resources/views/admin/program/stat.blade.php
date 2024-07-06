@extends('layouts.admin', [
    'second_title'    => 'Program Detail Statistik',
    'header_title'    => 'Program Detail Statistik',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'program'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .grid-menu [class*=col-] { border-bottom:0px; }
        .grid-menu [class*=col-]:nth-child(2n) { border-right-width:1px; }
        .grid-menu [class*=col-]:nth-last-child(-n+1) { border-right-width:0px !important; }
        .widget-chart { padding:8px !important;  }
        .widget-chart .widget-numbers { margin: 12px auto 14px auto; }
    </style>
@endsection


@section('content')
    <div class="main-card mb-2 card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0 pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adm.program.index') }}">Program</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <span class="fs-6 text-dark">{{ ucwords($program_name) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row pt-1">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Statistik Pengunjung Hari Ini</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-dark fs-5">Visit LP</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_today[0]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-warning fs-5">Klik Donasi</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_today[1]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-info fs-5">Pilih Payment</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_today[2]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-primary fs-5">Halaman Form</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_today[3]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Donasi</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_today[4]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Dibayar</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_today[5]) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row pt-1">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">
                        Pengunjung Landing Page Harian (Rata2 = {{ number_format($visitor_analytic_avg) }})
                    </h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Analisa Donasi Hari Ini</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-info fs-5">Total Donasi</div>
                                    <div class="widget-numbers text-info fs-5">Rp.{{ number_format($donate_today[0]) }} ({{ number_format($visitor_today[4]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Donasi Dibayar</div>
                                    <div class="widget-numbers text-success fs-5">Rp.{{ number_format($donate_today[1]) }} ({{ number_format($visitor_today[5]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-warning fs-5">Belum Dibayar</div>
                                    <div class="widget-numbers text-warning fs-5">Rp.{{ number_format($donate_today[3]) }} ({{ number_format($donate_today[2]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-primary fs-5">Rata2 Donasi</div>
                                    <div class="widget-numbers text-primary fs-5">Rp.{{ number_format($donate_today[4]) }} / Rp.{{ number_format($donate_today[5]) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Rangkuman Keuangan</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Donasi Dibayar</div>
                                    <div class="widget-numbers text-success fs-5">Rp.{{ number_format($summary[0]) }} ({{ number_format($summary[1]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-info fs-5">Penyaluran</div>
                                    <div class="widget-numbers text-info fs-5">Rp.{{ number_format($summary[2]) }} ({{ number_format($summary[3]) }})</div>
                                </div>
                            </div>
                            <?php
                                if($summary[0]>0){
                                    if($summary[4]>($summary[0]*20/100)) {
                                        $sisa = $summary[0]-$summary[2]-($summary[0]*5/100)-$summary[4]-($summary[0]*2/100);
                                    } else {
                                        $sisa = $summary[0]-$summary[2]-($summary[0]*5/100)-($summary[0]*20/100)-($summary[0]*2/100);
                                    }

                                    $ads_fee20 = $summary[0]*20/100;
                                    $plat_fee  = $summary[0]*5/100;
                                    $bank_fee  = $summary[0]*2/100;
                                } else {
                                    $ads_fee20 = 0;
                                    $plat_fee  = 0;
                                    $bank_fee  = 0;
                                    $sisa      = 0;
                                }
                            ?>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-warning fs-5">Ads Fee</div>
                                    <div class="widget-numbers text-warning fs-5">Rp.{{ number_format($ads_fee20) }} / {{ number_format($summary[4]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-danger fs-5">Platform Fee</div>
                                    <div class="widget-numbers text-danger fs-5">Rp.{{ number_format($plat_fee) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-danger fs-5">Bank Fee</div>
                                    <div class="widget-numbers text-danger fs-5">Rp.{{ number_format($bank_fee) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-primary fs-5">Sisa Dana</div>
                                    <div class="widget-numbers text-primary fs-5">Rp.{{ number_format($sisa) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Statistik Pengunjung Keseluruhan</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-dark fs-5">Visit LP</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_all[0]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-warning fs-5">Klik Donasi</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_all[1]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-info fs-5">Pilih Payment</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_all[2]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-primary fs-5">Halaman Form</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_all[3]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Donasi</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_all[4]) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Dibayar</div>
                                    <div class="widget-numbers text-dark fs-5">{{ number_format($visitor_all[5]) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Analisa Donasi Keseluruhan</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-info fs-5">Total Donasi</div>
                                    <div class="widget-numbers text-info fs-5">Rp.{{ number_format($donate_all[0]) }} ({{ number_format($visitor_all[4]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-success fs-5">Donasi Dibayar</div>
                                    <div class="widget-numbers text-success fs-5">Rp.{{ number_format($donate_all[1]) }} ({{ number_format($visitor_all[5]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-warning fs-5">Belum Dibayar</div>
                                    <div class="widget-numbers text-warning fs-5">Rp.{{ number_format($donate_all[3]) }} ({{ number_format($donate_all[2]) }})</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="widget-numbers text-primary fs-5">Rata2 Donasi</div>
                                    <div class="widget-numbers text-primary fs-5">Rp.{{ number_format($donate_all[4]) }} / Rp.{{ number_format($donate_all[5]) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Statistik Donasi Rata2 Perhari</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <table class="table table-hover table-responsive mb-1">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Senin</th>
                                <th>Selasa</th>
                                <th>Rabu</th>
                                <th>Kamis</th>
                                <th>Jumat</th>
                                <th>Sabtu</th>
                                <th>Minggu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi</td>
                                <td>20 / 50</td>
                                <td>20 / 50</td>
                                <td>20 / 50</td>
                                <td>20 / 50</td>
                                <td>20 / 50</td>
                                <td>20 / 50</td>
                                <td>20 / 50</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                                <td>Rp.53.000 / Rp.55.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-2 mt-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Statistik Donasi Rata2 Pertanggal</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <table class="table table-hover table-responsive mb-3">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>
                                <th>11</th>
                                <th>12</th>
                                <th>13</th>
                                <th>14</th>
                                <th>15</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi</td>
                                <td>{{ number_format($per_date_count[0]) }}</td>
                                <td>{{ number_format($per_date_count[1]) }}</td>
                                <td>{{ number_format($per_date_count[2]) }}</td>
                                <td>{{ number_format($per_date_count[3]) }}</td>
                                <td>{{ number_format($per_date_count[4]) }}</td>
                                <td>{{ number_format($per_date_count[5]) }}</td>
                                <td>{{ number_format($per_date_count[6]) }}</td>
                                <td>{{ number_format($per_date_count[7]) }}</td>
                                <td>{{ number_format($per_date_count[8]) }}</td>
                                <td>{{ number_format($per_date_count[9]) }}</td>
                                <td>{{ number_format($per_date_count[10]) }}</td>
                                <td>{{ number_format($per_date_count[11]) }}</td>
                                <td>{{ number_format($per_date_count[12]) }}</td>
                                <td>{{ number_format($per_date_count[13]) }}</td>
                                <td>{{ number_format($per_date_count[14]) }}</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi</td>
                                <td>{{ number_format($per_date_nominal[0]) }}</td>
                                <td>{{ number_format($per_date_nominal[1]) }}</td>
                                <td>{{ number_format($per_date_nominal[2]) }}</td>
                                <td>{{ number_format($per_date_nominal[3]) }}</td>
                                <td>{{ number_format($per_date_nominal[4]) }}</td>
                                <td>{{ number_format($per_date_nominal[5]) }}</td>
                                <td>{{ number_format($per_date_nominal[6]) }}</td>
                                <td>{{ number_format($per_date_nominal[7]) }}</td>
                                <td>{{ number_format($per_date_nominal[8]) }}</td>
                                <td>{{ number_format($per_date_nominal[9]) }}</td>
                                <td>{{ number_format($per_date_nominal[10]) }}</td>
                                <td>{{ number_format($per_date_nominal[11]) }}</td>
                                <td>{{ number_format($per_date_nominal[12]) }}</td>
                                <td>{{ number_format($per_date_nominal[13]) }}</td>
                                <td>{{ number_format($per_date_nominal[14]) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-hover table-responsive mb-3">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>16</th>
                                <th>17</th>
                                <th>18</th>
                                <th>19</th>
                                <th>20</th>
                                <th>21</th>
                                <th>22</th>
                                <th>23</th>
                                <th>24</th>
                                <th>25</th>
                                <th>26</th>
                                <th>27</th>
                                <th>28</th>
                                <th>29</th>
                                <th>30</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi</td>
                                <td>{{ number_format($per_date_count[15]) }}</td>
                                <td>{{ number_format($per_date_count[16]) }}</td>
                                <td>{{ number_format($per_date_count[17]) }}</td>
                                <td>{{ number_format($per_date_count[18]) }}</td>
                                <td>{{ number_format($per_date_count[19]) }}</td>
                                <td>{{ number_format($per_date_count[20]) }}</td>
                                <td>{{ number_format($per_date_count[21]) }}</td>
                                <td>{{ number_format($per_date_count[22]) }}</td>
                                <td>{{ number_format($per_date_count[23]) }}</td>
                                <td>{{ number_format($per_date_count[24]) }}</td>
                                <td>{{ number_format($per_date_count[25]) }}</td>
                                <td>{{ number_format($per_date_count[26]) }}</td>
                                <td>{{ number_format($per_date_count[27]) }}</td>
                                <td>{{ number_format($per_date_count[28]) }}</td>
                                <td>{{ number_format($per_date_count[29]) }}</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi</td>
                                <td>{{ number_format($per_date_nominal[15]) }}</td>
                                <td>{{ number_format($per_date_nominal[16]) }}</td>
                                <td>{{ number_format($per_date_nominal[17]) }}</td>
                                <td>{{ number_format($per_date_nominal[18]) }}</td>
                                <td>{{ number_format($per_date_nominal[19]) }}</td>
                                <td>{{ number_format($per_date_nominal[20]) }}</td>
                                <td>{{ number_format($per_date_nominal[21]) }}</td>
                                <td>{{ number_format($per_date_nominal[22]) }}</td>
                                <td>{{ number_format($per_date_nominal[23]) }}</td>
                                <td>{{ number_format($per_date_nominal[24]) }}</td>
                                <td>{{ number_format($per_date_nominal[25]) }}</td>
                                <td>{{ number_format($per_date_nominal[26]) }}</td>
                                <td>{{ number_format($per_date_nominal[27]) }}</td>
                                <td>{{ number_format($per_date_nominal[28]) }}</td>
                                <td>{{ number_format($per_date_nominal[29]) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-12">
                    <h5 class="card-title fs-6 mb-0 text-dark">Statistik Donasi Rata2 Perjam</h5>
                </div>
            </div>
            <div class="divider mt-2"></div>
            <div class="row">
                <div class="col-12 mb-2">
                    <table class="table table-hover table-responsive mb-3">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>00:..</th>
                                <th>01:..</th>
                                <th>02:..</th>
                                <th>03:..</th>
                                <th>04:..</th>
                                <th>05:..</th>
                                <th>06:..</th>
                                <th>07:..</th>
                                <th>08:..</th>
                                <th>09:..</th>
                                <th>10:..</th>
                                <th>11:..</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi</td>
                                <td>{{ number_format($per_time_count[0], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[1], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[2], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[3], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[4], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[5], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[6], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[7], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[8], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[9], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[10], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[11], 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi</td>
                                <td>{{ number_format($per_time_nominal[0]) }}</td>
                                <td>{{ number_format($per_time_nominal[1]) }}</td>
                                <td>{{ number_format($per_time_nominal[2]) }}</td>
                                <td>{{ number_format($per_time_nominal[3]) }}</td>
                                <td>{{ number_format($per_time_nominal[4]) }}</td>
                                <td>{{ number_format($per_time_nominal[5]) }}</td>
                                <td>{{ number_format($per_time_nominal[6]) }}</td>
                                <td>{{ number_format($per_time_nominal[7]) }}</td>
                                <td>{{ number_format($per_time_nominal[8]) }}</td>
                                <td>{{ number_format($per_time_nominal[9]) }}</td>
                                <td>{{ number_format($per_time_nominal[10]) }}</td>
                                <td>{{ number_format($per_time_nominal[11]) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-hover table-responsive mb-1">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>12:..</th>
                                <th>13:..</th>
                                <th>14:..</th>
                                <th>15:..</th>
                                <th>16:..</th>
                                <th>17:..</th>
                                <th>18:..</th>
                                <th>19:..</th>
                                <th>20:..</th>
                                <th>21:..</th>
                                <th>22:..</th>
                                <th>23:..</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi</td>
                                <td>{{ number_format($per_time_count[12], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[13], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[14], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[15], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[16], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[17], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[18], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[19], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[20], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[21], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[22], 2, ',', '.') }}</td>
                                <td>{{ number_format($per_time_count[23], 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi</td>
                                <td>{{ number_format($per_time_nominal[12]) }}</td>
                                <td>{{ number_format($per_time_nominal[13]) }}</td>
                                <td>{{ number_format($per_time_nominal[14]) }}</td>
                                <td>{{ number_format($per_time_nominal[15]) }}</td>
                                <td>{{ number_format($per_time_nominal[16]) }}</td>
                                <td>{{ number_format($per_time_nominal[17]) }}</td>
                                <td>{{ number_format($per_time_nominal[18]) }}</td>
                                <td>{{ number_format($per_time_nominal[19]) }}</td>
                                <td>{{ number_format($per_time_nominal[20]) }}</td>
                                <td>{{ number_format($per_time_nominal[21]) }}</td>
                                <td>{{ number_format($per_time_nominal[22]) }}</td>
                                <td>{{ number_format($per_time_nominal[23]) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content_modal')
    <!-- Modal Show Stats -->
    <div class="modal fade" id="modal_show_donate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h1 class="modal-title fs-5" id="modalTitle">Donate Report</h1>
                <button type="button" class="btn-close pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4" id="modalBody">
                
            </div>
            <div class="modal-footer pt-2 pb-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
      </div>
    </div>
@endsection


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
    let tgl        = [];
    let tgl_before = new Date();
    tgl            = [tgl_before.getDate() + '-' + tgl_before.getMonth()];
    for(i=1; i<30; i++) {
        let tgl_before = new Date(Date.now() - i*24*60*60*1000);
        tgl.push(tgl_before.getDate() + '-' + tgl_before.getMonth());
    }

    let val_visitor      = [];
    let val_donate_btn   = [];
    let val_donate_count = [];
    let val_donate_paid  = [];
    <?php for($i=0; $i<30; $i++) { ?>
        val_visitor.push({{ $visitor_analytic[$i] }});
        val_donate_btn.push({{ $click_donate[$i] }});
        val_donate_count.push({{ $donate_count[$i] }});
        val_donate_paid.push({{ $donate_paid[$i] }});
    <?php }?>


    new Chart(document.getElementById('myChart'), {
        type: 'line',
        data: {
            labels: tgl,
            datasets: [{
                label: 'Visit LP',
                data: val_visitor,
                // borderWidth: 1,
                // fill: true,
                borderColor: 'rgb(170, 215, 217)',
                tension: 0.1
            },
            {
                label: 'Klik Donasi',
                data: val_donate_btn,
                borderColor: 'rgb(40, 169, 224)',
                tension: 0.13
            },
            {
                label: 'Donasi/Transaksi',
                data: val_donate_count,
                borderColor: 'rgb(255, 181, 52)',
                tension: 0.15
            },
            {
                label: 'Dibayar',
                data: val_donate_paid,
                borderColor: 'rgb(139, 197, 61)',
                tension: 0.18
            }]
        },
        // options: {
        //   scales: {
        //     y: {
        //       beginAtZero: true
        //     }
        //   }
        // }
    });


    var table = $('#table-donatur').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        columnDefs: [
            { "width": "22%", "targets": 0 }
        ],
        order: [[4, 'desc']],
        ajax: "{{ route('adm.program.datatables') }}",
        columns: [
            {data: 'title', name: 'title'},
            {data: 'nominal', name: 'nominal'},
            {data: 'status', name: 'status'},
            {data: 'organization', name: 'organization'},
            {data: 'donate', name: 'donate'},
            {data: 'stats', name: 'stats'},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
        ]
    });

    function showDonate(id, title) {
        $("#modalTitle").html(title);
        
        $.ajax({
            type: "GET",
            url: "{{ route('adm.program.show.donate') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id": id
            },
            success: function(data){
                $("#modalBody").html(data);
            }
        });

        let myModal = new bootstrap.Modal(document.getElementById('modal_show_donate'));
        myModal.show();
    }
</script>
@endsection
