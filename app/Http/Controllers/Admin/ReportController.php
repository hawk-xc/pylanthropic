<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;

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
     * Mutation Matchin between bank and transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function mutationMatching()
    {
        // 'bca','bsi','bni','bri','mandiri','gopay','cash'
        $trans_real = \App\Models\TransactionReal::whereNull('transaction_id')->where('status', 'draft')->where('bank', 'gopay')
                        ->orderBy('id')->limit(2500)->get();
        foreach($trans_real as $v) {
                // BSI = 2,   BRI = 4,   BNI = 19,   Mandiri = 3,   QRIS = 5,   Gopay = 6,   Shopeepay = 7,   BCA = 1,
                $trans_check = Transaction::where('status', 'success')->where('nominal_final', $v->nominal)->where('payment_type_id', '6');
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
