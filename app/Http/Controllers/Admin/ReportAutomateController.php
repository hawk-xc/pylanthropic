<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Donatur;
use App\Models\DonaturMonthlyReport;

class ReportAutomateController extends Controller
{
    /**
     * Report Monthly View
     */
    public function monthly(Request $request)
    {
        return view('admin.report-auto.monthly');
    }

    /**
     * Donatur List to Insert => donatur_monthly_report
     */
    public function donaturList(Request $request)
    {
        $date = $request->date;

        if(isset($request->date)) {
            $date = date('Y-m', strtotime($request->date));
        } else {
            $date = date('Y-m');
        }

        $donatur  = Transaction::select('donatur_id')->where('created_at', 'like', $date.'%')->groupBy('donatur_id')->get();

        foreach ($donatur as $k => $v) {
            // insert table donatur_monthly_report
            $check_any = DonaturMonthlyReport::where('date', 'like', $date.'%')->where('donatur_id', $v->donatur_id)->select('id')->first();
            if(!isset($check_any->id)){
                DonaturMonthlyReport::create([
                    'donatur_id' => $v->donatur_id,
                    'date'       => date('Y-m-d', strtotime($date))
                ]);
            }
        }

        return 'success';
    }


    /**
     * Donatur List to Insert => donatur_monthly_report
     */
    public function updateDonate(Request $request)
    {
        $date = $request->date;

        if(isset($request->date)) {
            $date = date('Y-m', strtotime($request->date));
        } else {
            $date = date('Y-m');
        }

        $list = DonaturMonthlyReport::where('date', 'like', $date.'%')->whereNull('donate_count_all')->limit(800)->get();
        foreach ($list as $k => $v) {
            $count_all  = Transaction::where('donatur_id', $v->donatur_id)->where('created_at', 'like', $date.'%')->count();
            $sum_all    = Transaction::where('donatur_id', $v->donatur_id)->where('created_at', 'like', $date.'%')->sum('nominal_final');
            $count_paid = Transaction::where('donatur_id', $v->donatur_id)->where('created_at', 'like', $date.'%')->where('status', 'success')->count();
            $sum_paid   = Transaction::where('donatur_id', $v->donatur_id)->where('created_at', 'like', $date.'%')->where('status', 'success')->sum('nominal_final');

            DonaturMonthlyReport::where('id', $v->id)->update([
                'donate_count_all'  => $count_all,
                'donate_sum_all'    => $sum_all,
                'donate_count_paid' => $count_paid,
                'donate_sum_paid'   => $sum_paid
            ]);
        }

        return 'success';
    }

}
