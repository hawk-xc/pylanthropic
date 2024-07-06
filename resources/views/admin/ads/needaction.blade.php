@extends('layouts.admin', [
    'second_title'    => 'FB ADS',
    'header_title'    => 'ADS',
    'sidebar_menu'    => 'ads',
    'sidebar_submenu' => 'ads'
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
        .grid-menu .btn {
            border: 1px solid #3f6ad8;
            border-radius: 0.2rem;
        }
        .switch-btn input[type=checkbox] {
            position: relative;
            width: 38px;
            height: 18px;
            appearance: none;
            background: rgba(245, 245, 24, 1);
            outline: none;
            border-radius: 50px;
            cursor: pointer;
            background: rgba(59,168,221, 0.1);
        }
        .switch-btn input[type=checkbox]:before {
            content: "";
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: rgba(141, 143, 145, 1);
            position: absolute;
            top: 50%;
            left: 4px;
            transform: translateY(-50%);
            transition: 0.5s;
        }
        .switch-btn input[type=checkbox]:checked {
            background: rgba(59,168,221, 0.2);
        }
        .switch-btn input[type=checkbox]:checked::before {
            transform: translateX(100%) translateY(-50%);
            background: #3BA8DD;
        }
    </style>
@endsection


@section('content')
    <div class="row g-1">
        <div class="col-sm-12">
            <div class="main-card mb-2 card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 fc-rtl ps-1 pe-1">
                            <div class="grid-menu grid-menu-2col">
                                <div class="no-gutters row">
                                    <div class="col-sm-2">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="fw-bold mb-2">Pilih Account</div>
                                            @if($id==1)
                                                <a href="{{ route('adm.ads.need.action') }}?id=1" class="btn btn-sm btn-primary">Account 1</a>
                                                <a href="{{ route('adm.ads.need.action') }}?id=4" class="btn btn-sm mt-2 mb-1 btn-outline-primary">Account 4</a>
                                            @else
                                                <a href="{{ route('adm.ads.need.action') }}?id=1" class="btn btn-sm btn-outline-primary">Account 1</a>
                                                <a href="{{ route('adm.ads.need.action') }}?id=4" class="btn btn-sm mt-2 mb-1 btn-primary">Account 4</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-success fs-5">
                                                Rp.<span id="donate_paid_rp">1</span>
                                                <span class="badge badge-pill badge-success">2</span>
                                            </div>
                                            <div class="widget-numbers text-dark fs-5">
                                                Rp.<span id="donate_paid_rp">3</span>
                                                <span class="badge badge-pill badge-dark">2</span>
                                            </div>
                                            <div class="widget-subheading">
                                                LP : 0
                                                <span class="badge badge-secondary">Refresh</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-info fs-5">BSI</div>
                                            <div class="widget-numbers text-dark fs-5">Rp. <span id="saldo_today_bsi"></span></div>
                                            <div class="widget-subheading">Last Cehck : <span id="last_check_bsi">08:35:14</span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-primary fs-5">BRI</div>
                                            <div class="widget-numbers text-dark fs-5">Rp. <span id="saldo_today_bri"></span></div>
                                            <div class="widget-subheading">Last Cehck : <span id="last_check_bri">08:35:14</span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-warning fs-5">Mandiri</div>
                                            <div class="widget-numbers text-dark fs-5">Rp. <span id="saldo_today_mandiri"></span></div>
                                            <div class="widget-subheading">Last Cehck : <span id="last_check_mandiri">08:35:14</span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="widget-chart widget-chart-hover">
                                            <div class="widget-numbers text-primary fs-5">BCA</div>
                                            <div class="widget-numbers text-dark fs-5">Rp. <span id="saldo_today_bca"></span></div>
                                            <div class="widget-subheading">Last Cehck : <span id="last_check_bca">08:35:14</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-sm-12 pe-0">
            <div class="main-card mb-3 card">
                <div class="card-body p-3">
                    <div class="card-header p-0" style="border-bottom: 0px; height: auto;">
                        Butuh Tindakan
                        <div class="btn-actions-pane-right">Update Jam : {{ date('H:i') }}</div>
                    </div>
                    <div class="divider"></div>
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Campaign</th>
                                <th>Spent</th>
                                <th>CPR</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i=0; $i < count($need_action); $i++)
                                <tr>
                                    <td>
                                        <div class="switch-btn">
                                            @if(isset($campaign[$need_action[$i]['id']]))
                                                @if($campaign[$need_action[$i]['id']]=='ACTIVE')
                                                    <input type="checkbox" class="text-bottom btn-status" data-id="{{ $need_action[$i]['id'] }}"checked>
                                                @else
                                                    <input type="checkbox" class="text-bottom btn-status" data-id="{{ $need_action[$i]['id'] }}">
                                                @endif
                                            @else
                                                <input type="checkbox" class="text-bottom btn-status" data-id="{{ $need_action[$i]['id'] }}">
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $need_action[$i]['name'] }}</td>
                                    <td>{{ number_format($need_action[$i]['spend']) }}</td>
                                    <td>{{ number_format($need_action[$i]['cpr']) }}</td>
                                    <td>{{ number_format($need_action[$i]['result']) }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-sm-12">
            <div class="main-card mb-3 card">
                <div class="card-body p-3">
                    <div class="card-header p-0" style="border-bottom: 0px; height: auto;">
                        Iklan Lainnya
                    </div>
                    <div class="divider"></div>
                    <table id="table-donatur-mutation" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Campaign</th>
                                <th>Angka</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i=0; $i < count($others); $i++)
                                <tr>
                                    <td>
                                        <div class="switch-btn">
                                            @if(isset($campaign[$others[$i]['id']]))
                                                @if($campaign[$others[$i]['id']]=='ACTIVE')
                                                    <input type="checkbox" class="text-bottom btn-status" data-id="{{ $others[$i]['id'] }}" checked>
                                                @else
                                                    <input type="checkbox" class="text-bottom btn-status" data-id="{{ $others[$i]['id'] }}">
                                                @endif
                                            @else
                                                <input type="checkbox" class="text-bottom btn-status" data-id="{{ $others[$i]['id'] }}">
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $others[$i]['name'] }}</td>
                                    <td>
                                        {{ number_format($others[$i]['spend']) }}<br>
                                        {{ number_format($others[$i]['cpr']) }}<br>
                                        <strong>{{ number_format($others[$i]['result']) }}</strong>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
     $(".btn-status").on("click", function(){
        var campaign_id  = $(this).attr("data-id");
        if($(this).is(':checked')) {
            var status_to = 'ACTIVE';
        } else {
            var status_to = 'PAUSED';
        }

        $(this)[0].disabled = true;

        $.ajax({
            type: "POST",
            url: "{{ route('adm.ads.need.action.status.update') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "campaign_id": campaign_id,
              "account_id": "{{ $account_id }}",
              "status": status_to
            },
            success: function(data){
                if(data=='success') {
                    // toast success
                    alert('Campaign Berhasil di Update');
                } else {
                    alert(data);
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    });
</script>
@endsection
