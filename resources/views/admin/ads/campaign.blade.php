@extends('layouts.admin', [
    'second_title'    => 'Campaign',
    'header_title'    => 'Campaign',
    'sidebar_menu'    => 'ads',
    'sidebar_submenu' => 'campaign'
])


@section('css_plugins')
    <link href="{{ asset('admin/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection


@section('css_inline')
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
        .btn-xs {
            padding: 3px !important;
            font-size: 13px !important;
        }
    </style>
@endsection


@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Campaign</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-10 fc-rtl">
                    <a href="{{ route('adm.ads.get.new.campaign').'?id=4' }}" target="_blank" class="btn btn-outline-primary">Get Campaign BM4</a>
                    <button class="btn btn-outline-primary" id="btn-active">Aktif</button>
                    <button class="btn btn-outline-primary" id="btn-any-program">Ada Program</button>
                    <button class="btn btn-outline-primary" id="btn-no-program">No Program</button>
                    <button class="btn btn-outline-primary" id="btn-winning">Winning</button>
                    <button class="btn btn-outline-primary" id="btn-splittest">Splittest</button>
                    <button class="btn btn-outline-primary mr-1" id="refresh_table"><i class="fa fa-sync"></i> Refresh</button>
                </div>
            </div>
            <div class="divider"></div>
            <!-- <div class="row">
                <div class="col-12 form-inline">
                    <span>Filter :</span>
                    <input type="text" id="donatur_name" placeholder="Nama Campaign" class="form-control form-control-sm me-1 ms-2"> 
                    <input type="text" id="donatur_title" placeholder="Judul Program" class="form-control form-control-sm me-1">
                    <input type="text" id="donatur_telp" placeholder="Ref Code" class="form-control form-control-sm me-1"> 
                    <input type="text" id="filter_nominal" placeholder="Spent" class="form-control form-control-sm me-1" placeholder="dan lebih besar"> 
                    <button class="btn btn-sm btn-primary" id="filter_search">Cari</button>
                </div>
            </div>
            <div class="divider"></div> -->
            <table id="table-campaign" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama Campaign</th>
                        <th>Program</th>
                        <th>Tgl Buat - REF</th>
                        <th>Donasi & Spent</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="last_donate" value="">
    <input type="hidden" id="fu_val" value="0">
    <input type="hidden" id="1day_val" value="0">
    <input type="hidden" id="5day_val" value="1">
    <input type="hidden" id="bni_val" value="0">
    <input type="hidden" id="bsi_val" value="0">
    <input type="hidden" id="bri_val" value="0">
    <input type="hidden" id="qris_val" value="0">
    <input type="hidden" id="mandiri_val" value="0">
    <input type="hidden" id="gopay_val" value="0">
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
    const WINNING_MIN = 100;

    const filters = {
      // text inputs
      name: '',
      program: '',
      ref_code: '',
      min_spent: '',
      // toggles
      is_active: null,    // 1 atau null
      any_program: null,  // 1 atau null
      no_program: null,   // 1 atau null
      winning: null,      // angka atau null
      splittest: null     // 1 atau null
    };

    // helpers
    function toggleBtn(btn, key, onValue) {
      const isOn = filters[key] !== null;
      if (isOn) {
        filters[key] = null;
        $(btn).removeClass('btn-primary').addClass('btn-outline-primary');
      } else {
        filters[key] = onValue;
        $(btn).removeClass('btn-outline-primary').addClass('btn-primary');
      }
      table.ajax.reload();
    }

    function setBtn(btn, active) {
      $(btn).toggleClass('btn-primary', active)
           .toggleClass('btn-outline-primary', !active);
    }

    function reloadWithInputs() {
      filters.name      = $('#donatur_name').val().trim();   // kirim sebagai ?name=
      filters.program   = $('#donatur_title').val().trim();  // kirim sebagai ?program=
      filters.ref_code  = $('#donatur_telp').val().trim();   // kirim sebagai ?ref_code=
      filters.min_spent = $('#filter_nominal').val().trim(); // kirim sebagai ?min_spent=
      table.ajax.reload();
    }

    // events
    $('#btn-active').on('click', function () {
      // toggle 1/null
      toggleBtn(this, 'is_active', 1);
    });

    $('#btn-any-program').on('click', function () {
      const turningOn = filters.any_program === null;
      filters.any_program = turningOn ? 1 : null;
      // mutually exclusive
      filters.no_program  = null;
      setBtn('#btn-any-program', turningOn);
      setBtn('#btn-no-program', false);
      table.ajax.reload();
    });

    $('#btn-no-program').on('click', function () {
      const turningOn = filters.no_program === null;
      filters.no_program  = turningOn ? 1 : null;
      // mutually exclusive
      filters.any_program = null;
      setBtn('#btn-no-program', turningOn);
      setBtn('#btn-any-program', false);
      table.ajax.reload();
    });

    $('#btn-winning').on('click', function () {
      // kirim angka threshold winning, default 50
      const isOn = filters.winning !== null;
      filters.winning = isOn ? null : WINNING_MIN;
      setBtn('#btn-winning', !isOn);
      table.ajax.reload();
    });

    $('#btn-splittest').on('click', function () {
      // 1 atau null
      const isOn = filters.splittest !== null;
      filters.splittest = isOn ? null : 1;
      setBtn('#btn-splittest', !isOn);
      table.ajax.reload();
    });

    $('#filter_search').on('click', reloadWithInputs);
    $('#refresh_table').on('click', function(){ table.ajax.reload(null, false); });

    // INIT DataTable: pakai ajax.data untuk inject filters
    const table = $('#table-campaign').DataTable({
      orderCellsTop: true,
      fixedHeader: true,
      processing: true,
      serverSide: true,
      responsive: true,
      order: [],
      ajax: {
        url: "{{ route('adm.ads.campaign.datatables') }}",
        data: function (d) {
          // hanya kirim yang terisi
          Object.entries(filters).forEach(([k,v]) => {
            if (v !== null && v !== '' && v !== undefined) d[k] = v;
          });
        }
      },
      columnDefs: [
        { width: "27%", targets: 0 },
        { width: "37%", targets: 1 },
        { width: "14%", targets: 2 },
        { width: "14%", targets: 3 },
        { width: "8%",  targets: 4 },
        { orderable: false, targets: 4 },
        { searchable: false, targets: 4 },
      ],
      columns: [
        {data: 'name',       name: 'name'},
        {data: 'program',    name: 'program'},
        {data: 'start_time', name: 'start_time'},
        {data: 'spend',      name: 'spend'},
        {data: 'action',     name: 'action', orderable: false, searchable: false},
      ]
    });

    // optional: quick search per kolom tetap jalan
    $('#table-campaign thead tr').clone(true).appendTo('#table-campaign thead');
    $('#table-campaign tr:eq(1) th').each(function (i) {
      const title = $(this).text();
      $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search '+title+'" />');
      $('input', this).on('keyup change', function () {
        if (table.column(i).search() !== this.value) {
          table.column(i).search(this.value).draw();
        }
      });
    });

    // initial populate dari input kalau ada
    reloadWithInputs();
</script>
@endsection
