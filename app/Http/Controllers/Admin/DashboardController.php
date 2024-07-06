<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;
use App\Models\TrackingVisitor;

class DashboardController extends Controller
{
    /**
     * Show the application's Dashboard Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dn              = date('Y-m-d');
        $sum_donate      = Transaction::where('status', 'success')->count('id');
        $sum_paid        = Transaction::where('status', 'success')->sum('nominal_final');
        $sum_paid_now    = Transaction::where('status', 'success')
                            ->where('created_at', 'like', date('Y-m').'%')->sum('nominal_final');
        $sum_transaction = Transaction::count('id');
        $sum_page_viewed = Program::sum('count_view');

        $donate_success  = array(
            0 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count()
        );
        $donate_success_rp = array(
            0 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final')
        );
        $donate_draft    = array(
            0 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count()
        );
        $donate_draft_rp = array(
            0 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final')
        );

        // Visitor landing page
        // for($i=0; $i<30; $i++) {
        //     $visitor_analytic[] = TrackingVisitor::where('page_view', 'landing_page')
        //                         ->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')
        //                         ->count();
        // }

        // $a                      = array_filter($visitor_analytic);
        // if( count($a) ) {
        //     $visitor_analytic_avg = array_sum($a)/count($a);
        // } else {
        //     $visitor_analytic_avg = 0;
        // }

        // // Visitor count klik tombol donasi
        // for($i=0; $i<30; $i++) {
        //     $click_donate[] = TrackingVisitor::where('page_view', 'amount')
        //                     ->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')->count();
        // }

        // // Visitor count donate / transaction
        // for($i=0; $i<30; $i++) {
        //     $donate_count[] = Transaction::where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')->count();
        // }

        // // Visitor count donate paid / success
        // for($i=0; $i<30; $i++) {
        //     $donate_paid[] = Transaction::where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-'.$i.' day')).'%')
        //                     ->count();
        // }

        return view('admin.dashboard', compact('sum_donate', 'sum_paid', 'sum_paid_now', 'sum_transaction', 'sum_page_viewed', 'donate_success', 'donate_success_rp', 'donate_draft', 'donate_draft_rp'));
    }

}
