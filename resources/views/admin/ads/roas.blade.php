@extends('layouts.admin', [
    'second_title'    => 'ROAS FB Ads',
    'header_title'    => 'ROAS FB Ads',
    'sidebar_menu'    => 'ads',
    'sidebar_submenu' => 'roas'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection


@section('css_inline')
    <style type="text/css">
        .select2-container--bootstrap-5 { display: inline-block;}
        #select2-campaign-select2-container { text-align:left; }
    </style>
@endsection


@section('content')
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="row"><!--
            <div class="col-4">
                @if($ref!='')
                    <span class="font-weight-bold">Ref Code = {{ strtoupper($ref) }}</span>
                @else
                    <span class="font-weight-bold">Ref Code = -</span>
                @endif
            </div> -->
            <div class="col-12 fc-rtl">
                <div class="form-inline d-inline">
                    <!-- <input type="text" name="" class="form-control form-control-sm me-1" id="ref_code" placeholder="Masukkan REF Code"> -->
                    <select class="form-control form-control-sm" name="campaign" id="campaign-select2" style="width:75%">
                        @if($name_campaign!='')
                            <option value="{{ $id_campaign }}">{{ $name_campaign }}</option>
                        @endif
                    </select>
                    <select class="form-control form-control-sm me-1" name="type_time" id="type_time">
                        <option value="all" {{ ($type_time=='all') ? 'selected' : '' }}>Semua</option>
                        <option value="today" {{ ($type_time=='today') ? 'selected' : '' }}>Hari Ini</option>
                        <option value="day7" {{ ($type_time=='day7') ? 'selected' : '' }}>7 Hari</option>
                        <option value="day14" {{ ($type_time=='day14') ? 'selected' : '' }}>14 Hari</option>
                        <option value="day30" {{ ($type_time=='day30') ? 'selected' : '' }}>30 Hari</option>
                        <option value="monthago" {{ ($type_time=='monthago') ? 'selected' : '' }}>Bulan Lalu</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-info" id="btn-search"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <!-- <select class="form-control form-control-sm" name="campaign" id="campaign-select2"></select> -->
        </div>
        @if($ref!='')
            <div class="divider"></div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th>Donasi Semua - JML</th>
                            <td>Rp.{{ number_format($data['all_sum'], 0, ',', '.') }} ({{ number_format($data['all_count'], 0, ',', '.') }})</td>
                        </tr>
                        <tr>
                            <th>Donasi Dibayar - JML</th>
                            <td>Rp.{{ number_format($data['paid_sum'], 0, ',', '.') }} ({{ number_format($data['paid_count'], 0, ',', '.') }})</td>
                        </tr>
                        <tr>
                            <th>Total Spent - Result - CPR</th>
                            <td>Rp.{{ str_replace(',', '.', number_format($data['spent'], 0, ',', '.')) }} ({{ number_format($data['result'], 0, ',', '.') }}) Rp.{{ number_format($data['ads_cpr'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>ROAS</th>
                            <td>{!! $data['roas'] !!} : 1</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6 col-md-12 col-lg-6">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th>Donasi Rata2 Semua</th>
                            <td>Rp.{{ number_format($data['all_cpr'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Donasi Rata2 Dibayar</th>
                            <td>Rp.{{ number_format($data['paid_cpr'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>CPR in DB (Semua)</th>
                            <td>Rp.{{ number_format($data['all_cpr'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>CPR in DB (Dibayar)</th>
                            <td>Rp.{{ number_format($data['paid_cpr'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="divider"></div>
            @if(isset($data['ad_content'][0]['id']))
                <table class="table table-hover table-bordered">
                    <tr>
                        <th>Nama Konten</th>
                        <th>Spent</th>
                        <th>Result</th>
                        <th>CPR</th>
                        <th>Statistik</th>
                        <th>Link</th>
                    </tr>
                    @for($i=0; $i < count($data['ad_content']); $i++)
                        <tr>
                            <td>{{ $data['ad_content'][$i]['name'] }}</td>
                            <td>{{ number_format($data['ad_content'][$i]['spend'], 0, ',', '.')  }}</td>
                            <td>
                                <?php
                                $result      = $data['ad_content'][$i]['result'];
                                $result_show = number_format($data['ad_content'][$i]['result'], 0, ',', '.');
                                if($result>=10) {
                                    echo '<span class="badge badge-sm badge-success">'.$result_show.'</span>';
                                } elseif($result<10 && $result>0) {
                                    echo '<span class="badge badge-sm badge-warning">'.$result_show.'</span>';
                                } else {
                                    echo '<span class="badge badge-sm badge-danger">'.$result_show.'</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $cpr      = $data['ad_content'][$i]['cpr'];
                                $cpr_show = number_format($data['ad_content'][$i]['cpr'], 0, ',', '.');
                                if($cpr<=15000 && $cpr>0) {
                                    echo '<span class="badge badge-sm badge-success">'.$cpr_show.'</span>';
                                } elseif($cpr<=20000 && $cpr>15000) {
                                    echo '<span class="badge badge-sm badge-warning">'.$cpr_show.'</span>';
                                } else {
                                    echo '<span class="badge badge-sm badge-danger">'.$cpr_show.'</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                echo '<span class="badge badge-sm badge-info"><i class="fa fa-eye"></i> '.number_format($data['ad_content'][$i]['view_video'], 0, ',', '.').'</span> ';
                                echo '<span class="badge badge-sm badge-info"><i class="fa fa-pager"></i> '.number_format($data['ad_content'][$i]['view_lp'], 0, ',', '.').'</span> ';
                                echo '<span class="badge badge-sm badge-info"><i class="fa fa-heart"></i> '.number_format($data['ad_content'][$i]['click_donate'], 0, ',', '.').'</span> ';
                                echo '<span class="badge badge-sm badge-info"><i class="fa fa-credit-card"></i> '.number_format($data['ad_content'][$i]['payment_info'], 0, ',', '.').'</span> ';
                                echo '<span class="badge badge-sm badge-info"><i class="fa fa-file-invoice"></i> '.number_format($data['ad_content'][$i]['form'], 0, ',', '.').'</span> ';
                                ?>
                            </td>
                            <td>
                                <?php
                                if($data['ad_content'][$i]['link']!='-') {
                                    echo '<a href="'.$data['ad_content'][$i]['link'].'" target="_blank"><i class="fa fa-video"></i></a>';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                        </tr>
                    @endfor
                </table>
            @else
                Tidak ada data konten
            @endif
        @endif
        <div class="divider"></div>
        <table id="table-donatur" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jml Donasi</th>
                    <th>Judul</th>
                    <th>Staus</th>
                    <th>Tgl Donasi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="interest_val" value="0">
<input type="hidden" id="taken_val" value="0">
<input type="hidden" id="20_val" value="0">
<input type="hidden" id="50_val" value="0">
@endsection


@section('js_plugins')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('js_inline')
<script type="text/javascript">
    var table = $('#table-donatur').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[4, 'desc']],
        ajax: "{{ route('adm.donate.datatables') }}/?ref_code={{ $ref }}",
        "columnDefs": [
            { "width": "21%", "targets": 0 },
            { "width": "14%", "targets": 1 },
            { "width": "35%", "targets": 2 },
            { "width": "16%", "targets": 3 },
            { "width": "14%", "targets": 4 },
            { "orderable": false, "targets": 1 },
            { "orderable": false, "targets": 2 },
            { "orderable": false, "targets": 3 },
        ],
        columns: [
            {data: 'name', name: 'name'},
            {data: 'nominal_final', name: 'nominal_final'},
            {data: 'title', name: 'title'},
            {data: 'invoice', name: 'invoice'},
            {data: 'created_at', name: 'created_at'},
            // {
            //     data: 'action',
            //     name: 'action',
            //     orderable: false,
            //     searchable: false
            // },
        ]
    });
    $('#table-donatur thead tr').clone(true).appendTo( '#table-donatur thead' );
    $('#table-donatur tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control form-control-sm" placeholder="Search '+title+'" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    });

    $("#campaign-select2").select2({
        placeholder: 'Cari Campaign',
        theme: 'bootstrap-5',
        allowClear: true,
        ajax: {
            url: "{{ route('adm.ads.select2.all').'/?ref_code=ada' }}",
            delay: 250,
            data: function (params) {
                var query = {
                    search: params.term,
                    // page: params.page || 1
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data, params) {
                var items = $.map(data.data, function(obj){
                    let lembaga_name = obj.name+' - '+obj.ref_code;
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

    $("#btn-search").on("click", function(){
        let id_campaign = $('#campaign-select2').val();
        let type_time   = $('#type_time').val();
        if(id_campaign!==null) {
            window.location.replace("{{ route('adm.ads.roas') }}/?ref="+id_campaign+'&type_time='+type_time);
        }
    });
</script>
@endsection
