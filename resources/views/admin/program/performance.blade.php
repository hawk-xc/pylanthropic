@extends('layouts.admin', [
    'second_title'    => 'Performa Program',
    'header_title'    => 'Performa Program',
    'sidebar_menu'    => 'program',
    'sidebar_submenu' => 'donate_performance'
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
                            <li class="breadcrumb-item active" aria-current="page">Program</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-7 fc-rtl">
                    <button class="btn btn-outline-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('adm.program.create') }}" class="btn btn-outline-primary"><i class="fa fa-plus mr-1"></i> Tambah Program</a>
                </div>
            </div>
            <div class="divider"></div>
            <table id="table-donatur" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Average</th>
                        <th>{{ date('d-m-y') }}</th>
                        <?php
                        $dn = date('d-m-Y');
                        for ($a=1; $a<10; $a++) { 
                            echo "<th>".date('d-m-y', strtotime($dn.'-'.$a.' days'))."</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i=0; $i<count($data); $i++) {
                        $sum_row = 10;
                        $d_sum   = [];
                        $d_avg   = 0;

                        for ($d=0; $d<$sum_row; $d++) { 
                            $d_sum[$d] = (isset($data[$i]['donate'][$d]['sum'])) ? $data[$i]['donate'][$d]['sum'] : 0;
                            $d_avg    += $d_sum[$d];
                            if($d_sum[$d]>=1000000) {
                                $d_sum[$d] = "<span class='text-success'>".number_format($d_sum[$d])."</span>";
                            } elseif($d_sum[$d]<1000000 && $d_sum[$d]>=500000) {
                                $d_sum[$d] = number_format($d_sum[$d]);
                            } elseif($d_sum[$d]<500000 && $d_sum[$d]>=250000) {
                                $d_sum[$d] = "<span class='text-warning'>".number_format($d_sum[$d])."</span>";
                            } else {
                                $d_sum[$d] = "<span class='text-danger'>".number_format($d_sum[$d])."</span>";
                            }
                        }

                        $d_avg = $d_avg/10;
                        if($d_avg>=1000000) {
                            $d_avg = "<span class='text-success'>".number_format($d_avg)."</span>";
                        } elseif($d_avg<1000000 && $d_avg>=500000) {
                            $d_avg = number_format($d_avg);
                        } elseif($d_avg<500000 && $d_avg>=250000) {
                            $d_avg = "<span class='text-warning'>".number_format($d_avg)."</span>";
                        } else {
                            $d_avg = "<span class='text-danger'>".number_format($d_avg)."</span>";
                        }

                        echo "<tr>";
                        echo "<td>".$data[$i]['title']."</td>";
                        echo "<td>".number_format($data[$i]['donate_sum'])."<br>".$d_avg."</td>";

                        for ($a=0; $a<$sum_row; $a++) { 
                            echo "<td><b>".(isset($data[$i]['donate'][$a]['count']) ? number_format($data[$i]['donate'][$a]['count']): 0) ."</b><br>". ($d_sum[$a])."</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
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
