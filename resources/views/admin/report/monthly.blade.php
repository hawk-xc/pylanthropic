@extends('layouts.admin', [
    'second_title'    => 'Laporan Bulanan',
    'header_title'    => 'Laporan Bulanan',
    'sidebar_menu'    => 'report',
    'sidebar_submenu' => 'monthly_report'
])


@section('css_plugins')
    
@endsection


@section('content')
    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-card mb-2 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <h5 class="card-title">{{ date('F Y') }}</h5>
                            </div>
                            <div class="col-9 fc-rtl">
                                <button class="btn btn-sm btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                            </div>
                        </div>
                        <div class="divider mt-1"></div>

                        <div class="mt-3 row">
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <!-- <thead>
                                        <tr>
                                            <th>Nama</th>
                                        </tr>
                                    </thead> -->
                                    <tbody>
                                        <tr>
                                            <td>Penghimpunan</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($donate_sum)) }}</td>
                                        </tr>
                                        <tr title="{{ ($donate_sum==0) ? '0' : round($program_spend/$donate_sum*100, 2) }}%">
                                            <td>Biaya Riil Ads</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($program_spend)) }}</td>
                                        </tr>
                                        <tr title="Platform Fee 5% + 2% Bank & OP">
                                            <td>Biaya Lainnya</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format(($donate_sum*5/100)+($donate_sum*2/100))) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Salur</strong> ({{ ($donate_sum==0) ? '0' : round(($donate_sum-$program_spend-($donate_sum*5/100)-($donate_sum*2/100))/$donate_sum*100, 2) }}%)</td>
                                            <td><strong>Rp. {{ str_replace(',', '.', number_format($donate_sum-$program_spend-($donate_sum*5/100)-($donate_sum*2/100))) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr>
                                            <?php
                                                $pf      = $donate_sum*5/100;
                                                $ads     = $donate_sum*20/100;
                                                $others  = $donate_sum*2.5/100;
                                                $sum_fee = $pf+$ads+$others;
                                            ?>
                                            <td>Platform Fee 5%</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($pf)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Budget Ads 20%</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($ads)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Lainnya 2.5%</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($others)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong>Rp. {{ str_replace(',', '.', number_format($sum_fee)) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr>
                                            <td>Total Fee</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($sum_fee)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Gaji</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format(10100000)) }}</td>
                                        </tr>
                                        <tr title="Kantor 1jt + Server 800rb">
                                            <td>Biaya Lainnya</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format(1800000)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong>Rp. {{ str_replace(',', '.', number_format($sum_fee-10100000-1800000)) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-center mt-2">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr class=" fw-semibold">
                                            <td>BCA</td>
                                            <td>BSI</td>
                                            <td>BRI</td>
                                            <td>BRI</td>
                                            <td>MANDIRI</td>
                                            <td>QRIS</td>
                                            <td>GO-PAY</td>
                                            <td>SHOPEE-PAY</td>
                                            <td>OVO</td>
                                            <td>DANA</td>
                                        </tr>
                                        <tr>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bca)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bsi)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bri)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bni)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($mandiri)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($qris)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($gopay)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($shopeepay)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($ovo)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($dana)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-card mb-2 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <?php $dn = new \DateTime(date('Y-m-d H:i:s')); ?>
                                <h5 class="card-title">{{ $dn->modify('first day of -1 month')->format('F Y') }}</h5>
                            </div>
                            <div class="col-9 fc-rtl">
                                <button class="btn btn-sm btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                            </div>
                        </div>
                        <div class="divider mt-1"></div>

                        <div class="mt-1 row">
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr>
                                            <td>Penghimpunan</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($donate_sum_ago)) }}</td>
                                        </tr>
                                        <tr title="{{ ($donate_sum_ago==0) ? '0' : round($program_spend_ago/$donate_sum_ago*100, 2) }}%">
                                            <td>Biaya Riil Ads</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($program_spend_ago)) }}</td>
                                        </tr>
                                        <tr title="Platform Fee 5% + 2% Bank & OP">
                                            <td>Biaya Lainnya</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format(($donate_sum_ago*5/100)+($donate_sum_ago*2/100))) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Salur</strong> ({{ ($donate_sum_ago==0) ? '0' : round(($donate_sum_ago-$program_spend_ago-($donate_sum_ago*5/100)-($donate_sum_ago*2/100))/$donate_sum_ago*100, 2) }}%)</td>
                                            <td><strong>Rp. {{ str_replace(',', '.', number_format($donate_sum_ago-$program_spend_ago-($donate_sum_ago*5/100)-($donate_sum_ago*2/100))) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr>
                                            <?php
                                                $pf      = $donate_sum_ago*5/100;
                                                $ads     = $donate_sum_ago*20/100;
                                                $others  = $donate_sum_ago*2.5/100;
                                                $sum_fee = $pf+$ads+$others;
                                            ?>
                                            <td>Platform Fee 5%</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($pf)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Budget Ads 20%</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($ads)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Lainnya 2.5%</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($others)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong>Rp. {{ str_replace(',', '.', number_format($sum_fee)) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr>
                                            <td>Total Fee</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($sum_fee)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Gaji</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format(10100000)) }}</td>
                                        </tr>
                                        <tr title="Kantor 1jt + Server 800rb">
                                            <td>Biaya Lainnya</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format(1800000)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong>Rp. {{ str_replace(',', '.', number_format($sum_fee-10100000-1800000)) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-center mt-2">
                                <table class="table table-hover table-responsive mb-1">
                                    <tbody>
                                        <tr class=" fw-semibold">
                                            <td>BCA</td>
                                            <td>BSI</td>
                                            <td>BRI</td>
                                            <td>BNI</td>
                                            <td>MANDIRI</td>
                                            <td>QRIS</td>
                                            <td>GO-PAY</td>
                                            <td>SHOPEE-PAY</td>
                                            <td>OVO</td>
                                            <td>DANA</td>
                                        </tr>
                                        <tr>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bca_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bsi_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bri_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($bni_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($mandiri_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($qris_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($gopay_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($shopeepay_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($ovo_ago)) }}</td>
                                            <td>Rp. {{ str_replace(',', '.', number_format($dana_ago)) }}</td>
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
