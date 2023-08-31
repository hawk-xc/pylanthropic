<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;

use DataTables;

class ReportController extends Controller
{
    /**
     * Monthly Report
     */
    public function monthly()
    {
        $program_spend = \App\Models\ProgramSpend::where('type', 'ads')->where('status', 'done')->where('date_approved', 'like', date('Y-m').'%')
                        ->select('id')->sum('nominal_approved');
        $donate_sum    = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')->sum('nominal_final');
        
        $bca       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '1')->sum('nominal_final');
        $bsi       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '2')->sum('nominal_final');
        $bri       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '4')->sum('nominal_final');
        $bni       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '19')->sum('nominal_final');
        $mandiri   = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '3')->sum('nominal_final');
        $qris      = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '5')->sum('nominal_final');
        $gopay     = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '6')->sum('nominal_final');
        $shopeepay = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '7')->sum('nominal_final');
        $ovo       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '8')->sum('nominal_final');
        $dana      = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m').'%')
                        ->where('payment_type_id', '9')->sum('nominal_final');

                        
        $month_ago         = date('Y-m', strtotime(date('Y-m-d').'-1 month'));
        $program_spend_ago = \App\Models\ProgramSpend::where('type', 'ads')->where('status', 'done')->where('date_approved', 'like', $month_ago.'%')
                                ->select('id')->sum('nominal_approved');
        $donate_sum_ago    = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')->sum('nominal_final');

        $bca_ago       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '1')->sum('nominal_final');
        $bsi_ago       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '2')->sum('nominal_final');
        $bri_ago       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '4')->sum('nominal_final');
        $bni_ago       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '19')->sum('nominal_final');
        $mandiri_ago   = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '3')->sum('nominal_final');
        $qris_ago      = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '5')->sum('nominal_final');
        $gopay_ago     = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '6')->sum('nominal_final');
        $shopeepay_ago = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '7')->sum('nominal_final');
        $ovo_ago       = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '8')->sum('nominal_final');
        $dana_ago      = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $month_ago.'%')
                        ->where('payment_type_id', '9')->sum('nominal_final');

        return view('admin.report.monthly', compact('month_ago', 
                'program_spend', 'donate_sum', 'bca', 'bsi', 'bri', 'bni', 'mandiri', 'qris', 'gopay', 'shopeepay', 'ovo', 'dana',
                'program_spend_ago', 'donate_sum_ago', 'bca_ago', 'bsi_ago', 'bri_ago', 'bni_ago', 'mandiri_ago', 'qris_ago', 'gopay_ago', 'shopeepay_ago', 'ovo_ago', 'dana_ago'));
    }

    /**
     * Show the application's Dashboard Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function collection()
    {
        $dn                        = date('Y-m-d');
        $donate_success[0]         = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->count();
        for($i=1; $i<30; $i++) {
            $donate_success[$i]    = Transaction::select('id')->where('status', 'success')
                                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')->count();
        }

        $donate_success_rp[0]      = Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final');
        for($i=1; $i<30; $i++) {
            $donate_success_rp[$i] = Transaction::select('id')->where('status', 'success')
                                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')->sum('nominal_final');
        }

        $donate_draft[0]         = Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', $dn.'%')->count();
        for($i=1; $i<30; $i++) {
            $donate_draft[$i]    = Transaction::select('id')->where('status', '!=', 'success')
                                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')->count();
        }

        $donate_draft_rp[0]      = Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final');
        for($i=1; $i<30; $i++) {
            $donate_draft_rp[$i] = Transaction::select('id')->where('status', '!=', 'success')
                                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')->sum('nominal_final');
        }

        // Donasi Perjam
        $donate_perjam_count  = array(
            0 => Transaction::select('id')->where('created_at', 'like', '% 00:%')->count(),
            1 => Transaction::select('id')->where('created_at', 'like', '% 01:%')->count(),
            2 => Transaction::select('id')->where('created_at', 'like', '% 02:%')->count(),
            3 => Transaction::select('id')->where('created_at', 'like', '% 03:%')->count(),
            4 => Transaction::select('id')->where('created_at', 'like', '% 04:%')->count(),
            5 => Transaction::select('id')->where('created_at', 'like', '% 05:%')->count(),
            6 => Transaction::select('id')->where('created_at', 'like', '% 06:%')->count(),
            7 => Transaction::select('id')->where('created_at', 'like', '% 07:%')->count(),
            8 => Transaction::select('id')->where('created_at', 'like', '% 08:%')->count(),
            9 => Transaction::select('id')->where('created_at', 'like', '% 09:%')->count(),
            10 => Transaction::select('id')->where('created_at', 'like', '% 10:%')->count(),
            11 => Transaction::select('id')->where('created_at', 'like', '% 11:%')->count(),
            12 => Transaction::select('id')->where('created_at', 'like', '% 12:%')->count(),
            13 => Transaction::select('id')->where('created_at', 'like', '% 13:%')->count(),
            14 => Transaction::select('id')->where('created_at', 'like', '% 14:%')->count(),
            15 => Transaction::select('id')->where('created_at', 'like', '% 15:%')->count(),
            16 => Transaction::select('id')->where('created_at', 'like', '% 16:%')->count(),
            17 => Transaction::select('id')->where('created_at', 'like', '% 17:%')->count(),
            18 => Transaction::select('id')->where('created_at', 'like', '% 18:%')->count(),
            19 => Transaction::select('id')->where('created_at', 'like', '% 19:%')->count(),
            20 => Transaction::select('id')->where('created_at', 'like', '% 20:%')->count(),
            21 => Transaction::select('id')->where('created_at', 'like', '% 21:%')->count(),
            22 => Transaction::select('id')->where('created_at', 'like', '% 22:%')->count(),
            23 => Transaction::select('id')->where('created_at', 'like', '% 23:%')->count()
        );
        $donate_perjam_sum  = array(
            0 => Transaction::select('id')->where('created_at', 'like', '% 00:%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('created_at', 'like', '% 01:%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('created_at', 'like', '% 02:%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('created_at', 'like', '% 03:%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('created_at', 'like', '% 04:%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('created_at', 'like', '% 05:%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('created_at', 'like', '% 06:%')->sum('nominal_final'),
            7 => Transaction::select('id')->where('created_at', 'like', '% 07:%')->sum('nominal_final'),
            8 => Transaction::select('id')->where('created_at', 'like', '% 08:%')->sum('nominal_final'),
            9 => Transaction::select('id')->where('created_at', 'like', '% 09:%')->sum('nominal_final'),
            10 => Transaction::select('id')->where('created_at', 'like', '% 10:%')->sum('nominal_final'),
            11 => Transaction::select('id')->where('created_at', 'like', '% 11:%')->sum('nominal_final'),
            12 => Transaction::select('id')->where('created_at', 'like', '% 12:%')->sum('nominal_final'),
            13 => Transaction::select('id')->where('created_at', 'like', '% 13:%')->sum('nominal_final'),
            14 => Transaction::select('id')->where('created_at', 'like', '% 14:%')->sum('nominal_final'),
            15 => Transaction::select('id')->where('created_at', 'like', '% 15:%')->sum('nominal_final'),
            16 => Transaction::select('id')->where('created_at', 'like', '% 16:%')->sum('nominal_final'),
            17 => Transaction::select('id')->where('created_at', 'like', '% 17:%')->sum('nominal_final'),
            18 => Transaction::select('id')->where('created_at', 'like', '% 18:%')->sum('nominal_final'),
            19 => Transaction::select('id')->where('created_at', 'like', '% 19:%')->sum('nominal_final'),
            20 => Transaction::select('id')->where('created_at', 'like', '% 20:%')->sum('nominal_final'),
            21 => Transaction::select('id')->where('created_at', 'like', '% 21:%')->sum('nominal_final'),
            22 => Transaction::select('id')->where('created_at', 'like', '% 22:%')->sum('nominal_final'),
            23 => Transaction::select('id')->where('created_at', 'like', '% 23:%')->sum('nominal_final')
        );

        return view('admin.report.collection', compact('donate_success', 'donate_success_rp', 'donate_draft', 'donate_draft_rp', 'donate_perjam_count', 'donate_perjam_sum'));
    }

    /**
     * Show the application's settlement
     *
     */
    public function settlement(Request $request)
    {
        if($request->has('month')) {
                $month = '?month='.$request->month;
        } else {
                $month = '';
        }
        
        return view('admin.report.settlement', compact('month'));
    }

    /**
     * Datatables Mutation
     */
    public function datatablesMutation(Request $request)
    {
        if($request->has('month')) {
                $month = $request->month;
        } else {
                $month = date('Y-m');
        }

        $data = \App\Models\TransactionReal::where('created_at', 'like', $month.'%')->latest()->get();
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('nominal', function($row){
                    return number_format($row->nominal);
                })
                ->addColumn('transaction_id', function($row){
                    if($row->transaction_id!=null || $row->transaction_id>0) {
                        return $row->transaction_id;
                    } else {
                        return 'Belum';
                    }
                })
                ->addColumn('status', function($row){
                    if($row->transaction_id=='' || $row->transaction_id===null) {
                        $param  = $row->id.", 0, '".$row->status."', 'Rp.".number_format($row->nominal)."', '".$row->bank."'";
                    } else {
                        $param  = $row->id.", ".$row->transaction_id.", '".$row->status."', 'Rp.".number_format($row->nominal)."', '".$row->bank."'";
                    }
                    if($row->status=='notfound'){
                        $status = '<span class="badge badge-danger" style="cursor:pointer" onclick="editMutation('.$param.')">NotFound</span>';
                    } elseif ($row->status=='duplicate') {
                        $status = '<span class="badge badge-warning" style="cursor:pointer" onclick="editMutation('.$param.')">Duplicate</span>';
                    } elseif ($row->status=='hold') {
                        $status = '<span class="badge badge-secondary" style="cursor:pointer" onclick="editMutation('.$param.')">Hold</span>';
                    } elseif ($row->status=='matched') {
                        $status = '<span class="badge badge-success" style="cursor:pointer" onclick="editMutation('.$param.')">Matched</span>';
                    } else {
                        $status = '<span class="badge badge-light" style="cursor:pointer" onclick="editMutation('.$param.')">Draft</span>';
                    }
                    return $status;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->rawColumns(['nominal', 'status', 'transaction_id', 'created_at'])
                ->make(true);
    }

    /**
     * Datatables Transaction
     */
    public function datatablesTransaction(Request $request)
    {
        if($request->has('month')) {
                $month = $request->month;
        } else {
                $month = date('Y-m');
        }

        $trans_real = \App\Models\TransactionReal::where('created_at', 'like', $month.'%')->whereNotNull('transaction_id')
                    ->orderBy('transaction_id')->groupBy('transaction_id')->pluck('transaction_id');

        $data       = Transaction::where('transaction.created_at', 'like', $month.'%')->whereNotIn('transaction.id', $trans_real)
                        ->select('transaction.id', 'nominal_final', 'transaction.created_at', 'transaction.status', 'name')
                        ->join('payment_type', 'transaction.payment_type_id', 'payment_type.id')->latest()->get();
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('nominal_final', function($row){
                    return number_format($row->nominal_final).'<br>'.$row->status;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->rawColumns(['nominal_final', 'bank', 'created_at'])
                ->make(true);
    }

    /**
     * Mutation Edit
     */
    public function mutationEdit(Request $request)
    {
        $id_mutation = $request->id_mutation;
        $status      = $request->status;
        if($request->id_trans>0) {
                $id_trans    = $request->id_trans;
        } else {
                $id_trans    = null;
        }

        $trans_real = \App\Models\TransactionReal::where('id', $id_mutation)->update([ 
                'status'         => $status,
                'transaction_id' => $id_trans
        ]);

        return 'success';
    }


    /**
     * Mutation Matchin between bank and transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function mutationMatching()
    {
        // 'bca'=1, 'bsi'=2, 'bni'=19, 'bri'=4, 'mandiri'=3, 'gopay'=6, 'qris'=5, 'cash'=, 'Shopeepay'=7, 
        $type    = 'gopay';
        $type_id = 6;
        
        $trans_real = \App\Models\TransactionReal::whereNull('transaction_id')->where('status', 'draft')->where('bank', $type)->orderBy('id')->limit(2500)->get();
        foreach($trans_real as $v) {
                // Jenis Transfer
                // $trans_check = Transaction::where('status', 'success')->where('nominal_final', $v->nominal)->where('payment_type_id', $type_id);
                // khusus GOPAY
                $trans_check = Transaction::where('status', 'success')->where('invoice_number', $v->invoice_number)->where('payment_type_id', $type_id); 
                if($trans_check->count()>1) {           // duplicate kembar nominal dalam 1 jenis pembayaran
                        \App\Models\TransactionReal::where('id', $v->id)->update([
                                'status' => 'duplicate'
                        ]);
                } elseif(!empty($trans_check->first()->id)) {
                        \App\Models\TransactionReal::where('id', $v->id)->update([
                                'transaction_id' => $trans_check->first()->id,
                                'status' => 'matched'
                        ]);
                } else {
                        \App\Models\TransactionReal::where('id', $v->id)->update([
                                'status' => 'notfound'
                        ]);
                }
        }
        echo "FINISH";
    }
}
