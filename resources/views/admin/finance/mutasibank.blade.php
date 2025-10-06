@extends('layouts.admin', [
    'second_title'    => 'Transaksi x Mutasi',
    'header_title'    => 'Transaksi Donasi x Mutasi',
    'sidebar_menu'    => 'finance',
    'sidebar_submenu' => 'finance_posisikas'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .big-checkbox .form-check-input {
            width: 16px;
            height: 16px;
            margin-top: 3px !important;
        }
        .big-checkbox .form-check-label {
            margin-left: 6px;
        }
        .big-checkbox {
            min-height: auto !important;
        }
        table.dataTable thead tr th {
            word-wrap: break-word;
            word-break: break-all;
        }
        table.dataTable tbody tr td {
            word-wrap: break-word;
            word-break: break-all;
        }
        #table-donatur {
            width: 100% !important;
        }
        .grid-menu [class*=col-] { border-bottom:0px; }
        .grid-menu [class*=col-]:nth-child(2n) { border-right-width:1px; }
        .grid-menu [class*=col-]:nth-last-child(-n+1) { border-right-width:0px !important; }
        .widget-chart { padding:8px !important;  }
        .widget-chart .widget-numbers { margin: 12px auto 14px auto; }
        .grid-menu .badge {
            padding: 2px 4px;
        }
        .copy_id_mutation {
            cursor: pointer;
            text-decoration: none;
        }
    </style>
@endsection


@section('content')
    <div class="row g-1">
        <div class="col-md-12 col-sm-12 pe-0">
            <div class="main-card mb-3 card">
                <div class="card-body p-3">
                    <div>
                        <h5 class="card-title">
                            Saldo MutasiBank : Rp.{{ $saldo_user }}
                        </h5>
                    </div>
                    <div class="divider"></div>
                    <table id="table-donatur" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Nama Bank</th>
                                <th style="text-align: center;">Saldo Saat Ini</th>
                                <th style="text-align: center;">Jumlah Transaksi Masuk Bulan Ini</th>
                                <th style="text-align: center;">Terakhir Cek</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i=0; $i < count($data_account); $i++)
                                <tr>
                                    <td>{{ $data_account[$i]['bank'] }}</td>
                                    <td style="text-align: right;">{{ $data_account[$i]['balance'] }}</td>
                                    <td style="text-align: right;">{{ $data_account[$i]['sum_cr'].' ('.$data_account[$i]['count_cr'].')' }}</td>
                                    <td>{{ $data_account[$i]['last_activity'] }}</td>
                                </tr>
                            @endfor
                            <tr>
                                <th>Total</th>
                                <th style="text-align: right;">{{ $data_others['sum_balance'] }}<br>(Kecuali Cash & Iklan)</th>
                                <th style="text-align: right;">{{ $data_others['sum_cr'].' ('.$data_others['count_cr'].')' }}</th>
                                <th></th>
                            </tr>
                            <tr><td colspan="4"><br></td></tr>
                            <!-- <tr>
                                <th>Total Saldo (kecuali Cash dan Iklan)</th>
                                <td>{{ $data_others['sum_balance'] }}</td>
                            </tr>
                            <tr>
                                <th>Total Uang Masuk Semua Bank Bulan Ini</th>
                                <td>{{ $data_others['sum_cr'] }}</td>
                            </tr> -->
                            <tr>
                                <th colspan="2">Transaksi Tercatat</th>
                                <th style="text-align: right;">{{ $data_others['trans_success'] }}</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="2">Infaq</th>
                                <th style="text-align: right;">{{ $data_others['infaq'] }}</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="2">Platform Fee 5%</th>
                                <td style="text-align: right;">{{ $data_others['platform_fee'] }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="2">Optimation Fee 10%</th>
                                <td style="text-align: right;">{{ $data_others['optimation_fee'] }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Fee 15%</th>
                                <th style="text-align: right;">{{ $data_others['total_fee'] }}</th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="divider"></div>
                    <table id="table-donatur" class="table table-striped table-bordered">
                        
                    </table>
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
    

    var rupiah = document.getElementById("rupiah");
    rupiah.addEventListener("keyup", function(e) {
      // tambahkan 'Rp.' pada saat form di ketik
      // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
      rupiah.value = formatRupiah(this.value, "");
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
      var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
      }

      rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
      return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
    }
</script>
@endsection
