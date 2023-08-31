<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Transaction;
use DataTables;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.program.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string',
            'url'          => 'required|string',
            'category'     => 'required',
            'organization' => 'required|numeric',
            'nominal'      => 'required',
            'date_end'     => 'required|date',
            'show'         => 'required',
            'thumbnail'    => 'required|file',
            'img'          => 'required|file',
            'caption'      => 'required',
            'story'        => 'required'
        ]);

        $data                   = new Program;
        $data->title            = $request->title;
        $data->slug             = urlencode($request->url);
        $data->organization_id  = $request->organization;
        $data->nominal_request  = str_replace('.', '', $request->nominal);
        $data->nominal_approved = str_replace('.', '', $request->nominal);
        $data->end_date         = $request->date_end;
        $data->short_desc       = $request->caption;
        $data->about            = $request->story;
        $data->thumbnail        = 'thumbnail'; //$request->thumbnail;
        $data->image            = 'img.jpg'; //$request->img;

        if($request->show == 1){
            $data->is_publish       = 1;
            $data->is_recommended   = 0;
            $data->is_show_home     = 0;
        } elseif($request->show == 2) {
            $data->is_publish       = 1;
            $data->is_recommended   = 1;
            $data->is_show_home     = 0;
        } elseif($request->show == 3) {
            $data->is_publish       = 1;
            $data->is_recommended   = 0;
            $data->is_show_home     = 1;
        } elseif($request->show == 4) {
            $data->is_publish       = 0;
            $data->is_recommended   = 0;
            $data->is_show_home     = 0;
        }

        $data->approved_at      = date('Y-m-d H:i:s');
        $data->approved_by      = 1;
        $data->created_by       = 1;
        $data->save();

        echo "FINISHED";
        // return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Datatables Program
     */
    public function datatablesProgram(Request $request)
    {
        // if ($request->ajax()) {
            $data = Program::select('program.*', 'organization.name as organization')
                    ->join('organization', 'organization.id', 'program.organization_id');
            if(isset($request->is_publish)) {
                $data = $data->where('is_publish', $request->is_publish)->where('end_date', '>', date('Y-m-d'));
            }
            $data = $data->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('nominal', function($row){
                    $sum    = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->sum('nominal_final');
                    if($sum>0) {
                        $sum_percent = round($sum/$row->nominal_approved*100, 2);
                    } else {
                        $sum_percent = 0;
                    }

                    $spend  = \App\Models\ProgramSpend::where('program_id', $row->id)->where('status', 'done')->sum('nominal_approved');
                    if($spend>0 && $sum>0) {
                        $spend_percent = round($spend/$sum*100, 2);
                    } else {
                        $spend_percent = 0;
                    }

                    $param  = $row->id.", '".ucwords(str_replace("'", "", $row->title))."'";

                    return '<span class="badge badge-light" style="cursor:pointer" onclick="showSummary('.$param.')">
                        <i class="fa fa-check-double icon-gradient bg-happy-green"></i> Rp.'.str_replace(',', '.', number_format($row->nominal_approved)).'</span>
                        <br> 
                        <span class="badge badge-light modal_status" style="cursor:pointer" onclick="showDonate('.$param.')">
                        <i class="fa fa-money-bill icon-gradient bg-happy-green"></i> Rp.'.number_format($sum).' ('.$sum_percent.'%)</span>
                        <br>
                        <span class="badge badge-light" style="cursor:pointer" onclick="inpSpend('.$param.')">
                        <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.'.number_format($spend).' ('.$spend_percent.'%)</span>';
                })
                ->addColumn('status', function($row){
                    if($row->approved_at!==NULL) {                                      // disetujui
                        if($row->end_date > date('Y-m-d') && $row->is_publish=='1') {   // masih publish belum berakhir
                            if($row->is_recommended==1) {
                                $status = '<span class="badge badge-success">Publikasi Dipilihan</span><br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> Sejak '.date('d F Y', strtotime($row->approved_at));
                            } elseif($row->is_show_home==1) {
                                $status = '<span class="badge badge-success">Publikasi Dihome</span><br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> Sejak '.date('d F Y', strtotime($row->approved_at));
                            } else {
                                $status = '<span class="badge badge-success">Dipublikasi Biasa</span><br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> Sejak '.date('d F Y', strtotime($row->approved_at));
                            }
                        } elseif($row->is_publish=='0') {                               // tidak dipublikasi
                            $status = '<span class="badge badge-danger">Tidak Dipublikasi</span><br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> Sejak '.date('d F Y', strtotime($row->approved_at));
                        } else {                                                        // sudah berakhir
                            $status = '<span class="badge badge-danger">Sudah Berakhir</span><br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> Sejak '.date('d F Y', strtotime($row->approved_at));
                        }
                    } else {                                                            // belum disetujui
                        $status = '<span class="badge badge-secondary">Belum Disetujui</span>';
                    }

                    return $status;
                })
                ->addColumn('stats', function($row){
                    $view        = "<i class='fa fa-eye icon-gradient bg-malibu-beach'></i> ".number_format($row->count_view);
                    $read_more   = "<i class='fa fa-angle-double-down icon-gradient bg-malibu-beach'></i> ".number_format($row->count_read_more);
                    $amount_page = "<i class='fa fa-download icon-gradient bg-malibu-beach'></i> ".number_format($row->count_amount_page);

                    if($row->count_view>0 && $row->count_amount_page>0) {
                        $amount_per  = round($row->count_amount_page/$row->count_view*100, 2);
                    } else {
                        $amount_per  = 0;
                    }

                    if($row->count_view>0 && $row->count_read_more>0) {
                        $read_more_per  = round($row->count_read_more/$row->count_view*100, 2);
                    } else {
                        $read_more_per  = 0;
                    }
                    
                    
                    
                    return $view.'<br>'.$read_more.' ('.$read_more_per.'%) <br>'.$amount_page.' ('.$amount_per.'%)';
                })
                ->addColumn('donate', function($row){
                    $interest = "<i class='fa fa-file icon-gradient bg-malibu-beach'></i> ".number_format($row->count_pra_checkout);
                    $checkout = \App\Models\Transaction::where('program_id', $row->id)->count('id');
                    $count    = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->count('id');
                    
                    if($row->count_view>0 && $row->count_pra_checkout>0) {
                        $interest_per  = round($row->count_pra_checkout/$row->count_view*100, 2);
                    } else {
                        $interest_per  = 0;
                    }

                    if($checkout>0 && $row->count_view>0) {
                        $checkout_per = round($checkout/$row->count_view*100, 2);
                    } else {
                        $checkout_per = 0;
                    }
                    
                    if($count>0 && $checkout>0) {
                        $count_per = round($count/$checkout*100, 2);
                    } else {
                        $count_per = 0;
                    }

                    return $interest.' ('.$interest_per.'%)
                        <br> <i class="fa fa-shopping-cart icon-gradient bg-malibu-beach"></i> '.number_format($checkout).' ('.$checkout_per.'%)
                        <br> <i class="fa fa-heart icon-gradient bg-happy-green"></i> '.number_format($count).' ('.$count_per.'%)';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-xs">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'nominal', 'status', 'stats', 'donate'])
                ->make(true);
        // }
    }

    /**
     * Datatables Program Dashboard
     */
    public function datatablesProgramDashboard(Request $request)
    {
        // if ($request->ajax()) {
            $data = Program::select('program.*', 'organization.name as organization')
                    ->join('organization', 'organization.id', 'program.organization_id');
            if(isset($request->is_publish)) {
                $data = $data->where('is_publish', $request->is_publish)->where('end_date', '>', date('Y-m-d'));
            }
            $data = $data->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('nominal', function($row){
                    $sum    = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->sum('nominal_final');
                    if($sum>0) {
                        $sum_percent = round($sum/$row->nominal_approved*100, 2);
                    } else {
                        $sum_percent = 0;
                    }

                    $spend  = \App\Models\ProgramSpend::where('program_id', $row->id)->where('status', 'done')->sum('nominal_approved');
                    if($spend>0 && $sum>0) {
                        $spend_percent = round($spend/$sum*100, 2);
                    } else {
                        $spend_percent = 0;
                    }

                    $param  = $row->id.", '".ucwords(str_replace("'", "", $row->title))."'";

                    return '<span class="badge badge-light" style="cursor:pointer" onclick="showSummary('.$param.')">
                        <i class="fa fa-check-double icon-gradient bg-happy-green"></i> Rp.'.str_replace(',', '.', number_format($row->nominal_approved)).'</span>
                        <br> 
                        <span class="badge badge-light modal_status" style="cursor:pointer" onclick="showDonate('.$param.')">
                        <i class="fa fa-money-bill icon-gradient bg-happy-green"></i> Rp.'.number_format($sum).' ('.$sum_percent.'%)</span>
                        <br>
                        <span class="badge badge-light" style="cursor:pointer" onclick="inpSpend('.$param.')">
                        <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.'.number_format($spend).' ('.$spend_percent.'%)</span>';
                })
                ->addColumn('ads', function($row){
                    $trans_prime1 = \App\Models\Transaction::where('program_id', $row->id)->where('created_at', '>', date('Y-m-d').' 00:00:00')
                                    ->where('created_at', '<', date('Y-m-d').' 09:59:59');
                    if($trans_prime1->count()>9) {
                        $prime1_ads = '<span class="badge badge-success" title="10 Donasi Keatas">'.number_format($trans_prime1->count()).' | Rp.'.number_format($trans_prime1->sum('nominal_final')).'</span>';
                    } elseif($trans_prime1->count()>4) {
                        $prime1_ads = '<span class="badge badge-primary" title="5-9 Donasi">'.number_format($trans_prime1->count()).' | Rp.'.number_format($trans_prime1->sum('nominal_final')).'</span>';
                    } elseif($trans_prime1->count()>2) {
                        $prime1_ads = '<span class="badge badge-info" title="3-4 Donasi">'.number_format($trans_prime1->count()).' | Rp.'.number_format($trans_prime1->sum('nominal_final')).'</span>';
                    }  elseif($trans_prime1->count()>0) {
                        $prime1_ads = '<span class="badge badge-secondary" title="0-2 Donasi">'.number_format($trans_prime1->count()).' | Rp.'.number_format($trans_prime1->sum('nominal_final')).'</span>';
                    } else {
                        $prime1_ads = '<span class="badge badge-danger" title="0 Donasi">'.number_format($trans_prime1->count()).' | Rp.'.number_format($trans_prime1->sum('nominal_final')).'</span>';
                    }

                    $trans_prime2 = \App\Models\Transaction::where('program_id', $row->id)->where('created_at', '>', date('Y-m-d').' 10:00:00')
                                    ->where('created_at', '<', date('Y-m-d').' 14:59:59');
                    if($trans_prime2->count()>9) {
                        $prime2_ads = '<span class="badge badge-success" title="10 Donasi Keatas">'.number_format($trans_prime2->count()).' | Rp.'.number_format($trans_prime2->sum('nominal_final')).'</span>';
                    } elseif($trans_prime2->count()>4) {
                        $prime2_ads = '<span class="badge badge-primary" title="5-9 Donasi">'.number_format($trans_prime2->count()).' | Rp.'.number_format($trans_prime2->sum('nominal_final')).'</span>';
                    } elseif($trans_prime2->count()>2) {
                        $prime2_ads = '<span class="badge badge-info" title="3-4 Donasi">'.number_format($trans_prime2->count()).' | Rp.'.number_format($trans_prime2->sum('nominal_final')).'</span>';
                    }  elseif($trans_prime2->count()>0) {
                        $prime2_ads = '<span class="badge badge-secondary" title="0-2 Donasi">'.number_format($trans_prime2->count()).' | Rp.'.number_format($trans_prime2->sum('nominal_final')).'</span>';
                    } else {
                        $prime2_ads = '<span class="badge badge-danger" title="0 Donasi">'.number_format($trans_prime2->count()).' | Rp.'.number_format($trans_prime2->sum('nominal_final')).'</span>';
                    }

                    $trans_prime3 = \App\Models\Transaction::where('program_id', $row->id)->where('created_at', '>', date('Y-m-d').' 15:00:00')
                                    ->where('created_at', '<', date('Y-m-d').' 23:59:59');
                    if($trans_prime3->count()>9) {
                        $prime3_ads = '<span class="badge badge-success" title="10 Donasi Keatas">'.number_format($trans_prime3->count()).' | Rp.'.number_format($trans_prime3->sum('nominal_final')).'</span>';
                    } elseif($trans_prime3->count()>4) {
                        $prime3_ads = '<span class="badge badge-primary" title="5-9 Donasi">'.number_format($trans_prime3->count()).' | Rp.'.number_format($trans_prime3->sum('nominal_final')).'</span>';
                    } elseif($trans_prime3->count()>2) {
                        $prime3_ads = '<span class="badge badge-info" title="3-4 Donasi">'.number_format($trans_prime3->count()).' | Rp.'.number_format($trans_prime3->sum('nominal_final')).'</span>';
                    }  elseif($trans_prime3->count()>0) {
                        $prime3_ads = '<span class="badge badge-secondary" title="0-2 Donasi">'.number_format($trans_prime3->count()).' | Rp.'.number_format($trans_prime3->sum('nominal_final')).'</span>';
                    } else {
                        $prime3_ads = '<span class="badge badge-danger" title="0 Donasi">'.number_format($trans_prime3->count()).' | Rp.'.number_format($trans_prime3->sum('nominal_final')).'</span>';
                    }

                    $prime1 = 'Jam 00:00 - 10:00 = '.$prime1_ads;
                    $prime2 = 'Jam 10:00 - 15:00 = '.$prime2_ads;
                    $prime3 = 'Jam 15:00 - 24:00 = '.$prime3_ads;
                    return $prime1.'<br>'.$prime2.'<br>'.$prime3;
                })
                ->addColumn('stats', function($row){
                    $view        = "<i class='fa fa-eye icon-gradient bg-malibu-beach'></i> ".number_format($row->count_view);
                    $read_more   = "<i class='fa fa-angle-double-down icon-gradient bg-malibu-beach'></i> ".number_format($row->count_read_more);
                    $amount_page = "<i class='fa fa-download icon-gradient bg-malibu-beach'></i> ".number_format($row->count_amount_page);

                    if($row->count_view>0 && $row->count_amount_page>0) {
                        $amount_per  = round($row->count_amount_page/$row->count_view*100, 2);
                    } else {
                        $amount_per  = 0;
                    }

                    if($row->count_view>0 && $row->count_read_more>0) {
                        $read_more_per  = round($row->count_read_more/$row->count_view*100, 2);
                    } else {
                        $read_more_per  = 0;
                    }
                    
                    
                    
                    return $view.'<br>'.$read_more.' ('.$read_more_per.'%) <br>'.$amount_page.' ('.$amount_per.'%)';
                })
                ->addColumn('donate', function($row){
                    $interest = "<i class='fa fa-file icon-gradient bg-malibu-beach'></i> ".number_format($row->count_pra_checkout);
                    $checkout = \App\Models\Transaction::where('program_id', $row->id)->count('id');
                    $count    = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->count('id');
                    
                    if($row->count_view>0 && $row->count_pra_checkout>0) {
                        $interest_per  = round($row->count_pra_checkout/$row->count_view*100, 2);
                    } else {
                        $interest_per  = 0;
                    }

                    if($checkout>0 && $row->count_view>0) {
                        $checkout_per = round($checkout/$row->count_view*100, 2);
                    } else {
                        $checkout_per = 0;
                    }
                    
                    if($count>0 && $checkout>0) {
                        $count_per = round($count/$checkout*100, 2);
                    } else {
                        $count_per = 0;
                    }

                    return $interest.' ('.$interest_per.'%)
                        <br> <i class="fa fa-shopping-cart icon-gradient bg-malibu-beach"></i> '.number_format($checkout).' ('.$checkout_per.'%)
                        <br> <i class="fa fa-heart icon-gradient bg-happy-green"></i> '.number_format($count).' ('.$count_per.'%)';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-xs">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'nominal', 'ads', 'stats', 'donate'])
                ->make(true);
        // }
    }


    /**
     * Show Donate from datatable program
     */
    public function showDonate(Request $request)
    {
        // $program = Program::where('id', $id)->first();
        $id             = $request->id;
        $dn             = date('Y-m-d');
        $donate_success = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count(),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->count(),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->count(),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->count()
        );
        $donate_success_rp = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final'),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->sum('nominal_final'),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->sum('nominal_final'),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->sum('nominal_final')
        );
        $donate_draft    = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count(),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->count(),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->count(),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->count()
        );
        $donate_draft_rp = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final'),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->sum('nominal_final'),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->sum('nominal_final'),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->sum('nominal_final')
        );

        $data1   = '<table class="table table-hover table-responsive mb-1">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>'.date('d-m-Y').'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-1 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-2 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-3 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-4 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-5 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-6 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-7 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-8 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-9 day')).'</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi Dibayar</td>
                                <td>'.number_format($donate_success[0]).'</td>
                                <td>'.number_format($donate_success[1]).'</td>
                                <td>'.number_format($donate_success[2]).'</td>
                                <td>'.number_format($donate_success[3]).'</td>
                                <td>'.number_format($donate_success[4]).'</td>
                                <td>'.number_format($donate_success[5]).'</td>
                                <td>'.number_format($donate_success[6]).'</td>
                                <td>'.number_format($donate_success[7]).'</td>
                                <td>'.number_format($donate_success[8]).'</td>
                                <td>'.number_format($donate_success[9]).'</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi Dibayar</td>
                                <td>'.number_format($donate_success_rp[0]).'</td>
                                <td>'.number_format($donate_success_rp[1]).'</td>
                                <td>'.number_format($donate_success_rp[2]).'</td>
                                <td>'.number_format($donate_success_rp[3]).'</td>
                                <td>'.number_format($donate_success_rp[4]).'</td>
                                <td>'.number_format($donate_success_rp[5]).'</td>
                                <td>'.number_format($donate_success_rp[6]).'</td>
                                <td>'.number_format($donate_success_rp[7]).'</td>
                                <td>'.number_format($donate_success_rp[8]).'</td>
                                <td>'.number_format($donate_success_rp[9]).'</td>
                            </tr>
                            <tr>
                                <td>Donasi Blm Dibayar</td>
                                <td>'.number_format($donate_draft[0]).'</td>
                                <td>'.number_format($donate_draft[1]).'</td>
                                <td>'.number_format($donate_draft[2]).'</td>
                                <td>'.number_format($donate_draft[3]).'</td>
                                <td>'.number_format($donate_draft[4]).'</td>
                                <td>'.number_format($donate_draft[5]).'</td>
                                <td>'.number_format($donate_draft[6]).'</td>
                                <td>'.number_format($donate_draft[7]).'</td>
                                <td>'.number_format($donate_draft[8]).'</td>
                                <td>'.number_format($donate_draft[9]).'</td>
                            </tr>
                            <tr>
                                <td>Donasi Blm Dibayar Rp</td>
                                <td>'.number_format($donate_draft_rp[0]).'</td>
                                <td>'.number_format($donate_draft_rp[1]).'</td>
                                <td>'.number_format($donate_draft_rp[2]).'</td>
                                <td>'.number_format($donate_draft_rp[3]).'</td>
                                <td>'.number_format($donate_draft_rp[4]).'</td>
                                <td>'.number_format($donate_draft_rp[5]).'</td>
                                <td>'.number_format($donate_draft_rp[6]).'</td>
                                <td>'.number_format($donate_draft_rp[7]).'</td>
                                <td>'.number_format($donate_draft_rp[8]).'</td>
                                <td>'.number_format($donate_draft_rp[9]).'</td>
                            </tr>
                        </tbody>
                    </table>';
        return $data1;
    }



    /**
     * Show Summary in this Program from datatable program
     */
    public function showSummary(Request $request)
    {
        $id            = $request->id;
        $dn            = date('Y-m-d');
        
        $payout_paid    = \App\Models\Payout::where('status', 'paid')->where('program_id', $id)->sum('nominal_approved');
        $payout_req     = \App\Models\Payout::where('status', 'request')->where('program_id', $id)->sum('nominal_approved');
        $payout_process = \App\Models\Payout::where('status', 'process')->where('program_id', $id)->sum('nominal_approved');
        $payout_reject  = \App\Models\Payout::where('status', 'reject')->where('program_id', $id)->sum('nominal_approved');
        $payout_cancel  = \App\Models\Payout::where('status', 'cancel')->where('program_id', $id)->sum('nominal_approved');
        $donate_sum     = Transaction::select('id')->where('status', 'success')->where('program_id', $id)->sum('nominal_final');
        $platform_fee   = $donate_sum*5/100;
        $ads_fee        = $donate_sum*20/100;
        $opex_fee       = $donate_sum*3/100;
        $final          = $donate_sum-$platform_fee-$ads_fee-$opex_fee-$payout_paid;

        $data1   = '<div class="row">
                <div class="col-6">
                    <table class="table table-hover table-responsive mb-1">
                        <tr>
                            <td class="text-start">Total Donasi</td>
                            <td>Rp. '.number_format($donate_sum).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Platform Fee 5%</td>
                            <td>Rp. '.number_format($platform_fee).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">ADS Fee 20%</td>
                            <td>Rp. '.number_format($ads_fee).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Operasional 3%</td>
                            <td>Rp. '.number_format($opex_fee).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Terbayar</td>
                            <td>Rp. '.number_format($payout_paid).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Sisa Penghimpunan</td>
                            <td>Rp. '.number_format($final).'</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-hover table-responsive mb-1">
                        <tr>
                            <th>Penyaluran</th>
                            <th>Nominal</th>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Diajukan</td>
                            <td>Rp. '.number_format($payout_req).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Sedang Diproses</td>
                            <td>Rp. '.number_format($payout_process).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Terbayar</td>
                            <td>Rp. '.number_format($payout_paid).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Ditolak</td>
                            <td>Rp. '.number_format($payout_reject).'</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Dibatalkan</td>
                            <td>Rp. '.number_format($payout_cancel).'</td>
                        </tr>
                    </table>
                </div>
            </div>';
        return $data1;
    }

    /**
     * Show Donate from datatable program
     */
    public function showSpend(Request $request)
    {
        // $program = Program::where('id', $id)->first();
        $id             = $request->id;
        $dn             = date('Y-m-d');
        $donate_success = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count(),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->count(),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->count(),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->count()
        );
        $donate_success_rp = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final'),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->sum('nominal_final'),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->sum('nominal_final'),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->sum('nominal_final')
        );
        $donate_draft    = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count(),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->count(),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->count(),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->count()
        );
        $donate_draft_rp = array(
            0 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final'),
            7 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-7 day')).'%')->sum('nominal_final'),
            8 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-8 day')).'%')->sum('nominal_final'),
            9 => Transaction::select('id')->where('program_id', $id)->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-9 day')).'%')->sum('nominal_final')
        );

        $data1   = '<table class="table table-hover table-responsive mb-1">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>'.date('d-m-Y').'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-1 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-2 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-3 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-4 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-5 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-6 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-7 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-8 day')).'</th>
                                <th>'.date('d-m-Y', strtotime(date('Y-m-d').'-9 day')).'</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>JML Donasi Dibayar</td>
                                <td>'.number_format($donate_success[0]).'</td>
                                <td>'.number_format($donate_success[1]).'</td>
                                <td>'.number_format($donate_success[2]).'</td>
                                <td>'.number_format($donate_success[3]).'</td>
                                <td>'.number_format($donate_success[4]).'</td>
                                <td>'.number_format($donate_success[5]).'</td>
                                <td>'.number_format($donate_success[6]).'</td>
                                <td>'.number_format($donate_success[7]).'</td>
                                <td>'.number_format($donate_success[8]).'</td>
                                <td>'.number_format($donate_success[9]).'</td>
                            </tr>
                            <tr>
                                <td>Rp Donasi Dibayar</td>
                                <td>'.number_format($donate_success_rp[0]).'</td>
                                <td>'.number_format($donate_success_rp[1]).'</td>
                                <td>'.number_format($donate_success_rp[2]).'</td>
                                <td>'.number_format($donate_success_rp[3]).'</td>
                                <td>'.number_format($donate_success_rp[4]).'</td>
                                <td>'.number_format($donate_success_rp[5]).'</td>
                                <td>'.number_format($donate_success_rp[6]).'</td>
                                <td>'.number_format($donate_success_rp[7]).'</td>
                                <td>'.number_format($donate_success_rp[8]).'</td>
                                <td>'.number_format($donate_success_rp[9]).'</td>
                            </tr>
                            <tr>
                                <td>Donasi Blm Dibayar</td>
                                <td>'.number_format($donate_draft[0]).'</td>
                                <td>'.number_format($donate_draft[1]).'</td>
                                <td>'.number_format($donate_draft[2]).'</td>
                                <td>'.number_format($donate_draft[3]).'</td>
                                <td>'.number_format($donate_draft[4]).'</td>
                                <td>'.number_format($donate_draft[5]).'</td>
                                <td>'.number_format($donate_draft[6]).'</td>
                                <td>'.number_format($donate_draft[7]).'</td>
                                <td>'.number_format($donate_draft[8]).'</td>
                                <td>'.number_format($donate_draft[9]).'</td>
                            </tr>
                            <tr>
                                <td>Donasi Blm Dibayar Rp</td>
                                <td>'.number_format($donate_draft_rp[0]).'</td>
                                <td>'.number_format($donate_draft_rp[1]).'</td>
                                <td>'.number_format($donate_draft_rp[2]).'</td>
                                <td>'.number_format($donate_draft_rp[3]).'</td>
                                <td>'.number_format($donate_draft_rp[4]).'</td>
                                <td>'.number_format($donate_draft_rp[5]).'</td>
                                <td>'.number_format($donate_draft_rp[6]).'</td>
                                <td>'.number_format($donate_draft_rp[7]).'</td>
                                <td>'.number_format($donate_draft_rp[8]).'</td>
                                <td>'.number_format($donate_draft_rp[9]).'</td>
                            </tr>
                        </tbody>
                    </table>';
        return $data1;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function submitSpend(Request $request)
    {
        $request->validate([
            'title'      => 'required|string',
            'id_program' => 'required|numeric',
            'date_time'  => 'required',
            'nominal'    => 'required'
        ]);

        $data                   = new \App\Models\ProgramSpend;
        $data->program_id       = $request->id_program;
        $data->title            = trim($request->title);
        $data->nominal_request  = str_replace('.', '', $request->nominal);
        $data->nominal_approved = str_replace('.', '', $request->nominal);
        $data->date_request     = $request->date_time.':00';
        $data->date_approved    = $request->date_time.':00';
        $data->approved_by      = 1;
        $data->type             = 'ads';
        $data->is_operational   = 1;
        $data->status           = 'done';
        $data->desc_request     = trim($request->title);
        $data->save();

        echo "success";
        // return redirect()->back();
    }
}
